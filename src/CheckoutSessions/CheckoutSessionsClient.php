<?php

namespace Transxact\CheckoutSessions;

use Psr\Http\Client\ClientInterface;
use Transxact\Core\Client\RawClient;
use Transxact\CheckoutSessions\Requests\CreateCheckoutSessionRequest;
use Transxact\Types\CheckoutSession;
use Transxact\Exceptions\TransxactException;
use Transxact\Exceptions\TransxactApiException;
use Transxact\Core\Json\JsonApiRequest;
use Transxact\Environments;
use Transxact\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;

class CheckoutSessionsClient
{
    /**
     * @var array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options @phpstan-ignore-next-line Property is used in endpoint methods via HttpEndpointGenerator
     */
    private array $options;

    /**
     * @var RawClient $client
     */
    private RawClient $client;

    /**
     * @param RawClient $client
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        RawClient $client,
        ?array $options = null,
    ) {
        $this->client = $client;
        $this->options = $options ?? [];
    }

    /**
     * Starts a payment: call this from your server, then redirect the Customer to the returned `hostedUrl`, which takes the payment with any Provider. Don't fulfil on the redirect; wait for the `checkout_session.succeeded` webhook or retrieve the session. The `Idempotency-Key` header is required: a retry with the same key replays the original session instead of creating a second one, so derive it from your order. An `sk_test_` key creates a Test mode session that simulates payment; an `sk_live_` key creates a Live mode session that moves real money.
     *
     * Example:
     * ```php
     * $client->checkoutSessions->create(
     *     new CreateCheckoutSessionRequest([
     *         'idempotencyKey' => 'a1b2c3d4-order-9912',
     *         'amount' => 5000,
     *         'currency' => CreateCheckoutSessionRequestCurrency::Fjd->value,
     *     ]),
     * );
     * ```
     *
     * @param CreateCheckoutSessionRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?CheckoutSession
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function create(CreateCheckoutSessionRequest $request, ?array $options = null): ?CheckoutSession
    {
        $options = array_merge($this->options, $options ?? []);
        $headers = [];
        $headers['idempotency-key'] = $request->idempotencyKey;
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
                    path: "v1/checkout-sessions",
                    method: HttpMethod::POST,
                    headers: $headers,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return CheckoutSession::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new TransxactException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new TransxactException(message: $e->getMessage(), previous: $e);
        }
        throw new TransxactApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Returns a Checkout Session's current state. Use it to confirm the outcome when the Customer lands on your `successUrl` with `session_id`, or to check a webhook you missed. Only sessions created with a key of the same Merchant and mode are visible; anything else is 404.
     *
     * Example:
     * ```php
     * $client->checkoutSessions->retrieve(
     *     'cs_3f9c2b1a',
     * );
     * ```
     *
     * @param string $id Checkout Session identifier.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?CheckoutSession
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function retrieve(string $id, ?array $options = null): ?CheckoutSession
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
                    path: "v1/checkout-sessions/{$id}",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return CheckoutSession::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new TransxactException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new TransxactException(message: $e->getMessage(), previous: $e);
        }
        throw new TransxactApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Withdraws a `pending` Checkout Session so it can't be paid, e.g. when the order is abandoned or changed. Fails with `payment_in_progress` once the Customer has started paying; wait for the outcome webhook instead. Unpaid sessions also cancel on their own after `expiresAt`, unless the Customer has started paying.
     *
     * Example:
     * ```php
     * $client->checkoutSessions->cancel(
     *     'cs_3f9c2b1a',
     * );
     * ```
     *
     * @param string $id Checkout Session identifier.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?CheckoutSession
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function cancel(string $id, ?array $options = null): ?CheckoutSession
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
                    path: "v1/checkout-sessions/{$id}/cancel",
                    method: HttpMethod::POST,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return CheckoutSession::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new TransxactException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new TransxactException(message: $e->getMessage(), previous: $e);
        }
        throw new TransxactApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }
}
