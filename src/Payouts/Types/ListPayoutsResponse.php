<?php

namespace Transxact\Payouts\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Types\Payout;
use Transxact\Core\Json\JsonProperty;
use Transxact\Core\Types\ArrayType;

class ListPayoutsResponse extends JsonSerializableType
{
    /**
     * @var array<Payout> $data This page of Payouts.
     */
    #[JsonProperty('data'), ArrayType([Payout::class])]
    public array $data;

    /**
     * @var bool $hasMore True if more Payouts follow; pass the last id as `starting_after` to get them.
     */
    #[JsonProperty('hasMore')]
    public bool $hasMore;

    /**
     * @param array{
     *   data: array<Payout>,
     *   hasMore: bool,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->data = $values['data'];
        $this->hasMore = $values['hasMore'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
