# Installation
- `composer install`
- `./vendor/bin/sail up -d`
- `cp .env.example .env`
- `php artisan key:generate`
- Fill .env.example's `YOUTUBE_APIKEY`

# Testing
- Query `http://localhost/api/videos`
- You may add the following query parameters
  - (integer, optional) `offset`
  - (integer, optional) `page`
  - (string, required) `country` Must be one of the following `gb`, `nl`, `de`, `fr`, `es`, `it` or `gr`
- If both offset and page are added, offset will take preference
- Results are given in chunks of 10

# Caching
- All popular videos for requested country will be queried from YouTube API and stored in cache for 10 minutes
- Uncached requests take between 400ms and 600ms, depending on total amount of popular videos in YouTube API


# Improvements
- Edit validation to be case-insensitive when validating the request's country codes
- Use a VideoResource to encapsulate logic about video properties to expose in the api
- Cache warm up with command scheduling to help prevent users in the unlikely scenario they get a cache miss
- Queries to Wiki API for country descriptions rely on hardcoded list of country titles. This should be updated so that Wiki country titles are not hardcoded but automatically associated from the ISO country codes
- Create a constant for redis keys to help with maintainability and prevent risks 
- Add message from guzzle exceptions into our custom exceptions to provide further context
