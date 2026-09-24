<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;

class Payout extends JsonSerializableType
{
    /**
     * @var string $id Payout identifier.
     */
    #[JsonProperty('id')]
    public string $id;

    /**
     * @var int $amount Amount paid out, in FJD cents (minor units).
     */
    #[JsonProperty('amount')]
    public int $amount;

    /**
     * @var value-of<PayoutRail> $rail Business -> bank_transfer, Individual -> wallet.
     */
    #[JsonProperty('rail')]
    public string $rail;

    /**
     * @var int $createdAt Unix ms timestamp of the settlement run that created this Payout.
     */
    #[JsonProperty('createdAt')]
    public int $createdAt;

    /**
     * @param array{
     *   id: string,
     *   amount: int,
     *   rail: value-of<PayoutRail>,
     *   createdAt: int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'];
        $this->amount = $values['amount'];
        $this->rail = $values['rail'];
        $this->createdAt = $values['createdAt'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
