<?php

namespace App\Clients;

use App\Exceptions\YoutubeClientException;
use GuzzleHttp\Client;

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

    public function getPopular(string $countryCode): array
    {
        $url = sprintf('%s?%s',$this->baseUrl, http_build_query([
            'part' => 'snippet',
            'chart' =>  'mostPopular',
            'regionCode' => $countryCode,
            'key' => $this->apikey,
        ]));

        $response = $this->httpClient->get($url);

        if ($response->getStatusCode() !== 200) {
            throw new YoutubeClientException('Youtube API returned error status code ' . $response->getStatusCode());
        }

        $response = json_decode($response->getBody()->getContents(), true);

        if (is_null($response)) {
            throw new YoutubeClientException('Youtube API response can not be decoded or has too much nesting depth');
        }

        return $response['items'];
    }
}
