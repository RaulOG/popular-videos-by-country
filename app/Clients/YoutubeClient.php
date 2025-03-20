<?php

namespace App\Clients;

use App\Exceptions\YoutubeClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class YoutubeClient
{
    private Client $httpClient;
    private string $apikey;
    private string $baseUrl = 'https://youtube.googleapis.com/youtube/v3/videos';

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->apikey = env('YOUTUBE_APIKEY');
    }

    /**
     * @throws YoutubeClientException
     */
    public function getPopular(string $countryCode, string $pageToken = null): array
    {
        $url = sprintf('%s?%s',$this->baseUrl, http_build_query([
            'part' => 'snippet',
            'chart' =>  'mostPopular',
            'regionCode' => $countryCode,
            'key' => $this->apikey,
            'maxResults' => 50,
            ...(['pageToken' => $pageToken] ?: []),
        ]));

        try {
            $response = $this->httpClient->get($url);
        } catch (GuzzleException $e) {
            throw new YoutubeClientException($e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new YoutubeClientException('Youtube API returned error status code ' . $response->getStatusCode());
        }

        $response = json_decode($response->getBody()->getContents(), true);

        if (is_null($response)) {
            throw new YoutubeClientException('Youtube API response can not be decoded or has too much nesting depth');
        }

        return $response;
    }
}
