<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/webhook/*',
        '/FortAPI/general/backToMerchant',
        '/FortAPI/redirectionResponse/resume3ds2AfterDDCUrl',
        'api/set-payment-links',
        'api/get-slip-info'
    ];
}
