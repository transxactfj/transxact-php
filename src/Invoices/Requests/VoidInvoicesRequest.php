<?php

namespace Transxact\Invoices\Requests;

use Transxact\Core\Json\JsonSerializableType;

class VoidInvoicesRequest extends JsonSerializableType
{
    /**
     * @var string $idempotencyKey Client-generated key; a repeated key returns the original result instead of repeating the change.
     */
    public string $idempotencyKey;

    /**
     * @param array{
     *   idempotencyKey: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->idempotencyKey = $values['idempotencyKey'];
    }
}
