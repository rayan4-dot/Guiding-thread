<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Repositories\Contracts\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => Role::where('role', 'user')->first()->id,
        ]);

        // Session::put('user_id', $user->id);

        return $user;
    }

    public function login(array $credentials)
    {
        // $user = User::where('email', $credentials['email'])->first();

        // if (!$user || !Hash::check($credentials['password'], $user->password)) {
        //     return false;
        // }

        // // Session::put('user_id', $user->id);

        // return $user;


        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }
    
        Auth::login($user);  
        return $user;
    }

    public function logout()
    {
        Session::forget('user_id');
    }

    public function forgotPassword(string $email)
    {
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        
        // Mail::to($email)->send(new ResetPasswordMail($token));

        return $token;
    }

    public function resetPassword(array $data)
    {
        $reset = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->where('token', $data['token'])
            ->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return false;
        }

        User::where('email', $data['email'])->update([
            'password' => Hash::make($data['password'])
        ]);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return true;
    }

    public function updatePassword($user, string $newPassword)
{
    $user->update([
        'password' => Hash::make($newPassword),
    ]);

    return true;
}


public function deleteAccount($user)
{

    $user->posts()->delete(); 


    $result = $user->forceDelete();

    return $result;
}
}
