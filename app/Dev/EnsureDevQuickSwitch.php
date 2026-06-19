<?php

namespace App\Dev;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * DEV-ONLY: Middleware penjaga route DevQuickSwitch.
 * File ini sengaja ditempatkan di app/Dev/ (terpisah dari kode produksi).
 */
class EnsureDevQuickSwitch
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('dev_quick_switch.enabled')) {
            abort(404);
        }

        return $next($request);
    }
}
