<?php

namespace Transxact\Merchants;

use Psr\Http\Client\ClientInterface;
use Transxact\Core\Client\RawClient;
use Transxact\Types\Merchant;
use Transxact\Exceptions\TransxactException;
use Transxact\Exceptions\TransxactApiException;
use Transxact\Core\Json\JsonApiRequest;
use Transxact\Environments;
use Transxact\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;

class MerchantsClient
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
     * Returns the Merchant that owns the API key: its tier, the key's mode, the balance not yet paid out in that mode, and the Payout schedule. A cheap way to check a key works and which mode it's in.
     *
     * Example:
     * ```php
     * $client->merchants->me();
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
    public function me(?array $options = null): ?Merchant
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Production->value,
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
}
