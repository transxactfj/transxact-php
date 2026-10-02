# Reference
## CheckoutSessions
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
        'amount' => 5000,
        'currency' => CreateCheckoutSessionRequestCurrency::Fjd->value,
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

**$amount:** `int` — Amount to charge, in FJD cents (minor units). Must be under FJD 5,000.00 (at most 499999), in Test mode and Live mode alike.
    
</dd>
</dl>

<dl>
<dd>

**$currency:** `string` — Always FJD in v1.
    
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

