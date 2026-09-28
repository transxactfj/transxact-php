<?php

namespace Transxact\Types;

enum PayoutStatus: string
{
    case Pending = "pending";
    case Paid = "paid";
    case Failed = "failed";
}
