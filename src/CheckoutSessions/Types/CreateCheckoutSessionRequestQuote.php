<?php

namespace Transxact\CheckoutSessions\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;

/**
 * Price the session in USD or AUD instead of sending `amount`. `amount` is in cents of that currency. We convert it to FJD once, now, and lock the rate; the Customer sees and pays FJD only. The converted FJD amount must still be under FJD 5,000.00. Test mode only for now: a Live mode key gets `quote_unavailable_in_live_mode`. Only on Business accounts.
 */
class CreateCheckoutSessionRequestQuote extends JsonSerializableType
{
    /**
     * @var int $amount Price in cents of `currency`.
     */
    #[JsonProperty('amount')]
    public int $amount;

    /**
     * @var value-of<CreateCheckoutSessionRequestQuoteCurrency> $currency USD or AUD.
     */
    #[JsonProperty('currency')]
    public string $currency;

    /**
     * @param array{
     *   amount: int,
     *   currency: value-of<CreateCheckoutSessionRequestQuoteCurrency>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->amount = $values['amount'];
        $this->currency = $values['currency'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
