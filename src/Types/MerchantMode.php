<?php

namespace Transxact\Types;

enum MerchantMode: string
{
    case Test = "test";
    case Live = "live";
}
