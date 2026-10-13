<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;

class InvoiceItem extends JsonSerializableType
{
    /**
     * @var string $description What it is, up to 200 characters.
     */
    #[JsonProperty('description')]
    public string $description;

    /**
     * @var int $quantity Whole units, 1 to 9999.
     */
    #[JsonProperty('quantity')]
    public int $quantity;

    /**
     * @var int $unitPrice Price of one unit, excluding tax, in FJD cents (minor units).
     */
    #[JsonProperty('unitPrice')]
    public int $unitPrice;

    /**
     * @param array{
     *   description: string,
     *   quantity: int,
     *   unitPrice: int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->description = $values['description'];
        $this->quantity = $values['quantity'];
        $this->unitPrice = $values['unitPrice'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
