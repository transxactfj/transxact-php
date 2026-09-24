<?php

namespace Transxact\Types;

enum PayoutRail: string
{
    case BankTransfer = "bank_transfer";
    case Wallet = "wallet";
}
