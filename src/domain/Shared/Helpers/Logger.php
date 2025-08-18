<?php

namespace Domain\Shared\Helpers;

use Illuminate\Support\Facades\Log;

class Logger
{
    public static function error(string $context, \Throwable $exception, array $additionalData = [])
    {
        try {
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
        } catch (\Throwable $e) {
            // Ignore logging errors when the logger cannot be resolved
        }
    }

     public static function emergency(string $context, \Throwable $exception, array $additionalData = [])
    {
        try {
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
        } catch (\Throwable $e) {
            // Ignore logging errors when the logger cannot be resolved
        }
    }

    public static function info(string $context, string $message, array $additionalData = [])
    {
        try {
            Log::info(self::formatLog($context, [
                'message' => $message,
                'data' => $additionalData,
            ]));
        } catch (\Throwable $e) {
            // Ignore logging errors when the logger cannot be resolved
        }
    }

    public static function warning(string $context, string $message, array $additionalData = [])
    {
        try {
            Log::warning(self::formatLog($context, [
                'message' => $message,
                'data' => $additionalData,
            ]));
        } catch (\Throwable $e) {
            // Ignore logging errors when the logger cannot be resolved
        }
    }

    public static function debug(string $context, string $message, array $additionalData = [])
    {
        try {
            Log::debug(self::formatLog($context, [
                'message' => $message,
                'data' => $additionalData,
            ]));
        } catch (\Throwable $e) {
            // Ignore logging errors when the logger cannot be resolved
        }
    }

    private static function formatLog(string $context, array $payload): array
    {
        $timestamp = function_exists('now') ? now()->toISOString() : date('c');
        $env = function_exists('config') ? config('app.env') : null;
        $app = function_exists('config') ? config('app.name') : null;
        $requestId = function_exists('request') ? (request()?->header('X-Request-ID') ?? uniqid()) : uniqid();

        return [
            'timestamp' => $timestamp,
            'context' => $context,
            'env' => $env,
            'app' => $app,
            'request_id' => $requestId,
            'payload' => $payload,
        ];
    }
}