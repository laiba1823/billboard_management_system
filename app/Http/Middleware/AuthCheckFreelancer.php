<?php

namespace App\Http\Middleware;

use App\Models\Freelancer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCheckFreelancer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!(session()->has("LoggedFreelancer"))) {
            return redirect(route("freelancers.loginForm"))->with("fail", "Login first of all");
        }

        // Passing down users data for the template
        $id = session("LoggedFreelancer");
        $request->freelancer = Freelancer::where("id", "=", $id)->first();

        return $next($request);
    }
}