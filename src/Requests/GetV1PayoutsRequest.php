<?php

namespace Transxact\Requests;

use Transxact\Core\Json\JsonSerializableType;

class GetV1PayoutsRequest extends JsonSerializableType
{
    /**
     * @var ?string $startingAfter Cursor: return Payouts after this id.
     */
    public ?string $startingAfter;

    /**
     * @var ?string $limit Max rows to return (default 10, max 100).
     */
    public ?string $limit;

    /**
     * @param array{
     *   startingAfter?: ?string,
     *   limit?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->startingAfter = $values['startingAfter'] ?? null;
        $this->limit = $values['limit'] ?? null;
    }
}
