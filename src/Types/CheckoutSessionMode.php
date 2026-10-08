<?php

namespace Transxact\Types;

enum CheckoutSessionMode: string
{
    case Test = "test";
    case Live = "live";
}
