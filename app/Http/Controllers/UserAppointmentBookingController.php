<?php

namespace App\Http\Controllers;

use App\Models\AppointmentBooking;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserAppointmentBookingController extends Controller
{
    public function appointmentBookingList()
    {
        $user = auth()->user();
        $linkList = AppointmentBooking::with(['links:id,appointment_booking_id,url,type,status'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.appointment-booking.index', compact('linkList'));
    }

    public function appointmentBookingRegistration()
    {
        $booking_types = appointmentBookingTypes();
        $country = appointmentBookingCountry();

        $origin_country = array_filter($country, function ($key) {
            return $key === 'BD';
        }, ARRAY_FILTER_USE_KEY);

        $city = appointmentBookingCity();

        $traveling_country = array_filter($country, function ($key) {
            return $key !== 'BD';
        }, ARRAY_FILTER_USE_KEY);

        $nationality = appointmentBookingNationality();

        $gender = [
            'male' => 'Male',
            'female' => 'Female'
        ];
        $marital_status = [
            'unmarried' => 'Single',
            'married' => 'Married'
        ];

        $visa_type = appointmentBookingVisaType();
        $applied_position = appointmentBookingAppliedPosition();

        return view('user.appointment-booking.registration', [
            'booking_types' => $booking_types,
            'origin_country' => $origin_country,
            'origin_city' => $city,
            'traveling_country' => $traveling_country,
            'nationality' => $nationality,
            'gender' => $gender,
            'marital_status' => $marital_status,
            'visa_type' => $visa_type,
            'applied_position' => $applied_position,
        ]);
    }

    public function storeAppointmentBooking(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'city' => 'required|integer',
            'country_traveling_to' => 'required|in:SA',
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            'gender' => 'required|in:male,female',
            'marital_status' => 'required|in:unmarried,married',
            'passport_number' => 'required',
            'confirm_passport_number' => 'required|same:passport_number',
            'passport_issue_date' => 'required',
            'passport_issue_place' => 'required',
            'passport_expiry_date' => 'required',
            'visa_type' => 'required|in:wv,fv',
            // 'email' => 'required|email',
            // 'phone_number' => 'required',
            'nid_number' => 'required',
            'applied_position' => 'required|numeric',
            'confirm' => 'required'
        ], [
            'dob.required' => 'Date of Birth is required',
            'confirm.required' => 'You have to acknowledge that the information you have provided in this form is complete, true, and accurate'
        ]);

//        $email = 'abdullahraju400@gmail.com';
//        $phone_number = '+8801879272400';

        $email = 'abrt400@gmail.com';
        $phone_number = '+8801843276440';

        $user = auth()->user();

        $existingLink = AppointmentBooking::where(['user_id' => $user->id, 'passport_number' => $validated['passport_number']])->orderBy('id', 'desc')->first();

        if ($existingLink && $existingLink->created_at->diffInHours(now()) < 1) {
            return back()->with('error', 'You have already registered an appointment for booking. Please try again after an hour.');
        }

        $appointmentBooking = AppointmentBooking::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'country' => 'BD',
            'city' => $validated['city'],
            'country_traveling_to' => $validated['country_traveling_to'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'dob' => Carbon::parse($validated['dob']),
            'nationality' => 15,
            'gender' => $validated['gender'],
            'marital_status' => $validated['marital_status'],
            'passport_number' => $validated['passport_number'],
            'passport_issue_date' => Carbon::parse($validated['passport_issue_date']),
            'passport_issue_place' => $validated['passport_issue_place'],
            'passport_expiry_date' => Carbon::parse($validated['passport_expiry_date']),
            'visa_type' => $validated['visa_type'],
            'email' => $email,
            'phone_number' => $phone_number,
            'nid_number' => $validated['nid_number'],
            'applied_position' => $validated['applied_position'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return redirect()->route('user.appointment-booking.index')->with('success', 'Link registration successful');
    }

    public function sendSubmitRequest()
    {
        $user = auth()->user();
        $cacheKey = 'appointment_bookings_user::' . $user->id;

        // Retrieve cached data (item_id => timestamp)
        $bookedItems = Cache::get($cacheKey, []);

        $oneHourAgo = now()->subHour()->timestamp;

        // Get only the item IDs that are still within 1 hour window
        $recentlyBookedIds = collect($bookedItems)
            ->filter(fn($timestamp) => $timestamp >= $oneHourAgo)
            ->keys()
            ->toArray();


        $appointmentBooking = AppointmentBooking::with(['links:id,appointment_booking_id,url,status'])
            ->where('user_id', $user->id)
            ->when(!empty($recentlyBookedIds), function ($query) use ($recentlyBookedIds) {
                $query->whereNotIn('id', $recentlyBookedIds);
            })
            ->whereDoesntHave('links')
            ->orderBy('id', 'desc')
            ->get();

        $handler_app_url = config('app.handler_url'). '/book-appointment';

        foreach ($appointmentBooking as $item) {
            if ($item->type === 'normal') {
                if ($item->links()->count() === 0) {
                    $item->webhook_url = route('wafid.webhook');
                    $item->webhook_type = 'POST';

                    // cache current id for one hour
                    $bookedItems = Cache::get($cacheKey, []);

                    if (array_key_exists($item->id, $bookedItems)) {
                        continue;
                    } else {
                        $bookedItems[$item->id] = now()->timestamp;
                        Cache::put($cacheKey, $bookedItems, now()->addHour()); // Store for 1 hour
                    }

                    $item['uid'] = random_int(1111,9999).$item->id.random_int(1111,9999);
                    unset($item['id']);

                    $client = new Client();
                    $client->post($handler_app_url, [
                        'json' => $item->toArray()
                    ]);

                    sleep(3);
                    break;
                }
            }
        }
    }

    public function wafidWebhook(Request $request)
    {
        $data = $request->all();

        if (isset($data['uid']) && isset($data['url'])) {
            $uid = $data['uid'];
            $url = $data['url'];

            $original_id = substr($uid, 4, -4);

            $appointmentBooking = AppointmentBooking::where('id', $original_id)->first();

            if ($appointmentBooking) {
                $appointmentBooking->links()->create([
                    'appointment_booking_id' => $appointmentBooking->id,
                    'url' => $url
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Link created successfully',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Appointment booking not found',
            ], 404);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid data received',
        ], 400);
    }

    private function encryptId($id)
    {
        $key = base64_decode('base64:U29tZVN1cGVyU2VjcmV0S2V5U29SZWFsbHlTZWN1cmU=');
        $iv = '1234567890123456';

        return base64_encode(openssl_encrypt($id, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }
}
