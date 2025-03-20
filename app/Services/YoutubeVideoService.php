<?php

namespace App\Services;

use App\Clients\YoutubeClient;
use App\Collections\VideoCollection;
use App\Exceptions\YoutubeClientException;
use Illuminate\Support\Facades\Cache;

class YoutubeVideoService
{
    private YoutubeClient $videoClient;

    public function __construct(YoutubeClient $videoClient)
    {
        $this->videoClient = $videoClient;
    }

    /**
     * @throws YoutubeClientException
     */
    public function getPopularByCountryCode(string $countryCode, int $amount, int $offset = 0): VideoCollection
    {
        /** @var VideoCollection $videos */
        $videos = Cache::get("youtube.videos.$countryCode");

        if (is_null($videos)) {
            $videos = $this->getVideos($countryCode);

            Cache::put(
                key: "youtube.videos.$countryCode",
                value: $videos,
                ttl: now()->addMinutes(10),
            );
        }

        $totalCount = $videos->count();
        $videos = $videos->slice($offset, $amount);
        $videos->totalCount = $totalCount;

        return $videos;
    }

    /**
     * @throws YoutubeClientException
     */
    public function getVideos(string $countryCode): VideoCollection
    {
        $response = $this->videoClient->getPopular($countryCode);
        $videos = new VideoCollection($response['items']);

        while (array_key_exists('nextPageToken', $response)) {
            $response = $this->videoClient->getPopular($countryCode, $response['nextPageToken']);
            $videos = $videos->merge($response['items']);
        }

        return $videos->map(function ($video) {
            return [
                'description' => $video['snippet']['description'],
                'thumbnail_medium_resolution' => $video['snippet']['thumbnails']['medium']['url'],
                'thumbnail_high_resolution' => $video['snippet']['thumbnails']['high']['url'],
            ];
        });
    }
}
