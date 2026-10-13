<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;
use Transxact\Core\Types\ArrayType;

class Invoice extends JsonSerializableType
{
    /**
     * @var int $id Invoice identifier.
     */
    #[JsonProperty('id')]
    public int $id;

    /**
     * @var ?string $number The Invoice number, such as `INV-0007`. Assigned when the Invoice is sent, so a draft has none; numbers are never reused.
     */
    #[JsonProperty('number')]
    public ?string $number;

    /**
     * @var value-of<InvoiceStatus> $status `draft` until sent and editable only from the dashboard. `sent`: the Customer can pay it. `paid`: paid in full. `void`: you voided it; it can no longer be paid.
     */
    #[JsonProperty('status')]
    public string $status;

    /**
     * @var bool $overdue A sent, unpaid Invoice past its due date (Pacific/Fiji). Derived, not a status; the Customer can still pay.
     */
    #[JsonProperty('overdue')]
    public bool $overdue;

    /**
     * @var value-of<InvoiceMode> $mode `test` for an Invoice made with an `sk_test_` key, `live` for an `sk_live_` key.
     */
    #[JsonProperty('mode')]
    public string $mode;

    /**
     * @var string $customerName Who you billed.
     */
    #[JsonProperty('customerName')]
    public string $customerName;

    /**
     * @var ?string $customerEmail The Customer's email, if you gave one.
     */
    #[JsonProperty('customerEmail')]
    public ?string $customerEmail;

    /**
     * @var array<InvoiceItem> $items The line items, excluding tax.
     */
    #[JsonProperty('items'), ArrayType([InvoiceItem::class])]
    public array $items;

    /**
     * @var ?string $taxLabel Label of the tax line; null if none.
     */
    #[JsonProperty('taxLabel')]
    public ?string $taxLabel;

    /**
     * @var float $taxPercent 0 when there is no tax line.
     */
    #[JsonProperty('taxPercent')]
    public float $taxPercent;

    /**
     * @var ?string $vatNumber Your VAT number, if you gave one.
     */
    #[JsonProperty('vatNumber')]
    public ?string $vatNumber;

    /**
     * @var string $dueDate `YYYY-MM-DD`, Pacific/Fiji.
     */
    #[JsonProperty('dueDate')]
    public string $dueDate;

    /**
     * @var int $subtotal Sum of the line items, in FJD cents (minor units).
     */
    #[JsonProperty('subtotal')]
    public int $subtotal;

    /**
     * @var int $tax The tax line, in FJD cents (minor units).
     */
    #[JsonProperty('tax')]
    public int $tax;

    /**
     * @var int $total Amount due; under FJD 5,000.00, in FJD cents (minor units).
     */
    #[JsonProperty('total')]
    public int $total;

    /**
     * @var string $url The hosted page the Customer opens and pays on. Works once the Invoice is sent.
     */
    #[JsonProperty('url')]
    public string $url;

    /**
     * @var ?string $pdfUrl Where to `GET` the PDF, with your secret key. Null on a draft.
     */
    #[JsonProperty('pdfUrl')]
    public ?string $pdfUrl;

    /**
     * @var int $createdAt Unix ms timestamp the draft was created.
     */
    #[JsonProperty('createdAt')]
    public int $createdAt;

    /**
     * @var ?int $sentAt Unix ms timestamp it was sent; null on a draft.
     */
    #[JsonProperty('sentAt')]
    public ?int $sentAt;

    /**
     * @var ?int $paidAt Unix ms timestamp it was paid; null until then.
     */
    #[JsonProperty('paidAt')]
    public ?int $paidAt;

    /**
     * @param array{
     *   id: int,
     *   status: value-of<InvoiceStatus>,
     *   overdue: bool,
     *   mode: value-of<InvoiceMode>,
     *   customerName: string,
     *   items: array<InvoiceItem>,
     *   taxPercent: float,
     *   dueDate: string,
     *   subtotal: int,
     *   tax: int,
     *   total: int,
     *   url: string,
     *   createdAt: int,
     *   number?: ?string,
     *   customerEmail?: ?string,
     *   taxLabel?: ?string,
     *   vatNumber?: ?string,
     *   pdfUrl?: ?string,
     *   sentAt?: ?int,
     *   paidAt?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'];
        $this->number = $values['number'] ?? null;
        $this->status = $values['status'];
        $this->overdue = $values['overdue'];
        $this->mode = $values['mode'];
        $this->customerName = $values['customerName'];
        $this->customerEmail = $values['customerEmail'] ?? null;
        $this->items = $values['items'];
        $this->taxLabel = $values['taxLabel'] ?? null;
        $this->taxPercent = $values['taxPercent'];
        $this->vatNumber = $values['vatNumber'] ?? null;
        $this->dueDate = $values['dueDate'];
        $this->subtotal = $values['subtotal'];
        $this->tax = $values['tax'];
        $this->total = $values['total'];
        $this->url = $values['url'];
        $this->pdfUrl = $values['pdfUrl'] ?? null;
        $this->createdAt = $values['createdAt'];
        $this->sentAt = $values['sentAt'] ?? null;
        $this->paidAt = $values['paidAt'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
