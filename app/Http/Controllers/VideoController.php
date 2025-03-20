<?php

namespace App\Http\Controllers;

use App\DTOs\Video;
use App\Http\Requests\ListVideosRequest;
use App\Services\VideoService;
use App\Services\WikiService;

class VideoController extends Controller
{
    private VideoService $videoService;
    private WikiService $wikiService;
    private int $itemsPerPage = 10;

    public function __construct(VideoService $videoService, WikiService $wikiService)
    {
        $this->videoService = $videoService;
        $this->wikiService = $wikiService;
    }

    public function index(ListVideosRequest $request): array
    {
        $offset = $this->determineOffset($request);

        $popularVideos = $this->videoService->getPopularByCountryCode(
            countryCode: $request->input('country'),
            amount: $this->itemsPerPage,
            offset: $offset,
        );

        $countryDescription = $this->wikiService->getCountryDescription();

        return [
            "countryDescription" => $countryDescription,
            "videos" => $popularVideos->toArray(),
        ];
    }

    private function determineOffset(ListVideosRequest $request): int
    {
        if ($request->has('offset')) {
            return $request->input('offset');
        }

        if ($request->has('page')) {
            return $request->input('page') * $this->itemsPerPage;
        }

        return 0;
    }
}
