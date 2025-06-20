<?php

namespace App\Http\Controllers;

use App\Jobs\InitPaymentJob;
use App\Jobs\PayPaymentJob;
use App\Models\AppointmentBooking;
use App\Models\AppointmentBookingLink;
use App\Models\User;
use App\Services\MedicalApplicationAppointmentService;
use Carbon\Carbon;
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
        Cache::flush();
        $validated = $request->validate([
            'id' => 'required|numeric',
        ]);

        $user = auth()->user();

        if (!$user->card()->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'You don\'t have a card associated with your account.',
            ]);
        }

        $appointment_booking = AppointmentBooking::with(['links:id,appointment_booking_id,url'])
            ->where('id', $validated['id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$appointment_booking) {
            return response()->json([
                'status' => false,
                'message' => 'Appointment booking not found.',
            ]);
        }

        if ($appointment_booking) {
            $links = $appointment_booking->links->toArray() ?? [];
            $card_data = $user->card?->toArray() ?? [];

            foreach ($links as $link) {
                InitPaymentJob::dispatch(
                    appointment_booking_id: $appointment_booking->id,
                    appointment_booking_link_id: $link['id'] ?? null,
                    user_id: $user->id,
                    link: $link['url'],
                    card_data: $card_data
                );
            }

            $readyCacheKey = readyForPaymentCacheKey();
            Cache::put($readyCacheKey, [$appointment_booking->id => now()], now()->addHours(2));
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment process has been started. Wait for few minutes.'
        ]);
    }

    public function setPaymentLinks(Request $request)
    {
        $user_id = $request->user_id;
        $appointment_id = $request->appointment_booking_id;
        $appointment_booking_link_id = $request->appointment_booking_link_id;
        $link = $request->link;

        if ($user_id && $appointment_id && $link) {
            $cacheKey = linkCacheKey($user_id, $appointment_booking_link_id);

//            if (!Cache::has($cacheKey)) {
            Cache::put($cacheKey, ['link_id' => $appointment_booking_link_id, 'link' => $link], now()->addHours(2));
//            }
        }
    }

    public function payOnPaymentLink(Request $request)
    {
        $validated = $request->validate([
            'pay_id' => 'required|numeric',
        ]);

        $current_user = auth()->user();
        if ($current_user->slip_number <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'আপনার স্লিপ পেয় কোটা শেষ',
            ]);
        }

        $booking_link_data = AppointmentBookingLink::where('id', $validated['pay_id'])->first();

        $flag = false;
        if ($booking_link_data) {
            $booking_data = $booking_link_data->appointmentBooking;
            $user = $booking_data->user;

            $cacheKey = linkCacheKey($user->id, $booking_link_data->id);

            if (Cache::has($cacheKey)) {
                $cacheData = Cache::get($cacheKey);

                PayPaymentJob::dispatch(
                    link_id: $cacheData['link_id'],
                    link: $cacheData['link'],
                );

                Cache::forget($cacheKey);

                $booking_link_data->type = 'paying';
                $booking_link_data->save();

                $flag = true;
            }
        }

        return response()->json([
            'status' => $flag
        ]);
    }

    public function getSlipInfo(Request $request)
    {
        $status = $request->status;
        $link_id = $request->link_id;
        $success_link = $request->success_link;
        $message = $request->message;

        if ($status && $link_id) {
            $appointment_booking_link = AppointmentBookingLink::find($link_id);

            if ($appointment_booking_link) {
                $appointment_booking = $appointment_booking_link->appointmentBooking;

                $user = User::find($appointment_booking->user_id);

                if ($status === 'success') {
                    $appointment_booking->note = 'Payment Success';
                    $appointment_booking->save();

                    $appointment_booking_link->medical_center = $message;
                    $appointment_booking_link->status = 'paid';
                    $appointment_booking_link->save();

                    $user->slip_number -= 1;
                    $user->save();
                } else if ($status === 'failed') {
                    $appointment_booking->note = $message;
                    $appointment_booking->save();
                }

                $appointment_booking_link->type = '';
                $appointment_booking_link->save();

//                $cacheKey = linkCacheKey($user->id, $link_id);
//                $cacheItem = Cache::get($cacheKey);
//                unset($cacheItem['link']);
//                Cache::put($cacheKey, $cacheItem, now()->addHours(2));
            }
        }
    }

    public function completeAppointment(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric'
        ]);

        $appointment_booking = AppointmentBooking::with('links')->find($validated['id']);

        if ($appointment_booking->links->count() === 0) {
            return response()->json([
                'status' => false,
                'message' => 'No links found for this appointment booking.'
            ]);

        }

        if ($appointment_booking) {
            $appointment_booking->status = true;
            $appointment_booking->save();
        }

        if ($appointment_booking->status) {
            MedicalApplicationAppointmentService::createApplication($appointment_booking);
        }

        return response()->json([
            'status' => true,
            'message' => 'Appointment booking has been completed.',
            'appointment_booking_id' => $appointment_booking->id
        ]);
    }
}
