<?php

namespace App\Clients;

use App\Exceptions\WikiClientException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class WikiClient
{
    private string $baseUrl = 'https://en.wikipedia.org/w/api.php';
    private GuzzleClient $httpClient;

    public function __construct(GuzzleClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws GuzzleException
     * @throws WikiClientException
     */
    public function get(string $countryTitle): string
    {
        $url = sprintf('%s?%s', $this->baseUrl, http_build_query([
            'action' => 'query',
            'format' => 'json',
            'prop' => 'extracts',
            'exintro' => true,
            'explaintext' => true,
            'titles' => $countryTitle,
        ]));

        $response = $this->httpClient->get($url);

        if ($response->getStatusCode() !== 200) {
            throw new WikiClientException('Wiki returned error status code ' . $response->getStatusCode());
        }

        $response = json_decode($response->getBody()->getContents(), true);

        if (is_null($response)) {
            throw new WikiClientException('Wiki response can not be decoded or has too much nesting depth');
        }

        return reset($response['query']['pages'])['extract'];
    }
}
