<?php

namespace App\Services;

use App\DTOs\Video;
use Illuminate\Support\Collection;

class VideoService
{
    public function getPopularByCountryCode(string $countryCode, int $amount, int $offset = 0): Collection
    {
        $videos = collect([]);

        $video = new Video();
        $video->setDescription('kittens');
        $video->setThumbnailNormalResolution('kittens.normal_resolution');
        $video->setThumbnailHighResolution('kittens.high_resolution');
        $videos->add($video);

        $video = new Video();
        $video->setDescription('dogs');
        $video->setThumbnailNormalResolution('dogs.normal_resolution');
        $video->setThumbnailHighResolution('dogs.high_resolution');
        $videos->add($video);

        return $videos;
    }
}
