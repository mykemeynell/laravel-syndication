<?php

namespace LaravelSyndication\Feeds\Concerns;

use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait UsesCache
{
    /**
     * Gets the cache key of an item.
     *
     * @return string
     */
    public function cacheKey(): string
    {
        return sprintf("%s.%s", $this->identifier, $this->requestedFeedType);
    }

    /**
     * Test if the feed is cached.
     *
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function isCached(): bool
    {
        return $this->cache()->has($this->cacheKey()) &&
            $this->cache()->has($this->cacheKey() . "_timestamp");
    }

    /**
     * Get the cached timestamp key.
     *
     * @return Carbon
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function lastCachedAt(): Carbon
    {
        return $this->cache()->get($this->cacheKey() . "_timestamp", now());
    }

    /**
     * Get this feeds items from the cache.
     *
     * @return Collection
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getDataFromCache(): Collection
    {
        return collect($this->cache()->get($this->cacheKey(), []));
    }

    /**
     * Save the content data to the cache.
     *
     * @return false|mixed
     */
    public function saveToCache(): mixed
    {
        $cacheKey = $this->cacheKey();
        $globalCacheFeeds = config('syndication.cache_feeds', false);
        $globalCacheTtl = config('syndication.cache_ttl', 1440);

        $cachingConfig = collect(
            config('syndication.caching', [])
        );

        $cacheThisFeed = ($cachingConfig->has($cacheKey) && $cachingConfig->get($cacheKey) !== false && $cachingConfig->get($cacheKey) > 0)
            || $globalCacheFeeds;

        $cacheTtl = $cachingConfig->has($cacheKey) && $cachingConfig->get($cacheKey) !== false && $cachingConfig->get($cacheKey) > 0
            ? $cachingConfig->get($cacheKey) : $globalCacheTtl;

        if($cacheThisFeed) {
            $now = now();
            $expires = $now->addMinutes($cacheTtl * 60);

            return
                $this->cache()->remember($cacheKey, $expires, fn () => $this->getData()) &&
                $this->cache()->remember($cacheKey . "_timestamp", $expires, fn () => $now);
        }

        return false;
    }

    /**
     * Get the cache.
     *
     * @return Repository
     */
    private function cache(): Repository
    {
        $store = config('syndication.cache_store');

        return Cache::store($store);
    }
}
