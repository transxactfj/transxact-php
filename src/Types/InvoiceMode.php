<?php

namespace Transxact\Types;

enum InvoiceMode: string
{
    case Test = "test";
    case Live = "live";
}
