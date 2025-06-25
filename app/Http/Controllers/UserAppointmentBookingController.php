<?php

namespace App\Http\Controllers;

use App\Http\Enums\LinkStatusEnum;
use App\Models\AppointmentBooking;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Http\Enums\LinkLimitEnum;
use App\Models\AppointmentBookingLink;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class UserAppointmentBookingController extends Controller
{
    public function appointmentBookingList()
    {
        $param = request()->query();

        $user = auth()->user();
        $linkList = AppointmentBooking::with(['links:id,appointment_booking_id,url,type,status,medical_center'])
            ->where('user_id', $user->id)
            ->where('status', false)
            ->when(!empty($param), function ($query) use ($param) {
                $query->where('city', $param['c']);
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        $added_cards = $user->card()->count();
        $user_slip_numbers = $user->slip_number;

        return view('user.appointment-booking.index', compact(
            'linkList',
            'added_cards',
            'user_slip_numbers',
        ));
    }

    public function appointmentBookingCompleteList()
    {
        $user = auth()->user();
        $linkList = AppointmentBooking::with(['links:id,appointment_booking_id,url,type,status,medical_center'])
            ->where('user_id', $user->id)
            ->where('status', true)
            ->orderByDesc('created_at')
            ->paginate(10);

        $added_cards = $user->card()->count();
        $user_slip_numbers = $user->slip_number;

        return view('user.appointment-booking.complete-list', compact(
            'linkList',
            'added_cards',
            'user_slip_numbers',
        ));
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

        $center_list = slipCenterList();

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
            'center_list' => $center_list,
        ]);
    }

    public function storeAppointmentBooking(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'reference' => 'required',
            'city' => 'required|integer',
            'country_traveling_to' => 'required|in:SA',
            'center' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required|date|before_or_equal:today',
            'gender' => 'required|in:male,female',
            'marital_status' => 'required|in:unmarried,married',
            'passport_number' => 'required|unique:appointment_bookings,passport_number',
            'confirm_passport_number' => 'required|same:passport_number',
            'passport_issue_date' => 'required|date|before_or_equal:today',
            'passport_issue_place' => 'required',
            'passport_expiry_date' => 'required|numeric|in:5,10',
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

        $email = 'abdullahraju400@gmail.com';
        $phone_number = '+8801879272400';

        // $email = 'abrt400@gmail.com'; Fake
        // $phone_number = '+8801843276440'; Fake

        $existingLink = AppointmentBooking::where('passport_number', $validated['passport_number'])->exists();

        if ($existingLink) {
            return back()->with('error', 'You have already registered an appointment for booking.');
        }

        $user = auth()->user();

        AppointmentBooking::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'reference' => $validated['reference'],
            'center_name'  => $validated['center'],
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
            'passport_expiry_date' => Carbon::parse($validated['passport_issue_date'])->addYears($validated['passport_expiry_date'])->subDay(),
            'visa_type' => $validated['visa_type'],
            'email' => $email,
            'phone_number' => $phone_number,
            'nid_number' => $validated['nid_number'],
            'applied_position' => $validated['applied_position'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return redirect()->route('user.appointment.booking.list')->with('success', 'Link registration successful');
    }

    public function appointmentBookingEdit($passport_number)
    {
        $passport_number = trim($passport_number);

        $bookingItem = AppointmentBooking::where('passport_number', $passport_number)->firstOrFail();
        $bookingItem->passport_expire_validity = Carbon::parse($bookingItem->passport_issue_date)->diffInYears(Carbon::parse($bookingItem->passport_expiry_date)) + 1;

        $country = appointmentBookingCountry();

        $origin_country = array_filter($country, function ($key) {
            return $key === 'BD';
        }, ARRAY_FILTER_USE_KEY);

        $traveling_country = array_filter($country, function ($key) {
            return $key !== 'BD';
        }, ARRAY_FILTER_USE_KEY);

        return view('user.appointment-booking.edit-registration', [
            'bookingItem' => $bookingItem,
            'booking_types' => appointmentBookingTypes(),
            'origin_country' => $origin_country,
            'origin_city' => appointmentBookingCity(),
            'traveling_country' => $traveling_country,
            'nationality' => appointmentBookingNationality(),
            'gender' => [
                'male' => 'Male',
                'female' => 'Female'
            ],
            'marital_status' => [
                'unmarried' => 'Single',
                'married' => 'Married'
            ],
            'visa_type' => appointmentBookingVisaType(),
            'applied_position' => appointmentBookingAppliedPosition(),
            'center_list' => slipCenterList()
        ]);
    }

    public function updateAppointmentBookingEdit(Request $request, $passport_number)
    {
        $validated = $request->validate([
            'type' => 'required',
            'reference' => 'required',
            'city' => 'required|integer',
            'country_traveling_to' => 'required|in:SA',
            'center' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            'gender' => 'required|in:male,female',
            'marital_status' => 'required|in:unmarried,married',
            'passport_number' => [
                'required',
                Rule::unique('appointment_bookings', 'passport_number')->ignore($request->id)
            ],
            'confirm_passport_number' => 'required|same:passport_number',
            'passport_issue_date' => 'required',
            'passport_issue_place' => 'required',
            'passport_expiry_date' => 'required|numeric|in:5,10',
            'visa_type' => 'required|in:wv,fv',
            'nid_number' => 'required',
            'applied_position' => 'required|numeric',
            'confirm' => 'required'
        ]);

        $passport_number = trim($passport_number);
        $user = auth()->user();

        $bookingItem = AppointmentBooking::where(['user_id' => $user->id, 'passport_number' => $passport_number])->firstOrFail();

        try {
            $bookingItem->update([
                'type' => $validated['type'],
                'reference' =>  $validated['reference'],
                'center_name'  => $validated['center'],
                'city' => $validated['city'],
                'country_traveling_to' => $validated['country_traveling_to'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'dob' => Carbon::parse($validated['dob']),
                'gender' => $validated['gender'],
                'marital_status' => $validated['marital_status'],
                'passport_number' => $validated['passport_number'],
                'passport_issue_date' => Carbon::parse($validated['passport_issue_date']),
                'passport_issue_place' => $validated['passport_issue_place'],
                'passport_expiry_date' => Carbon::parse($validated['passport_issue_date'])->addYears($validated['passport_expiry_date'])->subDay(),
                'visa_type' => $validated['visa_type'],
                'nid_number' => $validated['nid_number'],
                'applied_position' => $validated['applied_position'],
                'updated_at' => Carbon::now()
            ]);
        } catch (Exception $exception) {
            return redirect()->route('user.appointment.booking.list')->with('error', 'Link update failed');
        }

        return redirect()->route('user.appointment.booking.list')->with('success', 'Link updated successful');
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

        $handler_app_url = config('app.handler_url') . '/book-appointment';

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

                    $item['uid'] = random_int(1111, 9999) . $item->id . random_int(1111, 9999);
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

    public function sendSubmitRequestNow()
    {
        $id = request()->id;

        if (empty($id)) {
            return response()->json([
                'status' => false,
                'message' => 'ID is required',
            ], 400);
        }

        $user = auth()->user();
        $cacheKey = 'appointment_bookings_user::' . $user->id;

        // Retrieve cached data (item_id => timestamp)
        $bookedItems = Cache::get($cacheKey, []);

        // if (array_key_exists($id, $bookedItems)) {
        //     $timestamp = $bookedItems[$id];
        //     if (now()->timestamp - $timestamp < 3600) { // Check if within 1 hour
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'You have already submitted this request within the last hour.',
        //         ]);
        //     }
        // }

        $appointmentBooking = AppointmentBooking::with(['links:id,appointment_booking_id,url,status'])
            ->where(['user_id' => $user->id, 'id' => $id])
            ->whereDoesntHave('links')
            ->first();

        if ($appointmentBooking) {
            if ($appointmentBooking->type === 'normal' && $appointmentBooking->links()->count() === 0) {
                $appointmentBooking->webhook_url = route('wafid.webhook');
                $appointmentBooking->webhook_type = 'POST';

                // cache current id for one hour
                // $bookedItems[$id] = now()->timestamp;
                // Cache::put($cacheKey, $bookedItems, now()->addHour()); // Store for 1 hour

                $appointmentBooking['uid'] = random_int(1111, 9999) . $appointmentBooking->id . random_int(1111, 9999);
                unset($appointmentBooking['id']);

                $handler_app_url = config('app.handler_url') . '/book-appointment';

                try {
                    $client = new Client();
                    $response = $client->post($handler_app_url, [
                        'json' => $appointmentBooking->toArray()
                    ]);

                    $body = $response->getBody()->getContents();
                    $decoded = json_decode($body, true);

                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    // Handle errors gracefully
                    return 'Request failed: ' . $e->getMessage();
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Request submitted successfully. Wait for few minutes to get the link.',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Appointment booking not found',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'You can not submit more than 1 request for the same appointment booking.',
        ]);
    }

    public function getAppointmentBookingList()
    {
        $user = auth()->user();
        $linkList = AppointmentBooking::with(['links:id,appointment_booking_id,url,type,status,medical_center'])
            ->where('user_id', $user->id)
            ->where('status', false)
            ->orderByDesc('created_at')
            ->paginate(10);

        foreach ($linkList as $appointmentBooking) {
            foreach ($appointmentBooking->links ?? [] as $link) {
                if (Carbon::parse($link->updated_at)->lt(Carbon::now()->subMinute())) {
                    $link->type = '';
                    $link->save();
                }

                $cacheKey = linkCacheKey($appointmentBooking->user_id, $link->id);

                if (Cache::has($cacheKey)) {
                    $link->ready_data = Cache::get($cacheKey, []);
                }
            }
        }

        $tbody = view('user.appointment-booking.render.tbody', ['linkList' => $linkList])->render();
        $pagination = view('user.appointment-booking.render.pagination', ['linkList' => $linkList])->render();

        return response()->json([
            'status' => true,
            'tbody' => $tbody,
            'pagination' => $pagination,
        ]);
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

    public function getDataforLocal()
    {
        $users = User::select(['id'])->where('has_link_permission', true)->get();

        $appointmentBooking = [];
        foreach ($users ?? [] as $user) {
            if ($user->appointmentBooking()->count() === 0) {
                continue;
            } // todo: add this condition into the query

            $appointmentBooking[] = AppointmentBooking::with(['user:id,name', 'links:id,appointment_booking_id,url,status,created_at'])
                ->where('user_id', $user->id)
                ->whereRaw("
                CASE
                    WHEN type = 'normal' THEN (SELECT COUNT(*) FROM appointment_booking_links WHERE appointment_booking_links.appointment_booking_id = appointment_bookings.id) < ?
                    WHEN type = 'normal_plus' THEN (SELECT COUNT(*) FROM appointment_booking_links WHERE appointment_booking_links.appointment_booking_id = appointment_bookings.id) < ?
                    WHEN type = 'special' THEN (SELECT COUNT(*) FROM appointment_booking_links WHERE appointment_booking_links.appointment_booking_id = appointment_bookings.id) < ?
                    WHEN type = 'special_plus' THEN (SELECT COUNT(*) FROM appointment_booking_links WHERE appointment_booking_links.appointment_booking_id = appointment_bookings.id) < ?
                    ELSE 0
                END = 1
            ", [
                    LinkLimitEnum::NORMAL->value,
                    LinkLimitEnum::NORMAL_PLUS->value + 1,
                    LinkLimitEnum::SPECIAL->value + 1,
                    LinkLimitEnum::SPECIAL_PLUS->value + 1
                ])
                ->where(function ($query) {
                    $query->whereDoesntHave('links')
                        ->orWhereRaw('(
                        SELECT MAX(created_at)
                        FROM appointment_booking_links
                        WHERE appointment_booking_links.appointment_booking_id = appointment_bookings.id
                    ) < ?', [Carbon::now()->subMinutes(60)]);
                })
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($appointmentBooking) {
                    $appointmentBooking->dob = Carbon::parse($appointmentBooking->dob)->format('d-m-Y');
                    $appointmentBooking->passport_issue_date = Carbon::parse($appointmentBooking->passport_issue_date)->format('d-m-Y');
                    $appointmentBooking->passport_expiry_date = Carbon::parse($appointmentBooking->passport_expiry_date)->format('d-m-Y');

                    return $appointmentBooking;
                })
                ->toArray();
        }

        $appointmentBooking = array_filter($appointmentBooking);

        return response()->json([
            'appointment_booking' => $appointmentBooking,
            'webhook_url' => route('wafid.webhook')
        ]);
    }

    const TOKEN = 'ae90e96b-b5e7-4942-a829-833dcd98c2c6';
    const BDUrl = 'https://api.brightdata.com/request?async=true';

    public function scrapPaymentPageData()
    {
//        $link_id = request()->id;
        $link_id = AppointmentBooking::find(1)->id;

        abort_if(empty($link_id), 400);

        $appointment_link = AppointmentBookingLink::find($link_id);
        $appointment = $appointment_link->appointmentBooking()->where('user_id', auth()->user()->id)->first();

        abort_if(empty($appointment), 404);

        $response = Http::timeout(30)->withToken(self::TOKEN)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0 Safari/537.36',
                'Referer' => 'https://wafid.com/',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])
            ->send('POST', 'https://api.brightdata.com/request?async=true', [
                'json' => [
                    'zone' => 'wafa',
                    'url' => $appointment_link->url,
                    'format' => 'raw',
                    'method' => 'GET',
                    'country' => 'BD'
                ]
            ]);

        dd($response->ok(), $response->status(), $response->body());

        if ($response->ok()) {
            $html = $response->body();
            $crawler = new Crawler($html);

            $inputs = $crawler->filter('input');

            $inputValues = [];
            foreach ($inputs as $input) {
                $name = $input->getAttribute('name');
                $value = $input->getAttribute('value');

                if ($name) {
                    $inputValues[$name] = $value;
                }
            }

//             $inputValues['card_holder_name'] = 'MD. JAMAL UDDIN';
//             $inputValues['card_number'] = '4777920003651645';
//             $inputValues['expiry_date'] = '2901';
//             $inputValues['card_security_code'] = '215';

            $inputValues['card_holder_name'] = 'MD MOHIUDDIN';
            $inputValues['card_number'] = '5293668251515853';
            $inputValues['expiry_date'] = '2703';
            $inputValues['card_security_code'] = '004';

            $response = Http::withOptions([
                'allow_redirects' => [
                    'max' => 10,     // default is 5
                    'track_redirects' => true,   // adds X-Guzzle-Redirect-History headers
                    'strict' => true,   // RFC-compliant redirect rules
                ],
            ])->asForm()->post('https://checkout.payfort.com/FortAPI/paymentPage', $inputValues);

            $html = $response->body();

            preg_match('/var\s+returnUrlParams\s*=\s*{.*?};/s', $html, $matches);

            if (!empty($matches)) {
                $jsBlock = $matches[0];
                $jsString = str_replace('var returnUrlParams = ', '', $jsBlock);
                $cleanJson = rtrim($jsString, ';');

                $params = json_decode($cleanJson, true);

                $formFields = [];
                foreach ($params ?? [] as $name => $value) {
                    $formFields[$name] = $value;
                }

                $finalUrl = null;
//                $response = Http::withToken(self::TOKEN)
//                    ->withHeaders([
//                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0 Safari/537.36',
//                        'Referer' => 'https://wafid.com/',
//                        'Content-Type' => 'application/json',
//                        'Accept' => 'application/json'
//                    ])
//                    ->withOptions([
//                    'on_stats' => function (\GuzzleHttp\TransferStats $stats) use (&$finalUrl) {
//                        $finalUrl = (string)$stats->getEffectiveUri();
//                    }
//                ])->send('POST', self::BDUrl, [
//                        'json' => [
//                            'zone' => 'wafa',
//                            'url' => $appointment_link->url,
//                            'method' => 'POST',
//                            'country' => 'BD',
//                            'format' => 'raw',
//                            'data_format' => 'form',
//                            'body' => http_build_query($formFields),
//                        ]
//                    ]);
//
//                dd($finalUrl, $response, $response->body());

//                $response = Http::withToken(self::TOKEN)
//                    ->withOptions([
//                        'proxy' => [
//                            'http' => self::BDUrl,
//                            'https' => self::BDUrl,
//                        ]
//                    ])
//                    ->withHeaders([
//                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0 Safari/537.36',
//                    'Referer' => 'https://wafid.com/',
//                    'Content-Type' => 'application/json',
//                    'Accept' => 'application/json'
//                    ])->asForm()->post($appointment_link->url, $formFields);
//
//                dd($finalUrl, $response, $response->body());

//                $proxy_username = 'brd-customer-hl_5fe89123-zone-wafa';
//                $proxy_password = '23zwj28mn03a';
//                $proxy_host = 'brd.superproxy.io';
//                $proxy_port = '33335';
//
//                $proxy = sprintf(
//                    'http://%s:%s@%s:%d',
//                    $proxy_username,
//                    $proxy_password,
//                    $proxy_host,
//                    $proxy_port
//                );
//
//                $httpClient = HttpClient::create([
//                    'proxy' => $proxy,
//                    'timeout' => 30,
//                    'headers' => [
//                        'User-Agent' => $this->getRandomUserAgent(), // Use a dynamic User-Agent
//                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
//                        'Accept-Language' => 'en-US,en;q=0.9',
//                        'Accept-Encoding' => 'gzip, deflate, br', // Indicate support for compression
//                        'Connection' => 'keep-alive',
//                        'DNT' => '1', // Do Not Track (optional, but good to include)
//                        'Upgrade-Insecure-Requests' => '1',
//                    ],
//                ]);
//
//                $client = new HttpBrowser($httpClient);
//
//                $crawler = $client->request('POST', $appointment_link->url, [], [], [
//                    'HTTP_CONTENT_TYPE' => 'application/x-www-form-urlencoded',
//                ], http_build_query($formFields));
//                $response = $client->getResponse();
//                dd($response, $response->getContent(),$crawler->getUri());

                $response = Http::withOptions([
//                    'proxy' => 'http://Raju_Medxpert_VUTi9:Medx_12345678@unblock.oxylabs.io:60000',
//                    'verify' => false
                ])
                    ->withHeaders([
                        'Content-Type' => 'application/x-www-form-urlencoded',
//                        'X-Oxylabs-Render' => 'html'
                    ])->asForm()->post($appointment_link->url, $formFields);

                dd($response->status(), $response->body(), $response->headers(), $response);

                $parsed = parse_url($finalUrl);
                parse_str($parsed['query'] ?? '', $queryParams);

                // get initiate3dsSimpleRedirectForm form action and it's input name and value
                $html = $response->body();
                $crawler = new Crawler($html);
                $form = $crawler->filter('form#initiate3dsSimpleRedirectForm')->first();

                // Get form action
                $action = $form->attr('action');

                $inputs = [];
                $form->filter('input')->each(function ($node) use (&$inputs) {
                    $name = $node->attr('name');
                    $value = $node->attr('value') ?? '';
                    if ($name !== null) {
                        $inputs[$name] = $value;
                    }
                });

                // Step 1: Fill out browser simulation fields manually
                $browserData = [
                    'token' => $queryParams['token'],
                    'threeDs2BrowserDataScreenWidth' => 1536,
                    'threeDs2BrowserDataScreenHeight' => 864,
                    'threeDs2BrowserDataColorDepth' => 24,
                    'threeDs2BrowserDataUserAgent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0', 0, 255),
                    'threeDs2BrowserDataIPAddress' => '103.126.150.99',
                    'threeDs2BrowserDataJavaEnabled' => false,
                    'threeDs2BrowserDataLanguage' => 'en-US',
                    'threeDs2BrowserDataTimeZoneOffset' => '-360', // This should ideally come from JS, mock it if needed
                ];

                // Step 2: Send the first form (initiate3dsSimpleRedirectForm)
                $response1 = Http::asForm()->post('https://checkout.payfort.com/FortAPI/redirectionResponse/resume3ds2AfterDDCUrl', $browserData);

                // Step 3: Send the second form (resume3ds2AfterDDCForm)
                $response2 = Http::asForm()->post($action, $inputs);

                $html = $response2->body();
                $crawler = new Crawler($html);
                $form = $crawler->filter('form#fingerprintForm')->first();

                // Get form action
                $action = $form->attr('action');
                $input = $form->filter('input[name="vdipMethodData"]')->attr('value');

                $response = Http::withOptions([
                    'verify' => storage_path('certs/cacert.pem')
                ])->post($action, [
                    'vdipMethodData' => $input,
                ]);

                dd($response, $response->body(), $response->json());

                // Step 4: Output (or handle) results
                dd($response2, [
                    'initiate_response_status' => $response1->status(),
                    'resume_response_status' => $response2->status(),
//                    'response 1 body' => $response1->body(),
//                    'response 1 json' => $response1->json(),
                    'response 2 body' => $response2->body(),
                    'response 2 json' => $response2->json()
                ]);
            } else {
                echo "No match found.";
            }

            return response($html, 200)->header('Content-Type', 'text/html');
        }

        return response()->json([
            'status' => false,
            'data' => []
        ]);
    }

    function getRandomUserAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:126.0) Gecko/20100101 Firefox/126.0',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 10; SM-G973F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edge/125.0.0.0',
        ];
        return $userAgents[array_rand($userAgents)];
    }

    public function checkLastPage(Request $request)
    {
        $url = str_replace('pay', 'slip', $request->current_url);
        $response = Http::get($url);

        $medical_center = '';
        if ($response->status() === 200) {
            $html = $response->body();
            $crawler = new Crawler($html);

            $firstTd = $crawler->filter('table.mc-table tbody tr')->first()->filter('td')->first();

            $medical_center = $firstTd->text();

            $link_data = AppointmentBookingLink::find($request->link_id);
            $link_data->status = LinkStatusEnum::PAID->value;
            $link_data->medical_center = $medical_center;
            $link_data->save();
        }

        return response()->json([
            'status' => $response->ok(),
            'status_code' => $response->status(),
            'medical_center' => $medical_center,
            'message' => $response->ok() ? 'Payment Complete' : 'Payment Failed'
        ]);
    }

    public function errorPayment(Request $request)
    {
        return redirect()->route('user.appointment.booking.list')->with('error', 'Payment Failed');
    }

    public function lastPayment(Request $request)
    {
        dd($request);
    }

    //https://checkout.payfort.com/FortAPI/redirectionResponse/threeDs2RedirectURL?token=165377CJhBC3Y96h0NmmDK7LYVSmYd
    //https://checkout.payfort.com/FortAPI/redirectionResponse/threeDs2RedirectURL?token=70391bkN9hR0LOhr9JdgO0TtBNXuzU

    // BrightData API Key: ae90e96b-b5e7-4942-a829-833dcd98c2c6
}
