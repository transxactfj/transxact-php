<?php

namespace Transxact;

/**
 * Not generated from the OpenAPI spec — HMAC signature verification isn't
 * something an OpenAPI spec describes (ADR-0010). Scheme: header shaped
 * `t=<ms-timestamp>,v1=<hex-hmac>`, HMAC-SHA256 over `${timestamp}.${payload}`,
 * constant-time compare (ADR-0008).
 */
class Webhooks
{
    /**
     * Verifies a Transxact webhook's `Transxact-Signature` header.
     *
     * @param string $rawPayload The exact raw request body bytes, not re-serialized JSON.
     * @param string $header The `Transxact-Signature` header value, shaped `t=...,v1=...`.
     * @param string $secret Your webhook signing secret, from the dashboard's webhook settings.
     * @param int $toleranceMs How old a signed timestamp may be, in milliseconds. Defaults to 5 minutes.
     */
    public static function verifySignature(
        string $rawPayload,
        string $header,
        string $secret,
        int $toleranceMs = 5 * 60 * 1000,
    ): bool {
        $parts = [];
        foreach (explode(',', $header) as $part) {
            [$key, $value] = array_pad(explode('=', $part, 2), 2, null);
            if ($key !== null && $value !== null) {
                $parts[$key] = $value;
            }
        }

        $timestamp = $parts['t'] ?? null;
        $signature = $parts['v1'] ?? null;
        if ($timestamp === null || $signature === null) {
            return false;
        }

        $nowMs = (int) (microtime(true) * 1000);
        if (abs($nowMs - (int) $timestamp) > $toleranceMs) {
            return false;
        }

        $expected = hash_hmac('sha256', "{$timestamp}.{$rawPayload}", $secret);

        return hash_equals($expected, $signature);
    }
}
