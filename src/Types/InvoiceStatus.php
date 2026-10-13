<?php

namespace Transxact\Types;

enum InvoiceStatus: string
{
    case Draft = "draft";
    case Sent = "sent";
    case Paid = "paid";
    case Void = "void";
}
