<?php

namespace Transxact\Invoices\Requests;

use Transxact\Core\Json\JsonSerializableType;

class ListInvoicesRequest extends JsonSerializableType
{
    /**
     * @var ?int $limit 1 to 100; defaults to 10.
     */
    public ?int $limit;

    /**
     * @var ?int $startingAfter An Invoice `id`; returns the Invoices older than it.
     */
    public ?int $startingAfter;

    /**
     * @param array{
     *   limit?: ?int,
     *   startingAfter?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->limit = $values['limit'] ?? null;
        $this->startingAfter = $values['startingAfter'] ?? null;
    }
}
