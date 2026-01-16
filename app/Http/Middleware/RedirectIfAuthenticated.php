<?php


namespace App\Http\Middleware;

// use App\Providers\RouteServiceProvider; // <-- REMOVED THIS LINE
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // Custom redirect logic
                if ($guard === 'admin') {
                    // If an admin is logged in, redirect to admin dashboard
                    return redirect(route('admin.import.index'));
                }

                if ($guard === 'student') {
                    // If a student is logged in, redirect to student dashboard
                    return redirect(route('filament.student.pages.course-registration'));
                }

                // Default fallback - REPLACED 'RouteServiceProvider::HOME'
                return redirect('/home');
            }
        }

        return $next($request);
    }
}


