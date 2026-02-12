<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if maintenance mode is enabled in DB
        // We use a direct query or model to avoid overhead if possible, but Model is fine.
        // We can wrap in try-catch in case DB is not setup
        try {
            $maintenance = Setting::where('key', 'system_maintenance')->value('value');

            if ($maintenance == '1') {
                // 2. Allow specific excluded paths (like login, admin login)
                // If the user needs to login to access admin panel, we must allow login routes
                if ($request->is('login') || $request->is('register') || $request->is('admin/*') || $request->is('logout')) {
                    // Logic: Even if maintenance is on, we let people try to access these.
                    // But wait, if they access /login and login as 'user', they are still in maintenance.
                    // So we should let the request proceed?
                    // Better approach: Let Auth run.
                }

                // 3. Check if user is authenticated and is admin
                // Note: This middleware must run AFTER 'StartSession' and 'VerifyCsrfToken' usually to have Auth session.

                if (Auth::check() && Auth::user()->hasRole('admin')) {
                    return $next($request);
                }

                // If not admin, and strictly accessing a non-auth route or just general user
                // We typically exclude 'login' so admins can actually log in.
                if ($request->is('login') || $request->is('admin/login')) {
                    return $next($request);
                }

                // Otherwise, show maintenance page
                // We can use Laravel's default 503 or a custom view
                return response()->view('errors.503', [], 503);
            }

        } catch (\Exception $e) {
            // DB error or table doesn't exist? just continue
        }

        return $next($request);
    }
}
