<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use App\Models\Generalsetting;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */

    protected $except = [
        'payment/process',
        '/notify/paytm',
        '/notify/razorpay',
        '/notify/flutterwave',
        '/notify/instamojo',
        '/payment/process',
        '/notify/coingate'
    ];
}
