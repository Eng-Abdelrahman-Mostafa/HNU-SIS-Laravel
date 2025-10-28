<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

// We EXTEND the base Controller to get the 'middleware' method
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Student Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating students for the application and
    | redirecting them to your registration screen.
    |
    */

    // We NO LONGER use the 'AuthenticatesUsers' trait

    /**
     * Where to redirect students after login.
     *
     * @var string
     */
    protected $redirectTo = '/registration';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // The 'middleware' method is available because we extend 'Controller'
        $this->middleware('guest:student')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt for a student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'student_id' => $request->student_id,
            'password' => $request->password
        ];

        $remember = $request->boolean('remember');

        // We attempt to log in using the 'student' guard
        // Auth::attempt will automatically use the getAuthPassword() method
        // in your Student model to check against the 'password_hash' column.
        if (!Auth::guard('student')->attempt($credentials, $remember)) {
            // If failed, throw a validation error
            throw ValidationException::withMessages([
                'student_id' => __('auth.failed'),
            ]);
        }

        // If successful, regenerate the session
        $request->session()->regenerate();

        // Redirect to the intended student registration page
        return redirect()->intended(route('registration.index'));
    }

    /**
     * Log the student out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log out from the 'student' guard
        Auth::guard('student')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirect to the student login form
        return redirect(route('login.form'));
    }
}


