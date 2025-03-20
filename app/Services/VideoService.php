<?php

namespace App\Services;

use App\Clients\YoutubeClient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class VideoService
{
    private YoutubeClient $videoClient;

    public function __construct(YoutubeClient $videoClient)
    {
        $this->videoClient = $videoClient;
    }

    public function getPopularByCountryCode(string $countryCode, int $amount, int $offset = 0): Collection
    {
        $videos = Cache::get("popular.$countryCode");

        if (!is_null($videos)) {
            return $videos;
        }

        $videos = collect($this->videoClient->getPopular($countryCode))->map(function ($video) {
            return [
                'description' => $video['snippet']['description'],
                'thumbnail_medium_resolution' => $video['snippet']['thumbnails']['medium']['url'],
                'thumbnail_high_resolution' => $video['snippet']['thumbnails']['high']['url'],
            ];
        });

        Cache::put(
            key: "popular.$countryCode",
            value: $videos,
            ttl: now()->addMinutes(10),
        );

        return $videos;
    }
}
