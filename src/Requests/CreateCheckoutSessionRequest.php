<?php

namespace Transxact\Requests;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;
use Transxact\Types\CreateCheckoutSessionRequestCurrency;

class CreateCheckoutSessionRequest extends JsonSerializableType
{
    /**
     * @var string $idempotencyKey Client-generated key; a repeated key returns the original session.
     */
    public string $idempotencyKey;

    /**
     * @var int $amount Amount to charge, in FJD cents (minor units).
     */
    #[JsonProperty('amount')]
    public int $amount;

    /**
     * @var value-of<CreateCheckoutSessionRequestCurrency> $currency Always FJD in v1.
     */
    #[JsonProperty('currency')]
    public string $currency;

    /**
     * @param array{
     *   idempotencyKey: string,
     *   amount: int,
     *   currency: value-of<CreateCheckoutSessionRequestCurrency>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->idempotencyKey = $values['idempotencyKey'];
        $this->amount = $values['amount'];
        $this->currency = $values['currency'];
    }
}
