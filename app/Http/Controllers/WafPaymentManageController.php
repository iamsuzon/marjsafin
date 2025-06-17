<?php

namespace App\Http\Controllers;

use App\Jobs\InitPaymentJob;
use App\Models\AppointmentBooking;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WafPaymentManageController extends Controller
{
    public function initPaymentProcess(Request $request)
    {
        $rest_of_appointment_bookings = Cache::get(readyForPaymentCacheKey());

        if ($rest_of_appointment_bookings) {
            $appointment_booking_id = $rest_of_appointment_bookings['appointment_booking_id'] ?? null;

            if ($appointment_booking_id) {
                // get the user_id from the appointment booking
                $appointment_booking = AppointmentBooking::find($appointment_booking_id);
                if ($appointment_booking) {
                    $user = $appointment_booking->user;

                    if ($user) {
                        $user_id = $user->id;

                        $appointment_booking_links = $appointment_booking->links->pluck('url', 'id')->toArray();

                        foreach ($appointment_booking_links as $link_id => $link) {
                            $cacheKey = linkCacheKey($user_id, $appointment_booking_id);
                            if (! Cache::has($cacheKey)) {
                                $cachedLink = Cache::get($cacheKey);
                                if ($cachedLink === $link) {
                                    // Dispatch the job for each link
                                    InitPaymentJob::dispatch(
                                        appointment_booking_id: $appointment_booking_id,
                                        user_id: $user_id,
                                        link: $link,
                                        card_data: $user->card?->toArray() ?? []
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }


//        $validated = $request->validate([
//            'id' => 'required|numeric',
//        ]);
//
//        $user = auth()->user();
//
//        if (! $user->card()->exists()) {
//            return response()->json([
//                'status' => false,
//               'message' => 'You don\'t have a card associated with your account.',
//            ]);
//        }
//
//        $appointment_booking = AppointmentBooking::with(['links:id,appointment_booking_id,url'])
//            ->where('id', $validated['id'])
//            ->where('user_id', $user->id)
//            ->first();
//
//        if (! $appointment_booking) {
//            return response()->json([
//                'status' => false,
//                'message' => 'Appointment booking not found.',
//            ]);
//        }
//
//        if ($appointment_booking) {
//            $links = $appointment_booking->links->toArray() ?? [];
//            $card_data = $user->card?->toArray() ?? [];
//
//            foreach ($links as $link) {
//                InitPaymentJob::dispatch(
//                    appointment_booking_id: $appointment_booking->id,
//                    appointment_booking_link_id: $link['id'] ?? null,
//                    user_id: $user->id,
//                    link: $link['url'],
//                    card_data: $card_data
//                );
//            }
//
//            $readyCacheKey = readyForPaymentCacheKey();
//            Cache::put($readyCacheKey, ['appointment_booking_id' => $appointment_booking->id], now()->addHours(2));
//        }
//
//        return response()->json([
//            'status' => true,
//            'message' => 'Payment process has been started. Wait for few minutes.'
//        ]);
    }

    public function setPaymentLinks(Request $request)
    {
        $user_id = $request->user_id;
        $appointment_id = $request->appointment_booking_id;
        $appointment_booking_link_id = $request->appointment_booking_link_id;
        $link = $request->links;

        if ($user_id && $appointment_id && $link) {
            $cacheKey = linkCacheKey($user_id, $appointment_booking_link_id);

            if (! Cache::has($cacheKey)) {
                Cache::put($cacheKey, ['link_id' => $appointment_booking_link_id, 'link' => $link], now()->addHours(2));
            }
        }
    }
}
