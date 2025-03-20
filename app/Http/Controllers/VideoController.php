<?php

namespace App\Http\Controllers;

use App\Exceptions\YoutubeClientException;
use App\Http\Requests\ListVideosRequest;
use App\Services\YoutubeVideoService;
use App\Services\WikiService;

class VideoController extends Controller
{
    private YoutubeVideoService $videoService;
    private WikiService $wikiService;
    private int $itemsPerPage = 10;

    public function __construct(YoutubeVideoService $videoService, WikiService $wikiService)
    {
        $this->videoService = $videoService;
        $this->wikiService = $wikiService;
    }

    /**
     * @throws YoutubeClientException
     */
    public function index(ListVideosRequest $request): array
    {
        $offset = $this->determineOffset($request);

        $popularVideos = $this->videoService->getPopularByCountryCode(
            countryCode: $request->input('country'),
            amount: $this->itemsPerPage,
            offset: $offset,
        );

        $countryDescription = $this->wikiService->getCountryDescription(countryCode: $request->input('country'));

        return [
            "countryDescription" => $countryDescription,
            "videos" => [
                'total' => $popularVideos->totalCount,
                'data' => $popularVideos->toArray(),
            ],
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
