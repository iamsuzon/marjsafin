<?php

namespace App\Jobs;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InitPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $appointment_booking_id,
        public $appointment_booking_link_id,
        public $user_id,
        public $link,
        public $card_data
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $url = config('app.waf_payment_url');

            Http::withOptions([
                'timeout' => 120, // seconds
            ])->post($url, [
                'user_id' => $this->user_id,
                'appointment_booking_id' => $this->appointment_booking_id,
                'appointment_booking_link_id' => $this->appointment_booking_link_id,
                'link' => $this->link,
                'card_data' => $this->card_data,
            ]);
        } catch (ConnectionException $e) {
            // log or handle the timeout
            Log::error('WAF payment request failed: ' . $e->getMessage());
        }
    }
}
