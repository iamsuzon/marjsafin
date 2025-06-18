<?php

namespace App\Console\Commands;

use App\Jobs\InitPaymentJob;
use App\Models\AppointmentBooking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CreatePaymentLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment-link:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rest_of_appointment_bookings = Cache::get(readyForPaymentCacheKey());

        if ($rest_of_appointment_bookings) {
            foreach ($rest_of_appointment_bookings ?? [] as $array_key_id => $time) {
                if (Carbon::parse($time)->addHours(2)->isPast())
                {
                    unset($rest_of_appointment_bookings[$array_key_id]);
                    Cache::set(readyForPaymentCacheKey(), $rest_of_appointment_bookings);
                }
                else
                {
                    $appointment_booking_id = $array_key_id;

                    if ($appointment_booking_id) {
                        // get the user_id from the appointment booking
                        $appointment_booking = AppointmentBooking::find($appointment_booking_id);
                        if ($appointment_booking) {
                            $user = $appointment_booking->user;

                            if ($user) {
                                $user_id = $user->id;

                                $appointment_booking_links = $appointment_booking->links->pluck('url', 'id')->toArray();

                                foreach ($appointment_booking_links as $link_id => $link) {
                                    $cacheKey = linkCacheKey($user_id, $link_id);

                                    if (! Cache::has($cacheKey)) {
//                                $cachedLink = Cache::get($cacheKey);
//                                if ($cachedLink === $link) {
                                        // Dispatch the job for each link
                                        InitPaymentJob::dispatch(
                                            appointment_booking_id: $appointment_booking_id,
                                            user_id: $user_id,
                                            link: $link,
                                            card_data: $user->card?->toArray() ?? []
                                        );
//                                }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
