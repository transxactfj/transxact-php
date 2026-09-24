<?php

namespace Transxact\Types;

enum CheckoutSessionStatus: string
{
    case Pending = "pending";
    case Succeeded = "succeeded";
    case Failed = "failed";
    case Cancelled = "cancelled";
}
