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
     * Tests if the cache should be used.
     *
     * @return bool
     */
    public function shouldUseCache(): bool
    {
        $cachingConfig = collect(
            config('syndication.caching', [])
        );

        $globalCacheFeeds = config('syndication.cache_feeds', false);

        if($globalCacheFeeds && !$cachingConfig->has($this->cacheKey())) {
            return true;
        }

        if(
            !$globalCacheFeeds &&
            $cachingConfig->has($this->cacheKey()) &&
            $cachingConfig->get($this->cacheKey()) !== false &&
            $cachingConfig->get($this->cacheKey()) > 0
        ) {
            return true;
        }

        return false;
    }

    /**
     * Gets the TTL value for this feeds cache.
     *
     * @return int
     */
    public function cacheTtl(): int
    {
        $cachingConfig = collect(
            config('syndication.caching', [])
        );

        $globalCacheTtl = config('syndication.cache_ttl', 1440);

        if($globalCacheTtl && !$cachingConfig->has($this->cacheKey())) {
            return $globalCacheTtl;
        }

        if(
            $cachingConfig->has($this->cacheKey()) &&
            $cachingConfig->get($this->cacheKey()) !== false
        ) {
            return -1;
        }

        return $cachingConfig->get($this->cacheKey());
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
     * @return bool
     */
    public function saveToCache(): bool
    {
        if($this->shouldUseCache()) {
            $now = now();
            $expires = $now->addMinutes($this->cacheTtl() * 60);

            return
                $this->cache()->remember($this->cacheKey(), $expires, fn () => $this->getData()) &&
                $this->cache()->remember($this->cacheKey() . "_timestamp", $expires, fn () => $now);
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
