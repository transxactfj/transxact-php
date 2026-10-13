<?php

namespace Transxact\CheckoutSessions\Requests;

use Transxact\Core\Json\JsonSerializableType;

class ListCheckoutSessionsRequest extends JsonSerializableType
{
    /**
     * @var ?string $limit 1 to 100; defaults to 10.
     */
    public ?string $limit;

    /**
     * @var ?string $cursor The `nextCursor` from the previous page; returns the sessions older than it. Opaque: don't build or change one.
     */
    public ?string $cursor;

    /**
     * @var ?string $status Only sessions with this status: one of `pending`, `succeeded`, `failed` or `cancelled`.
     */
    public ?string $status;

    /**
     * @var ?string $from Only sessions created at or after this Unix ms timestamp.
     */
    public ?string $from;

    /**
     * @var ?string $to Only sessions created at or before this Unix ms timestamp.
     */
    public ?string $to;

    /**
     * @param array{
     *   limit?: ?string,
     *   cursor?: ?string,
     *   status?: ?string,
     *   from?: ?string,
     *   to?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->limit = $values['limit'] ?? null;
        $this->cursor = $values['cursor'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->from = $values['from'] ?? null;
        $this->to = $values['to'] ?? null;
    }
}
