<?php

namespace Transxact;

use Transxact\CheckoutSessions\CheckoutSessionsClient;
use Transxact\Merchants\MerchantsClient;
use Transxact\Payouts\PayoutsClient;
use Psr\Http\Client\ClientInterface;
use Transxact\Core\Client\RawClient;

class TransxactClient
{
    /**
     * @var CheckoutSessionsClient $checkoutSessions
     */
    public CheckoutSessionsClient $checkoutSessions;

    /**
     * @var MerchantsClient $merchants
     */
    public MerchantsClient $merchants;

    /**
     * @var PayoutsClient $payouts
     */
    public PayoutsClient $payouts;

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
     * @param string $token The token to use for authentication.
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        string $token,
        ?array $options = null,
    ) {
        $defaultHeaders = [
            'Authorization' => "Bearer $token",
            'X-Fern-Language' => 'PHP',
            'X-Fern-SDK-Name' => 'Transxact',
            'X-Fern-SDK-Version' => '0.4.482',
            'User-Agent' => 'transxact/transxact/0.4.482',
        ];

        $this->options = $options ?? [];

        $this->options['headers'] = array_merge(
            $defaultHeaders,
            $this->options['headers'] ?? [],
        );

        $this->client = new RawClient(
            options: $this->options,
        );

        $this->checkoutSessions = new CheckoutSessionsClient($this->client, $this->options);
        $this->merchants = new MerchantsClient($this->client, $this->options);
        $this->payouts = new PayoutsClient($this->client, $this->options);
    }
}
