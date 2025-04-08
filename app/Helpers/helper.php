<?php

use App\Constants\HttpCodeConstant;
use App\Logging\DailyLog;

if (!function_exists("logMessage")) {
    /**
     * 로그 메세지를 저장하는 함수
     *
     * @param   string  $channel  로그 메세지
     * @param   array   $level    로그 데이터
     * @param   string  $message  로그 레벨
     * @param   string  $logData  로그 저장 경로
     *
     * @return  void
     */
    function logMessage(string $channel, string $level, string $message, array $logData=[])
    {
        Log::channel($channel)->$level($message, $logData);
    }
}

if (!function_exists("ensureDirectoryPath")) {
    /**
     * 지정된 디렉토리가 존재하지 않으면 디렉토리를 생성하는 함수
     *
     * @param   string   $message
     *
     * @return  string
     */
    function ensureDirectoryPath(string $path): string
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
            chmod($path, 0707);
        }

        return $path;
    }
}

if (!function_exists("handleSuccessResult")) {
    /**
     * 성공 결과 처리하는 함수
     *
     * @param   array   $data
     * @param   int     $code
     *
     * @return  array
     */
    function handleSuccessResult(array $data, int $code=HttpCodeConstant::OK): array
    {
        return [
            "result" => true,
            "code"   => $code,
            "data"   => $data
        ];
    }
}

if (!function_exists("handleFailureResult")) {
    /**
     * 실패 결과 처리하는 함수
     *
     * @param   int     $code
     * @param   string  $message
     *
     * @return  array
     */
    function handleFailureResult(int $code, string $message): array
    {
        return [
            "result"  => false,
            "code"    => $code,
            "message" => $message
        ];
    }
}
