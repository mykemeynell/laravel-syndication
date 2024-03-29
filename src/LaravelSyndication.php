<?php

namespace LaravelSyndication;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use LaravelSyndication\Feeds\AtomFeed;
use LaravelSyndication\Feeds\Feed;
use LaravelSyndication\Feeds\RssAndAtomFeed;
use LaravelSyndication\Feeds\RssFeed;

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
    public function meta(...$feeds): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
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

        if($feed instanceof AtomFeed || $feed instanceof RssAndAtomFeed) {
            $feeds->push([
                'type' => 'application/atom+xml',
                'title' => sprintf("%s Atom feed", ucfirst(Str::plural($feedTag))),
                'route' => route('syndication', $feedTag . '.atom')
            ]);
        }

        if($feed instanceof RssFeed || $feed instanceof RssAndAtomFeed) {
            $feeds->push([
                'type' => 'application/rss+xml',
                'title' => sprintf("%s RSS feed", ucfirst(Str::plural($feedTag))),
                'route' => route('syndication', $feedTag . '.rss'),
            ]);
        }

        return $feeds;
    }
}
