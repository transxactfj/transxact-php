<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;

/**
 * Present when you created the session with a `quote`: the USD or AUD price you sent, the FJD rate it was converted at (a decimal string), and the Unix ms time the rate was taken. `amount` is the FJD that is charged. Never shown to the Customer.
 */
class CheckoutSessionQuote extends JsonSerializableType
{
    /**
     * @var int $amount The price you sent, in cents of `currency`.
     */
    #[JsonProperty('amount')]
    public int $amount;

    /**
     * @var value-of<CheckoutSessionQuoteCurrency> $currency USD or AUD.
     */
    #[JsonProperty('currency')]
    public string $currency;

    /**
     * @var string $rate FJD per 1 unit of `currency`, a decimal string.
     */
    #[JsonProperty('rate')]
    public string $rate;

    /**
     * @var int $asOf Unix ms time the rate was taken.
     */
    #[JsonProperty('asOf')]
    public int $asOf;

    /**
     * @param array{
     *   amount: int,
     *   currency: value-of<CheckoutSessionQuoteCurrency>,
     *   rate: string,
     *   asOf: int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->amount = $values['amount'];
        $this->currency = $values['currency'];
        $this->rate = $values['rate'];
        $this->asOf = $values['asOf'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
