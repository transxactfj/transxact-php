<?php

namespace Transxact;

use Psr\Http\Client\ClientInterface;
use Transxact\Core\Client\RawClient;
use Transxact\Requests\CreateCheckoutSessionRequest;
use Transxact\Types\CheckoutSession;
use Transxact\Exceptions\TransxactException;
use Transxact\Exceptions\TransxactApiException;
use Transxact\Core\Json\JsonApiRequest;
use Transxact\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Transxact\Types\Merchant;
use Transxact\Requests\GetV1PayoutsRequest;
use Transxact\Types\GetV1PayoutsResponse;
use Transxact\Types\Payout;

class TransxactClient
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
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        ?array $options = null,
    ) {
        $defaultHeaders = [
            'X-Fern-Language' => 'PHP',
            'X-Fern-SDK-Name' => 'Transxact',
            'X-Fern-SDK-Version' => '0.2.1',
            'User-Agent' => 'transxact/transxact/0.2.1',
        ];

        $this->options = $options ?? [];

        $this->options['headers'] = array_merge(
            $defaultHeaders,
            $this->options['headers'] ?? [],
        );

        $this->client = new RawClient(
            options: $this->options,
        );
    }

    /**
     * Example:
     * ```php
     * $client->postV1CheckoutSessions(
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
    public function postV1CheckoutSessions(CreateCheckoutSessionRequest $request, ?array $options = null): ?CheckoutSession
    {
        $options = array_merge($this->options, $options ?? []);
        $headers = [];
        $headers['idempotency-key'] = $request->idempotencyKey;
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? '',
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
     * Example:
     * ```php
     * $client->getV1CheckoutSessionsId(
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
    public function getV1CheckoutSessionsId(string $id, ?array $options = null): ?CheckoutSession
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? '',
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
     * Example:
     * ```php
     * $client->getV1MerchantsMe();
     * ```
     *
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?Merchant
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function getV1MerchantsMe(?array $options = null): ?Merchant
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? '',
                    path: "v1/merchants/me",
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
                return Merchant::fromJson($json);
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
     * Example:
     * ```php
     * $client->getV1Payouts(
     *     new GetV1PayoutsRequest([
     *         'startingAfter' => 'po_3f9c2b1a',
     *         'limit' => '10',
     *     ]),
     * );
     * ```
     *
     * @param GetV1PayoutsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?GetV1PayoutsResponse
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function getV1Payouts(GetV1PayoutsRequest $request = new GetV1PayoutsRequest(), ?array $options = null): ?GetV1PayoutsResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->startingAfter != null) {
            $query['starting_after'] = $request->startingAfter;
        }
        if ($request->limit != null) {
            $query['limit'] = $request->limit;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? '',
                    path: "v1/payouts",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return GetV1PayoutsResponse::fromJson($json);
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
     * Example:
     * ```php
     * $client->getV1PayoutsId(
     *     'po_3f9c2b1a',
     * );
     * ```
     *
     * @param string $id Payout identifier.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?Payout
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function getV1PayoutsId(string $id, ?array $options = null): ?Payout
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? '',
                    path: "v1/payouts/{$id}",
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
                return Payout::fromJson($json);
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
