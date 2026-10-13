# Reference
## CheckoutSessions
<details><summary><code>$client-&gt;checkoutSessions-&gt;list($request) -> ?CheckoutSessionList</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Lists your Checkout Sessions in the key's mode, newest first, so you can reconcile payments without having kept every id. Narrow it with `status` and a `from`/`to` created range. To page, pass the `nextCursor` you got as `cursor` until it is null.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->checkoutSessions->list(
    new ListCheckoutSessionsRequest([
        'limit' => '10',
        'cursor' => '1717086400000.cs_3f9c2b1a',
        'status' => 'succeeded',
        'from' => '1717000000000',
        'to' => '1717086400000',
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$limit:** `?string` — 1 to 100; defaults to 10.
    
</dd>
</dl>

<dl>
<dd>

**$cursor:** `?string` — The `nextCursor` from the previous page; returns the sessions older than it. Opaque: don't build or change one.
    
</dd>
</dl>

<dl>
<dd>

**$status:** `?string` — Only sessions with this status: one of `pending`, `succeeded`, `failed` or `cancelled`.
    
</dd>
</dl>

<dl>
<dd>

**$from:** `?string` — Only sessions created at or after this Unix ms timestamp.
    
</dd>
</dl>

<dl>
<dd>

**$to:** `?string` — Only sessions created at or before this Unix ms timestamp.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;checkoutSessions-&gt;create($request) -> ?CheckoutSession</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Starts a payment: call this from your server, then redirect the Customer to the returned `hostedUrl`, which takes the payment with any Provider. Don't fulfil on the redirect; wait for the `checkout_session.succeeded` webhook or retrieve the session. The `Idempotency-Key` header is required: a retry with the same key replays the original session instead of creating a second one, so derive it from your order. An `sk_test_` key creates a Test mode session that simulates payment; an `sk_live_` key creates a Live mode session that moves real money.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->checkoutSessions->create(
    new CreateCheckoutSessionRequest([
        'idempotencyKey' => 'a1b2c3d4-order-9912',
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$idempotencyKey:** `string` — Client-generated key; a repeated key returns the original session.
    
</dd>
</dl>

<dl>
<dd>

**$amount:** `?int` — Amount to charge, in FJD cents (minor units). Must be under FJD 5,000.00 (at most 499999), in Test mode and Live mode alike. Send this with `currency`, or send `quote` instead, never both.
    
</dd>
</dl>

<dl>
<dd>

**$currency:** `?string` — Always FJD in v1. Required with `amount`.
    
</dd>
</dl>

<dl>
<dd>

**$quote:** `?CreateCheckoutSessionRequestQuote` — Price the session in USD or AUD instead of sending `amount`. `amount` is in cents of that currency. We convert it to FJD once, now, and lock the rate; the Customer sees and pays FJD only. The converted FJD amount must still be under FJD 5,000.00. Test mode only for now: a Live mode key gets `quote_unavailable_in_live_mode`. Only on Business accounts.
    
</dd>
</dl>

<dl>
<dd>

**$successUrl:** `?string` — Where the hosted checkout sends the Customer after the payment succeeds. HTTPS only. Transxact appends `session_id=<Checkout Session id>`; confirm the outcome with GET /v1/checkout-sessions/{id} rather than trusting the redirect.
    
</dd>
</dl>

<dl>
<dd>

**$cancelUrl:** `?string` — Where the hosted checkout sends the Customer if they cancel or the payment doesn't go through. HTTPS only. Transxact appends `session_id=<Checkout Session id>`; confirm the outcome with GET /v1/checkout-sessions/{id} rather than trusting the redirect.
    
</dd>
</dl>

<dl>
<dd>

**$metadata:** `?array` — Up to 20 string key/value pairs (keys ≤40 chars, values ≤500) for matching the session to your own records. Returned on retrieve and in webhooks; never shown to the Customer. Don't put secrets or personal data here.
    
</dd>
</dl>

<dl>
<dd>

**$customerEmail:** `?string` — The Customer's email. In Live mode, once the payment succeeds, Transxact emails them a receipt from you (branded with your Display name) unless you've turned receipts off in Settings. We keep the address 30 days after the session ends and never return it. In Test mode it is checked and dropped; nothing is sent.
    
</dd>
</dl>

<dl>
<dd>

**$expiresAt:** `?int` — Unix ms timestamp, 30 minutes to 24 hours from now, after which the session can't be paid and is cancelled. Defaults to 24 hours.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;checkoutSessions-&gt;retrieve($id) -> ?CheckoutSession</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns a Checkout Session's current state. Use it to confirm the outcome when the Customer lands on your `successUrl` with `session_id`, or to check a webhook you missed. Only sessions created with a key of the same Merchant and mode are visible; anything else is 404.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->checkoutSessions->retrieve(
    'cs_3f9c2b1a',
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `string` — Checkout Session identifier.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;checkoutSessions-&gt;cancel($id) -> ?CheckoutSession</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Withdraws a `pending` Checkout Session so it can't be paid, e.g. when the order is abandoned or changed. Fails with `payment_in_progress` once the Customer has started paying; wait for the outcome webhook instead. Unpaid sessions also cancel on their own after `expiresAt`, unless the Customer has started paying.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->checkoutSessions->cancel(
    'cs_3f9c2b1a',
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `string` — Checkout Session identifier.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Invoices
<details><summary><code>$client-&gt;invoices-&gt;list($request) -> ?InvoiceList</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Lists your Invoices in the key's mode, newest first. To page, pass the last `id` you got as `starting_after` while `hasMore` is true.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->invoices->list(
    new ListInvoicesRequest([
        'limit' => 10,
        'startingAfter' => 42,
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$limit:** `?int` — 1 to 100; defaults to 10.
    
</dd>
</dl>

<dl>
<dd>

**$startingAfter:** `?int` — An Invoice `id`; returns the Invoices older than it.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;invoices-&gt;create($request) -> ?Invoice</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Creates a draft Invoice in the key's mode. It has no number and no working `url` until you send it. The total, with tax, must be under FJD 5,000.00 (at most 499999 cents), in Test mode and Live mode alike. The `Idempotency-Key` header is required: a retry with the same key returns the original draft instead of making a second.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->invoices->create(
    new CreateInvoiceRequest([
        'idempotencyKey' => 'a1b2c3d4-order-9912',
        'customerName' => 'Mere Tuilagi',
        'dueDate' => '2026-11-30',
        'items' => [
            new InvoiceItem([
                'description' => 'Tiling, kitchen',
                'quantity' => 2,
                'unitPrice' => 12000,
            ]),
        ],
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$idempotencyKey:** `string` — Client-generated key; a repeated key returns the original result instead of repeating the change.
    
</dd>
</dl>

<dl>
<dd>

**$customerName:** `string` — Who you are billing, up to 100 characters.
    
</dd>
</dl>

<dl>
<dd>

**$customerEmail:** `?string` — Optional until you email the Invoice from the dashboard.
    
</dd>
</dl>

<dl>
<dd>

**$dueDate:** `string` — `YYYY-MM-DD`, Pacific/Fiji.
    
</dd>
</dl>

<dl>
<dd>

**$items:** `array` — 1 to 50 line items, entered excluding tax.
    
</dd>
</dl>

<dl>
<dd>

**$taxLabel:** `?string` — Label for the one optional tax line, such as `VAT`. Needs `taxPercent`.
    
</dd>
</dl>

<dl>
<dd>

**$taxPercent:** `?float` — Tax as a percentage of the subtotal, up to 100 with 2 decimal places.
    
</dd>
</dl>

<dl>
<dd>

**$vatNumber:** `?string` — Your VAT number, if you are registered.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;invoices-&gt;retrieve($id) -> ?Invoice</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns one Invoice and its current state. Invoices from the other mode or another Merchant are 404.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->invoices->retrieve(
    42,
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `?int` — Invoice identifier.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;invoices-&gt;send($id, $request) -> ?Invoice</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Releases a draft: assigns its Invoice number (from your prefix and counter in Settings) and makes its `url` work for the Customer. Transxact does not email it for you here; share the `url`, or email it from the dashboard. A sent Invoice can't be edited; void it and make another. The `Idempotency-Key` header is required: a retry returns the sent Invoice and uses no second number.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->invoices->send(
    42,
    new SendInvoicesRequest([
        'idempotencyKey' => 'a1b2c3d4-order-9912',
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `?int` — Invoice identifier.
    
</dd>
</dl>

<dl>
<dd>

**$idempotencyKey:** `string` — Client-generated key; a repeated key returns the original result instead of repeating the change.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;invoices-&gt;void($id, $request) -> ?Invoice</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Cancels a sent, unpaid Invoice so it can no longer be paid. Its number is kept and never reused. Refused for a draft, a paid Invoice, or while a Customer is paying it right now. The `Idempotency-Key` header is required.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->invoices->void(
    42,
    new VoidInvoicesRequest([
        'idempotencyKey' => 'a1b2c3d4-order-9912',
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `?int` — Invoice identifier.
    
</dd>
</dl>

<dl>
<dd>

**$idempotencyKey:** `string` — Client-generated key; a repeated key returns the original result instead of repeating the change.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;invoices-&gt;retrievePdf($id) -> string</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

The Invoice as a PDF, the same file the Customer's page and emails carry. This is the `pdfUrl` on the Invoice. Available once the Invoice is sent.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->invoices->retrievePdf(
    ,
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `?int` — Invoice identifier.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Merchants
<details><summary><code>$client-&gt;merchants-&gt;me() -> ?Merchant</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns the Merchant that owns the API key: its tier, the key's mode, the balance not yet paid out in that mode, and the Payout schedule. A cheap way to check a key works and which mode it's in.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->merchants->me();
```
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Payouts
<details><summary><code>$client-&gt;payouts-&gt;list($request) -> ?ListPayoutsResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Lists the Merchant's Payouts in the key's mode, oldest id first. Payouts are made on the Merchant's Payout schedule, or when the Merchant asks from the dashboard on the manual schedule; they can't be created through the API. To page, pass the last id you got as `starting_after` while `hasMore` is true.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->payouts->list(
    new ListPayoutsRequest([
        'startingAfter' => 'po_3f9c2b1a',
        'limit' => '10',
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$startingAfter:** `?string` — Cursor: return Payouts after this id.
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?string` — Max rows to return (default 10, max 100).
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;payouts-&gt;retrieve($id) -> ?Payout</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns one Payout by id: its amount, rail and status, and why it failed if it did. Payouts from the other mode or another Merchant are 404.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->payouts->retrieve(
    'po_3f9c2b1a',
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `string` — Payout identifier.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

