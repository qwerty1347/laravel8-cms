<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct() {
    }

    public function getUserWithSocialAccountRow(string $email, string $socialProviderId=""): ?User
    {
        return User::with(['socialAccounts'])
        ->where(['email' => $email])
        ->when(!empty($socialProviderId), function ($query) use ($socialProviderId) {
           $query->where(function ($query) use ($socialProviderId) {
                $query->whereHas('socialAccounts', function ($query) use ($socialProviderId) {
                    $query->where('provider_id', $socialProviderId);
                })->orDoesntHave('socialAccounts');
           });
        })
        // ->where(function ($query) use ($socialProviderId) {
        //     $query->whereHas('socialAccounts', function ($q) use ($socialProviderId) {
        //         $q->where('provider_id', $socialProviderId);
        //     })->orDoesntHave('socialAccounts');
        // })
        ->first();
    }

    public function firstOrCreate(array $where, array $data): User
    {
        return User::firstOrCreate($where, $data);
    }
}
