# Reference
<details><summary><code>$client-&gt;postV1CheckoutSessions($request) -> ?CheckoutSession</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->postV1CheckoutSessions(
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

<details><summary><code>$client-&gt;getV1CheckoutSessionsId($id) -> ?CheckoutSession</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->getV1CheckoutSessionsId(
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

<details><summary><code>$client-&gt;postV1CheckoutSessionsIdCancel($id) -> ?CheckoutSession</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->postV1CheckoutSessionsIdCancel(
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

<details><summary><code>$client-&gt;getV1MerchantsMe() -> ?Merchant</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->getV1MerchantsMe();
```
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;getV1Payouts($request) -> ?GetV1PayoutsResponse</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->getV1Payouts(
    new GetV1PayoutsRequest([
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

<details><summary><code>$client-&gt;getV1PayoutsId($id) -> ?Payout</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->getV1PayoutsId(
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

