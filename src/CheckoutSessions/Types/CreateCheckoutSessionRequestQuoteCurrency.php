<?php

namespace Transxact\CheckoutSessions\Types;

enum CreateCheckoutSessionRequestQuoteCurrency: string
{
    case Usd = "USD";
    case Aud = "AUD";
}
