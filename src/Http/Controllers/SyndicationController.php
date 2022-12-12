<?php

namespace LaravelSyndication\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use LaravelSyndication\Contracts\Feeds\AtomFeed;
use LaravelSyndication\Contracts\Feeds\AtomFeedOnly;
use LaravelSyndication\Feeds\Feed;

class SyndicationController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @throws BindingResolutionException
     */
    public function generate(Request $request): Response
    {
        $feedName = self::normaliseFeedName($request->route('feed'));
        $feedType = self::normaliseFeedType($request->route('feed'));

        if(!$feedType || !$feed = config('syndication.feeds.' . $feedName)) {
            return abort(404);
        }

        /** @var Feed $feed */
        $feed = app()->make($feed);
        $feed->identifier($feedName);

        if (!$feed instanceof Feed) {
            throw new \Exception(
                sprintf("Feed type [%s] did not return an instance of [%s]. Does the feed object extend the abstract class?", get_class($feed), Feed::class)
            );
        }

        if(
            ($feedType === 'atom' &&
            (!$feed instanceof AtomFeed && !$feed instanceof AtomFeedOnly)) ||
            ($feedType === 'rss' && $feed instanceof AtomFeedOnly)
        ) {
            return abort(404);
        }

        $cacheKey = $this->cacheKey($feedName, $feedType);

        $feedData = $this->cache()->has($cacheKey)
            ? $this->cache()->get($cacheKey) : $this->getFeedData($feed);

        if(!$this->cache()->has($cacheKey)) {
            $this->saveToCache($cacheKey, $feedData);
        }

        return response()
            ->view('syndication::' . $feedType, $feedData)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Save the content data to the cache.
     *
     * @param string $cacheKey
     * @param        $feedData
     *
     * @return false|mixed
     */
    private function saveToCache(string $cacheKey, $feedData): mixed
    {
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
            return $this->cache()->remember($cacheKey, now()->addMinutes($cacheTtl * 60), fn () => $feedData);
        }

        return false;
    }

    /**
     * Get the cache.
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    private function cache(): \Illuminate\Contracts\Cache\Repository
    {
        $store = config('syndication.cache_store');

        return Cache::store($store);
    }

    /**
     * Gets the cache key.
     *
     * @param string $feedName
     * @param string $feedType
     * @param string $separator
     *
     * @return string
     */
    private function cacheKey(string $feedName, string $feedType): string
    {
        return sprintf("%s.%s", $feedName, $feedType);
    }

    /**
     * Normalise the feed name.
     *
     * @param string|null $name
     *
     * @return string|null
     */
    private static function normaliseFeedName(?string $name): ?string
    {
        return $name ? preg_replace('/(\.atom|\.rss)$/i', '', Str::lower($name))
            : null;
    }

    /**
     * Normalise the feed type.
     *
     * @param string|null $name
     *
     * @return string|null
     */
    private static function normaliseFeedType(?string $name): ?string
    {
        return match(Str::afterLast(Str::lower($name), '.')) {
            'atom' => 'atom',
            'rss' => 'rss',
            default => null
        };
    }

    /**
     * Get the feed data.
     *
     * @param Feed $feed
     *
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getFeedData(Feed $feed): array
    {
        return [
            'encoding' => config('syndication.encoding', 'utf-8'),
            'feed' => $feed,
            'items' => $feed->getItems()
        ];
    }
}
