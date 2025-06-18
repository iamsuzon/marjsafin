<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $link_id, public string $link)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $url = config('app.waf_pay_url');

            Http::withOptions([
                'timeout' => 120, // seconds
            ])->post($url, [
                'link_id' => $this->link_id,
                'link' => $this->link,
            ]);
        } catch (ConnectionException $e) {
            // log or handle the timeout
            Log::error('WAF link pay failed: ' . $e->getMessage());
        }
    }
}
