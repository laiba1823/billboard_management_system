<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
{
    if ($request->expectsJson()) {
        return null;
    }

    // 👉 Vendor routes
    if ($request->is('vendors/*')) {
        return url('/vendors/login');
    }

    // 👉 Buyer routes
    if ($request->is('buyers/*')) {
        return url('/buyers/login');
    }

    // 👉 Default fallback
    return url('/');
}
}
