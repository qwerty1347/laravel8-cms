<?php

namespace App\Constants;

class HttpCodeConstant
{
    public const SUCCESS = [
        self::OK,
    ];

    public const OK = 200;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const INTERVAL_SERVER_ERROR = 500;
    public const UNKNOWN_ERROR = 520;
}
