<?php

namespace LaravelSyndication\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use LaravelSyndication\Feeds\AtomFeed;
use LaravelSyndication\Feeds\Feed;
use LaravelSyndication\Feeds\RssAndAtomFeed;

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

        if (!$feed instanceof Feed) {
            throw new \Exception(
                sprintf("Feed type [%s] did not return an instance of [%s]. Does the feed object extend the abstract class?", get_class($feed), Feed::class)
            );
        }

        $feed->identifier($feedName);
        $feed->requestedFeedType($feedType);

        $feed->setUp();

        if(
            ($feedType === 'atom' &&
            (!$feed instanceof RssAndAtomFeed && !$feed instanceof  AtomFeed)) ||

            $feedType === 'rss' && $feed instanceof AtomFeed
        ) {
            return abort(404);
        }

        $feedData = $feed->isCached() && $feed->shouldUseCache()
            ? $feed->getDataFromCache() : $feed->getData();


        if(!$feed->isCached() && $feed->shouldUseCache()) {
            $feed->saveToCache();
        }

        return response()
            ->view('syndication::' . $feedType, $feedData)
            ->header('Content-Type', 'text/xml');
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
}
