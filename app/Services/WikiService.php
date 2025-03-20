<?php

namespace App\Services;

use App\Clients\WikiClient;

class WikiService
{
    private WikiClient $wikiClient;

    public function __construct(WikiClient $wikiClient)
    {
        $this->wikiClient = $wikiClient;
    }

    public function getCountryDescription(string $countryCode): string
    {
        return $this->wikiClient->get(config("countries.$countryCode"));
    }
}
