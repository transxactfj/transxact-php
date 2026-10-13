<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;
use Transxact\Core\Types\ArrayType;

class InvoiceList extends JsonSerializableType
{
    /**
     * @var array<Invoice> $data This page of Invoices.
     */
    #[JsonProperty('data'), ArrayType([Invoice::class])]
    public array $data;

    /**
     * @var bool $hasMore True if older Invoices follow.
     */
    #[JsonProperty('hasMore')]
    public bool $hasMore;

    /**
     * @param array{
     *   data: array<Invoice>,
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
