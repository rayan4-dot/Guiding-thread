<?php

namespace App\Repositories\Contracts;

interface AuthRepositoryInterface
{
    public function register(array $data);
    public function login(array $credentials);
    public function logout();
    public function forgotPassword(string $email);
    public function resetPassword(array $data);
    public function updatePassword($user, string $newPassword);
    public function deleteAccount($user);

}
