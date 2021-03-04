<?php

namespace Rir\PaymentProviders\Uniteller\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ConfirmRequest
{
    private Client $client;

    protected string $baseUrl;

    protected array $parameters;

    /**
     * @param string $baseUrl
     * @param array $parameters
     */
    public function __construct(
        string $baseUrl,
        array  $parameters
    ) {
        $this->client = new Client();

        $this->baseUrl    = $baseUrl;
        $this->parameters = $parameters;
    }

    public function send()
    {
        try {
            $response = $this->client->post(
                $this->baseUrl . '/confirm', [
                    'headers' => [
                        'Accept' => 'text/csv',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => $this->parameters
                ]
            );
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
        }
    }
}
