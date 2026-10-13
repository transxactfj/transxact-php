<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;
use Transxact\Core\Types\ArrayType;

class CheckoutSessionList extends JsonSerializableType
{
    /**
     * @var array<CheckoutSession> $data This page of Checkout Sessions, newest first.
     */
    #[JsonProperty('data'), ArrayType([CheckoutSession::class])]
    public array $data;

    /**
     * @var ?string $nextCursor Pass as `cursor` to get the next page; null on the last page.
     */
    #[JsonProperty('nextCursor')]
    public ?string $nextCursor;

    /**
     * @param array{
     *   data: array<CheckoutSession>,
     *   nextCursor?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->data = $values['data'];
        $this->nextCursor = $values['nextCursor'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
