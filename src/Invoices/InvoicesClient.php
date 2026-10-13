<?php

namespace Transxact\Invoices;

use Psr\Http\Client\ClientInterface;
use Transxact\Core\Client\RawClient;
use Transxact\Invoices\Requests\ListInvoicesRequest;
use Transxact\Types\InvoiceList;
use Transxact\Exceptions\TransxactException;
use Transxact\Exceptions\TransxactApiException;
use Transxact\Core\Json\JsonApiRequest;
use Transxact\Environments;
use Transxact\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Transxact\Invoices\Requests\CreateInvoiceRequest;
use Transxact\Types\Invoice;
use Transxact\Invoices\Requests\SendInvoicesRequest;
use Transxact\Invoices\Requests\VoidInvoicesRequest;

class InvoicesClient
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
     * Lists your Invoices in the key's mode, newest first. To page, pass the last `id` you got as `starting_after` while `hasMore` is true.
     *
     * Example:
     * ```php
     * $client->invoices->list(
     *     new ListInvoicesRequest([
     *         'limit' => 10,
     *         'startingAfter' => 42,
     *     ]),
     * );
     * ```
     *
     * @param ListInvoicesRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?InvoiceList
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function list(ListInvoicesRequest $request = new ListInvoicesRequest(), ?array $options = null): ?InvoiceList
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->limit != null) {
            $query['limit'] = $request->limit;
        }
        if ($request->startingAfter != null) {
            $query['starting_after'] = $request->startingAfter;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
                    path: "v1/invoices",
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
                return InvoiceList::fromJson($json);
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
     * Creates a draft Invoice in the key's mode. It has no number and no working `url` until you send it. The total, with tax, must be under FJD 5,000.00 (at most 499999 cents), in Test mode and Live mode alike. The `Idempotency-Key` header is required: a retry with the same key returns the original draft instead of making a second.
     *
     * Example:
     * ```php
     * $client->invoices->create(
     *     new CreateInvoiceRequest([
     *         'idempotencyKey' => 'a1b2c3d4-order-9912',
     *         'customerName' => 'Mere Tuilagi',
     *         'dueDate' => '2026-11-30',
     *         'items' => [
     *             new InvoiceItem([
     *                 'description' => 'Tiling, kitchen',
     *                 'quantity' => 2,
     *                 'unitPrice' => 12000,
     *             ]),
     *         ],
     *     ]),
     * );
     * ```
     *
     * @param CreateInvoiceRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?Invoice
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function create(CreateInvoiceRequest $request, ?array $options = null): ?Invoice
    {
        $options = array_merge($this->options, $options ?? []);
        $headers = [];
        $headers['idempotency-key'] = $request->idempotencyKey;
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
                    path: "v1/invoices",
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
                return Invoice::fromJson($json);
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
     * Returns one Invoice and its current state. Invoices from the other mode or another Merchant are 404.
     *
     * Example:
     * ```php
     * $client->invoices->retrieve(
     *     42,
     * );
     * ```
     *
     * @param ?int $id Invoice identifier.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?Invoice
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function retrieve(?int $id = null, ?array $options = null): ?Invoice
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
                    path: "v1/invoices/{$id}",
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
                return Invoice::fromJson($json);
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
     * Releases a draft: assigns its Invoice number (from your prefix and counter in Settings) and makes its `url` work for the Customer. Transxact does not email it for you here; share the `url`, or email it from the dashboard. A sent Invoice can't be edited; void it and make another. The `Idempotency-Key` header is required: a retry returns the sent Invoice and uses no second number.
     *
     * Example:
     * ```php
     * $client->invoices->send(
     *     42,
     *     new SendInvoicesRequest([
     *         'idempotencyKey' => 'a1b2c3d4-order-9912',
     *     ]),
     * );
     * ```
     *
     * @param ?int $id Invoice identifier.
     * @param SendInvoicesRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?Invoice
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function send(SendInvoicesRequest $request, ?int $id = null, ?array $options = null): ?Invoice
    {
        $options = array_merge($this->options, $options ?? []);
        $headers = [];
        $headers['idempotency-key'] = $request->idempotencyKey;
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
                    path: "v1/invoices/{$id}/send",
                    method: HttpMethod::POST,
                    headers: $headers,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return Invoice::fromJson($json);
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
     * Cancels a sent, unpaid Invoice so it can no longer be paid. Its number is kept and never reused. Refused for a draft, a paid Invoice, or while a Customer is paying it right now. The `Idempotency-Key` header is required.
     *
     * Example:
     * ```php
     * $client->invoices->void(
     *     42,
     *     new VoidInvoicesRequest([
     *         'idempotencyKey' => 'a1b2c3d4-order-9912',
     *     ]),
     * );
     * ```
     *
     * @param ?int $id Invoice identifier.
     * @param VoidInvoicesRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?Invoice
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function void(VoidInvoicesRequest $request, ?int $id = null, ?array $options = null): ?Invoice
    {
        $options = array_merge($this->options, $options ?? []);
        $headers = [];
        $headers['idempotency-key'] = $request->idempotencyKey;
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
                    path: "v1/invoices/{$id}/void",
                    method: HttpMethod::POST,
                    headers: $headers,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return Invoice::fromJson($json);
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
     * The Invoice as a PDF, the same file the Customer's page and emails carry. This is the `pdfUrl` on the Invoice. Available once the Invoice is sent.
     *
     * Example:
     * ```php
     * $client->invoices->retrievePdf(
     *     ,
     * );
     * ```
     *
     * @param ?int $id Invoice identifier.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return string
     * @throws TransxactException
     * @throws TransxactApiException
     */
    public function retrievePdf(?int $id = null, ?array $options = null): string
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
                    path: "v1/invoices/{$id}/pdf",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                return $response->getBody()->getContents();
            }
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
