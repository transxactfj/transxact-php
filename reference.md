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

**$amount:** `int` — Amount to charge, in FJD cents (minor units).
    
</dd>
</dl>

<dl>
<dd>

**$currency:** `string` — Always FJD in v1.
    
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

