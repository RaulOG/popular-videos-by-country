<?php

namespace App\DTOs;

class Video
{
    private string $description;
    private string $thumbnail_normal_resolution;
    private string $thumbnail_high_resolution;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getThumbnailNormalResolution(): string
    {
        return $this->thumbnail_normal_resolution;
    }

    public function setThumbnailNormalResolution(string $thumbnail_normal_resolution): void
    {
        $this->thumbnail_normal_resolution = $thumbnail_normal_resolution;
    }

    public function getThumbnailHighResolution(): string
    {
        return $this->thumbnail_high_resolution;
    }

    public function setThumbnailHighResolution(string $thumbnail_high_resolution): void
    {
        $this->thumbnail_high_resolution = $thumbnail_high_resolution;
    }
}
