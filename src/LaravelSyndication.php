<?php

namespace LaravelSyndication;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use LaravelSyndication\Contracts\Feeds\AtomFeed;
use LaravelSyndication\Contracts\Feeds\AtomFeedOnly;
use LaravelSyndication\Feeds\Feed;

class LaravelSyndication
{
    /**
     * Get the URL to a feed.
     *
     * @throws Exception
     */
    public function url(string $feed): string
    {
        if(empty(config('syndication.feeds.' . $feed))) {
            throw new Exception("Unknown feed [%s]", $feed);
        }

        return route('syndication', $feed);
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function meta(...$feeds): string
    {
        if(empty($feeds)) {
            $feeds = collect(config('syndication.feeds'))->keys()->toArray();
        }

        $tags = collect([]);
        foreach ($feeds as $feedTag) {
            $feedClass = config('syndication.feeds.'.$feedTag);

            if(empty($feedClass)) {
                continue;
            }

            /** @var Feed $feed */
            $feed = app()->make($feedClass);
            $feed->identifier($feedTag);

            $tags = $tags->merge(
                $this->feedTagData($feed, $feedTag)
            );
        }

        return view('syndication::meta')
            ->with('feeds', $tags);
    }

    private function feedTagData(Feed $feed, $feedTag): Collection
    {
        $feeds = collect([]);

        if($feed instanceof AtomFeed) {
            $feeds->push([
                'type' => 'application/atom+xml',
                'title' => sprintf("%s Atom feed", ucfirst(Str::plural($feedTag))),
                'route' => route('syndication', $feedTag . '.atom')
            ]);
        }

        if(!$feed instanceof AtomFeedOnly) {
            $feeds->push([
                'type' => 'application/rss+xml',
                'title' => sprintf("%s RSS feed", ucfirst(Str::plural($feedTag))),
                'route' => route('syndication', $feedTag . '.rss'),
            ]);
        }

        return $feeds;
    }
}
