<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

// We EXTEND the base Controller to get the 'middleware' method
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin users (using the 'users'
    | table) for the application's backend.
    |
    */

    // We NO LONGER use the 'AuthenticatesUsers' trait

    /**
     * Where to redirect admins after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/import';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // The 'middleware' method is available because we extend 'Controller'
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the application's admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // We attempt to log in using the 'admin' guard
        if (!Auth::guard('admin')->attempt($credentials, $remember)) {
            // If failed, throw a validation error
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // If successful, regenerate the session
        $request->session()->regenerate();

        // Redirect to the intended admin page
        return redirect()->intended(route('admin.import.index'));
    }

    /**
     * Log the admin out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log out from the 'admin' guard
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirect to the admin login form
        return redirect(route('admin.login.form'));
    }
}


