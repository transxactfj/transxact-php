<?php

namespace Transxact\Invoices\Requests;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;
use Transxact\Types\InvoiceItem;
use Transxact\Core\Types\ArrayType;

class CreateInvoiceRequest extends JsonSerializableType
{
    /**
     * @var string $idempotencyKey Client-generated key; a repeated key returns the original result instead of repeating the change.
     */
    public string $idempotencyKey;

    /**
     * @var string $customerName Who you are billing, up to 100 characters.
     */
    #[JsonProperty('customerName')]
    public string $customerName;

    /**
     * @var ?string $customerEmail Optional until you email the Invoice from the dashboard.
     */
    #[JsonProperty('customerEmail')]
    public ?string $customerEmail;

    /**
     * @var string $dueDate `YYYY-MM-DD`, Pacific/Fiji.
     */
    #[JsonProperty('dueDate')]
    public string $dueDate;

    /**
     * @var array<InvoiceItem> $items 1 to 50 line items, entered excluding tax.
     */
    #[JsonProperty('items'), ArrayType([InvoiceItem::class])]
    public array $items;

    /**
     * @var ?string $taxLabel Label for the one optional tax line, such as `VAT`. Needs `taxPercent`.
     */
    #[JsonProperty('taxLabel')]
    public ?string $taxLabel;

    /**
     * @var ?float $taxPercent Tax as a percentage of the subtotal, up to 100 with 2 decimal places.
     */
    #[JsonProperty('taxPercent')]
    public ?float $taxPercent;

    /**
     * @var ?string $vatNumber Your VAT number, if you are registered.
     */
    #[JsonProperty('vatNumber')]
    public ?string $vatNumber;

    /**
     * @param array{
     *   idempotencyKey: string,
     *   customerName: string,
     *   dueDate: string,
     *   items: array<InvoiceItem>,
     *   customerEmail?: ?string,
     *   taxLabel?: ?string,
     *   taxPercent?: ?float,
     *   vatNumber?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->idempotencyKey = $values['idempotencyKey'];
        $this->customerName = $values['customerName'];
        $this->customerEmail = $values['customerEmail'] ?? null;
        $this->dueDate = $values['dueDate'];
        $this->items = $values['items'];
        $this->taxLabel = $values['taxLabel'] ?? null;
        $this->taxPercent = $values['taxPercent'] ?? null;
        $this->vatNumber = $values['vatNumber'] ?? null;
    }
}
