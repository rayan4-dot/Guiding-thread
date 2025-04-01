<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthRepositoryInterface $auth)
    {
        $this->auth = $auth;
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $this->auth->register($request->only('name', 'email', 'password'));

        return redirect()->route('user.home')->with('success', 'Account created successfully.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            if (Auth::check()) {
                $user = Auth::user();
                // dd($user);  
            }
    

            if ($user->role && $user->role->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.home');
        }
    
        return back()->withErrors(['email' => 'These credentials do not match our records.']);
    }
    
    
    
    

    public function logout()
    {
        // $this->auth->logout();

        // session()->invalidate(); 
        // session()->regenerateToken(); 
    
        // return redirect()->route('login')->with('success', 'Logged out successfully.'); 
        


    Auth::logout(); 
    
    session()->invalidate();
    session()->regenerateToken();
    
    return redirect()->route('login')->with('success', 'Logged out successfully.');


         }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $this->auth->forgotPassword($request->email);

        return back()->with('success', 'Reset link sent!');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token'    => 'required',
        ]);

        $status = $this->auth->resetPassword($request->only('email', 'password', 'token'));

        if (!$status) {
            return back()->with('error', 'Invalid token or expired');
        }

        return redirect()->route('login')->with('success', 'Password reset successful.');
    }
}
