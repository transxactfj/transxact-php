<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;
use Transxact\Core\Types\ArrayType;

class CheckoutSession extends JsonSerializableType
{
    /**
     * @var string $id Checkout Session identifier.
     */
    #[JsonProperty('id')]
    public string $id;

    /**
     * @var value-of<CheckoutSessionStatus> $status `pending` until the Customer pays or the session ends. `succeeded`: paid, safe to fulfil. `failed`: the payment was declined or didn't go through. `cancelled`: you cancelled it or it expired unpaid. Only `pending` ever changes, and each change sends the matching `checkout_session.*` webhook.
     */
    #[JsonProperty('status')]
    public string $status;

    /**
     * @var value-of<CheckoutSessionMode> $mode `test` for a session made with an `sk_test_` key, `live` for an `sk_live_` key. Webhook endpoints each hear one mode, and the payload says which it came from.
     */
    #[JsonProperty('mode')]
    public string $mode;

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
     * @var array<string, string> $metadata Your own key/value data from creation; empty object if none was set.
     */
    #[JsonProperty('metadata'), ArrayType(['string' => 'string'])]
    public array $metadata;

    /**
     * @var int $expiresAt Unix ms timestamp after which no payment can start; a still-pending session is then cancelled.
     */
    #[JsonProperty('expiresAt')]
    public int $expiresAt;

    /**
     * @param array{
     *   id: string,
     *   status: value-of<CheckoutSessionStatus>,
     *   mode: value-of<CheckoutSessionMode>,
     *   amount: int,
     *   currency: value-of<CheckoutSessionCurrency>,
     *   hostedUrl: string,
     *   metadata: array<string, string>,
     *   expiresAt: int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'];
        $this->status = $values['status'];
        $this->mode = $values['mode'];
        $this->amount = $values['amount'];
        $this->currency = $values['currency'];
        $this->hostedUrl = $values['hostedUrl'];
        $this->metadata = $values['metadata'];
        $this->expiresAt = $values['expiresAt'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
