<?php

namespace Transxact\Types;

enum MerchantPayoutSchedule: string
{
    case Daily = "daily";
    case Weekly = "weekly";
    case Fortnightly = "fortnightly";
    case Monthly = "monthly";
    case Manual = "manual";
}
