<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;

class Payout extends JsonSerializableType
{
    /**
     * @var string $id Payout identifier.
     */
    #[JsonProperty('id')]
    public string $id;

    /**
     * @var int $amount Amount paid out, in FJD cents (minor units).
     */
    #[JsonProperty('amount')]
    public int $amount;

    /**
     * @var value-of<PayoutRail> $rail Business -> bank_transfer, Individual -> wallet.
     */
    #[JsonProperty('rail')]
    public string $rail;

    /**
     * @var value-of<PayoutStatus> $status pending until the money reaches your payout destination, then paid. failed means the transfer didn't go through; the amount returns to your balance and goes out in the next Payout. Test mode Payouts are always paid.
     */
    #[JsonProperty('status')]
    public string $status;

    /**
     * @var ?int $paidAt Unix ms timestamp the Payout was paid, or null.
     */
    #[JsonProperty('paidAt')]
    public ?int $paidAt;

    /**
     * @var ?string $failureReason Why the transfer failed, when status is failed.
     */
    #[JsonProperty('failureReason')]
    public ?string $failureReason;

    /**
     * @var int $createdAt Unix ms timestamp of the settlement run that created this Payout.
     */
    #[JsonProperty('createdAt')]
    public int $createdAt;

    /**
     * @param array{
     *   id: string,
     *   amount: int,
     *   rail: value-of<PayoutRail>,
     *   status: value-of<PayoutStatus>,
     *   createdAt: int,
     *   paidAt?: ?int,
     *   failureReason?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'];
        $this->amount = $values['amount'];
        $this->rail = $values['rail'];
        $this->status = $values['status'];
        $this->paidAt = $values['paidAt'] ?? null;
        $this->failureReason = $values['failureReason'] ?? null;
        $this->createdAt = $values['createdAt'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
