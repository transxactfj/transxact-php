<?php

namespace Transxact\Requests;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;
use Transxact\Types\CreateCheckoutSessionRequestCurrency;
use Transxact\Core\Types\ArrayType;

class CreateCheckoutSessionRequest extends JsonSerializableType
{
    /**
     * @var string $idempotencyKey Client-generated key; a repeated key returns the original session.
     */
    public string $idempotencyKey;

    /**
     * @var int $amount Amount to charge, in FJD cents (minor units). Must be under FJD 5,000.00 (at most 499999), in Test mode and Live mode alike.
     */
    #[JsonProperty('amount')]
    public int $amount;

    /**
     * @var value-of<CreateCheckoutSessionRequestCurrency> $currency Always FJD in v1.
     */
    #[JsonProperty('currency')]
    public string $currency;

    /**
     * @var ?string $successUrl Where the hosted checkout sends the Customer after the payment succeeds. HTTPS only. Transxact appends `session_id=<Checkout Session id>`; confirm the outcome with GET /v1/checkout-sessions/{id} rather than trusting the redirect.
     */
    #[JsonProperty('successUrl')]
    public ?string $successUrl;

    /**
     * @var ?string $cancelUrl Where the hosted checkout sends the Customer if they cancel or the payment doesn't go through. HTTPS only. Transxact appends `session_id=<Checkout Session id>`; confirm the outcome with GET /v1/checkout-sessions/{id} rather than trusting the redirect.
     */
    #[JsonProperty('cancelUrl')]
    public ?string $cancelUrl;

    /**
     * @var ?array<string, string> $metadata Up to 20 string key/value pairs (keys ≤40 chars, values ≤500) for matching the session to your own records. Returned on retrieve and in webhooks; never shown to the Customer. Don't put secrets or personal data here.
     */
    #[JsonProperty('metadata'), ArrayType(['string' => 'string'])]
    public ?array $metadata;

    /**
     * @var ?int $expiresAt Unix ms timestamp, 30 minutes to 24 hours from now, after which the session can't be paid and is cancelled. Defaults to 24 hours.
     */
    #[JsonProperty('expiresAt')]
    public ?int $expiresAt;

    /**
     * @param array{
     *   idempotencyKey: string,
     *   amount: int,
     *   currency: value-of<CreateCheckoutSessionRequestCurrency>,
     *   successUrl?: ?string,
     *   cancelUrl?: ?string,
     *   metadata?: ?array<string, string>,
     *   expiresAt?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->idempotencyKey = $values['idempotencyKey'];
        $this->amount = $values['amount'];
        $this->currency = $values['currency'];
        $this->successUrl = $values['successUrl'] ?? null;
        $this->cancelUrl = $values['cancelUrl'] ?? null;
        $this->metadata = $values['metadata'] ?? null;
        $this->expiresAt = $values['expiresAt'] ?? null;
    }
}
