<?php

namespace Domain\Shared\Helpers;

use Illuminate\Support\Facades\Log;

class Logger
{
    public static function error(string $context, \Throwable $exception, array $additionalData = [])
    {
        Log::error(self::formatLog($context, [
            'error' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ],
            'data' => $additionalData,
        ]));
    }

     public static function emergency(string $context, \Throwable $exception, array $additionalData = [])
    {
        Log::error(self::formatLog($context, [
            'error' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ],
            'data' => $additionalData,
        ]));
    }

    public static function info(string $context, string $message, array $additionalData = [])
    {
        Log::info(self::formatLog($context, [
            'message' => $message,
            'data' => $additionalData,
        ]));
    }

    public static function warning(string $context, string $message, array $additionalData = [])
    {
        Log::warning(self::formatLog($context, [
            'message' => $message,
            'data' => $additionalData,
        ]));
    }

    public static function debug(string $context, string $message, array $additionalData = [])
    {
        Log::debug(self::formatLog($context, [
            'message' => $message,
            'data' => $additionalData,
        ]));
    }

    private static function formatLog(string $context, array $payload): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'context' => $context,
            'env' => config('app.env'),
            'app' => config('app.name'),
            'request_id' => request()->header('X-Request-ID') ?? uniqid(),
            'payload' => $payload,
        ];
    }
}