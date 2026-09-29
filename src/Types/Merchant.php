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
     * @var value-of<MerchantPayoutSchedule> $payoutSchedule When Payouts are made, in UTC: `daily`; `weekly` on Mondays (the default); `fortnightly` on every other Monday; `monthly` on the 1st; or `manual`, where a Payout is made only when the Merchant asks for one from the dashboard. Set on the dashboard.
     */
    #[JsonProperty('payoutSchedule')]
    public string $payoutSchedule;

    /**
     * @param array{
     *   tier: value-of<MerchantTier>,
     *   mode: value-of<MerchantMode>,
     *   balance: int,
     *   payoutSchedule: value-of<MerchantPayoutSchedule>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->tier = $values['tier'];
        $this->mode = $values['mode'];
        $this->balance = $values['balance'];
        $this->payoutSchedule = $values['payoutSchedule'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
