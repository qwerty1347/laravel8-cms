<?php

namespace App\Repositories;

use App\Models\SocialAccount;

class SocialAccountRepository
{
    public function __construct()
    {
    }

    public function firstOrCreate(array $data
    ): SocialAccount
    {
        return SocialAccount::firstOrCreate($data);
    }
}
