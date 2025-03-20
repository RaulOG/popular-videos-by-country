<?php

namespace App\Services;

use App\Clients\WikiClient;
use App\Exceptions\WikiClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class WikiService
{
    private WikiClient $wikiClient;

    public function __construct(WikiClient $wikiClient)
    {
        $this->wikiClient = $wikiClient;
    }

    /**
     * @throws WikiClientException
     * @throws GuzzleException
     */
    public function getCountryDescription(string $countryCode): string
    {
        $countryDescription = Cache::get("wiki.country.$countryCode");

        if (is_null($countryDescription)) {
            $countryDescription = $this->wikiClient->get(config("countries.$countryCode"));
            Cache::put(
                key: "wiki.country.$countryCode",
                value: $countryDescription,
                ttl: now()->addHour(),
            );
        }

        return $countryDescription;
    }
}
