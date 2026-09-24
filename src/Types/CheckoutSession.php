<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;

class CheckoutSession extends JsonSerializableType
{
    /**
     * @var string $id Checkout Session identifier.
     */
    #[JsonProperty('id')]
    public string $id;

    /**
     * @var value-of<CheckoutSessionStatus> $status Current status of the Checkout Session.
     */
    #[JsonProperty('status')]
    public string $status;

    /**
     * @var int $amount Amount to charge, in FJD cents (minor units).
     */
    #[JsonProperty('amount')]
    public int $amount;

    /**
     * @var value-of<CheckoutSessionCurrency> $currency Always FJD in v1.
     */
    #[JsonProperty('currency')]
    public string $currency;

    /**
     * @var string $hostedUrl Transxact-hosted URL to redirect the Customer to.
     */
    #[JsonProperty('hostedUrl')]
    public string $hostedUrl;

    /**
     * @param array{
     *   id: string,
     *   status: value-of<CheckoutSessionStatus>,
     *   amount: int,
     *   currency: value-of<CheckoutSessionCurrency>,
     *   hostedUrl: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'];
        $this->status = $values['status'];
        $this->amount = $values['amount'];
        $this->currency = $values['currency'];
        $this->hostedUrl = $values['hostedUrl'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
