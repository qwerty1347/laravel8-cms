<?php

namespace App\Repositories\Auth;

use App\Models\Auth\OauthToken;

class OauthTokenRepository
{
    public function __construct()
    {
    }

    public function firstWhere(array $where): ?OauthToken
    {
        return OauthToken::firstWhere($where);
    }

    public function updateOrCreate(array $where, array $data)
    {
        OauthToken::updateOrCreate($where, $data);
    }

    public function update(array $where, array $update)
    {
        OauthToken::where($where)->update($update);
    }
}
