<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;

class Merchant extends JsonSerializableType
{
    /**
     * @var value-of<MerchantTier> $tier Merchant's account tier.
     */
    #[JsonProperty('tier')]
    public string $tier;

    /**
     * @var value-of<MerchantMode> $mode Mode of the API key used for this request.
     */
    #[JsonProperty('mode')]
    public string $mode;

    /**
     * @var int $balance Earned balance not yet swept into a Payout, in FJD cents (minor units).
     */
    #[JsonProperty('balance')]
    public int $balance;

    /**
     * @param array{
     *   tier: value-of<MerchantTier>,
     *   mode: value-of<MerchantMode>,
     *   balance: int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->tier = $values['tier'];
        $this->mode = $values['mode'];
        $this->balance = $values['balance'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
