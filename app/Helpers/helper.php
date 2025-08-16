<?php

use App\Constants\HttpCodeConstant;

if (!function_exists("logMessage")) {
    /**
     * 로그 메세지를 저장하는 함수
     *
     * @param   string  $channel  로그 채널
     * @param   array   $level    로그 레벨
     * @param   string  $message  로그 메세지
     * @param   string  $logData  로그 데이터
     *
     * @return  void
     */
    function logMessage(string $channel, string $level, string $message, array $logData=[])
    {
        $logger = (new \App\Logging\DailyLog())(['channel' => $channel]);
        $logger->$level($message, $logData);
    }
}

if (!function_exists("ensureDir")) {
    /**
     * 지정된 경로의 디렉토리가 존재하지 않으면 지정된 경로에 디렉토리를 생성하는 함수
     *
     * @param   string   $path
     *
     * @return  string
     */
    function ensureDir(string $path): string
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
            chmod($path, 0707);
        }

        return $path;
    }
}

if (!function_exists("successResponse")) {
    /**
     * 성공 결과 처리하는 함수
     *
     * @param   array   $data
     * @param   int     $code
     *
     * @return  array
     */
    function successResponse(array $data=[], int $code=HttpCodeConstant::OK): array
    {
        return [
            "result" => true,
            "code"   => $code,
            "data"   => $data
        ];
    }
}

if (!function_exists("errorResponse")) {
    /**
     * 실패 결과 처리하는 함수
     *
     * @param   int     $code
     * @param   string  $message
     *
     * @return  array
     */
    function errorResponse(int $code, string $message): array
    {
        return [
            "result"  => false,
            "code"    => $code,
            "message" => $message
        ];
    }
}
