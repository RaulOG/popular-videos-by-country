# Installation
- `docker-compose up -d`
- `composer install`
- `cp .env.example .env`
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
