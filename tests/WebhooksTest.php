<?php

namespace Transxact\Tests;

use PHPUnit\Framework\TestCase;
use Transxact\Webhooks;

class WebhooksTest extends TestCase
{
    private const SECRET = 'whsec_test';
    private const PAYLOAD = '{"type":"checkout_session.succeeded"}';

    private static function sign(string $secret, int $timestampMs, string $payload): string
    {
        $hmac = hash_hmac('sha256', "{$timestampMs}.{$payload}", $secret);
        return "t={$timestampMs},v1={$hmac}";
    }

    public function testAcceptsAValidlySignedFreshPayload(): void
    {
        $header = self::sign(self::SECRET, (int) (microtime(true) * 1000), self::PAYLOAD);
        $this->assertTrue(Webhooks::verifySignature(self::PAYLOAD, $header, self::SECRET));
    }

    public function testRejectsAPayloadSignedWithTheWrongSecret(): void
    {
        $header = self::sign('wrong-secret', (int) (microtime(true) * 1000), self::PAYLOAD);
        $this->assertFalse(Webhooks::verifySignature(self::PAYLOAD, $header, self::SECRET));
    }

    public function testRejectsASignatureOlderThanTheToleranceWindow(): void
    {
        $staleTimestamp = (int) (microtime(true) * 1000) - 10 * 60 * 1000;
        $header = self::sign(self::SECRET, $staleTimestamp, self::PAYLOAD);
        $this->assertFalse(Webhooks::verifySignature(self::PAYLOAD, $header, self::SECRET));
    }

    public function testRejectsAMalformedHeader(): void
    {
        $this->assertFalse(Webhooks::verifySignature(self::PAYLOAD, 'not-a-valid-header', self::SECRET));
    }
}
