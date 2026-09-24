<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;
use Transxact\Core\Types\ArrayType;

class GetV1PayoutsResponse extends JsonSerializableType
{
    /**
     * @var array<Payout> $data
     */
    #[JsonProperty('data'), ArrayType([Payout::class])]
    public array $data;

    /**
     * @var bool $hasMore
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
