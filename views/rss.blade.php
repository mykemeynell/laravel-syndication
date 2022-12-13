@php
    /**
     * @var \LaravelSyndication\Feeds\RssFeed $feed
     * @var \LaravelSyndication\Feeds\FeedItem $item
     */
@endphp
{!! '<?xml version="1.0" encoding="' . $encoding . '" ?>' !!}

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        @if($feed->hasAtomFeed())
            <atom:link href="{{ $feed->getAtomFeed() }}" rel="self" type="application/rss+xml"/>
        @endif
        <title>{{ $feed->getTitle() }}</title>
        <link>{{ $feed->getUrl() }}</link>
        <description><![CDATA[{!! $feed->getDescription() !!}]]></description>
        @if($feed->hasCategory())
            <category>{{ $feed->getCategory() }}</category>
        @endif
        @if($feed->hasLanguage())
            <language>{{ $feed->getLanguage() }}</language>
        @endif
        @if($feed->hasCopyright())
            <copyright>{{ $feed->getCopyright() }}</copyright>
        @endif
        @if($feed->hasTtl())
            <ttl>{{ $feed->getTtl() }}</ttl>
        @endif
        @if($feed->hasGenerator())
            {!! $feed->getGenerator() !!}
        @endif
        @if($feed->hasPublished())
            <pubDate>{{ $feed->getPublished()->format('D, d M Y H:i:s O') }}</pubDate>
        @endif
        @if($feed->hasUpdated())
            <lastBuildDate>{{ $feed->getUpdated()->format('D, d M Y H:i:s O') }}</lastBuildDate>
        @endif
        @if($feed->hasCloud())
            {!! $feed->getCloud() !!}
        @endif
        @if($feed->hasImage())
            {!! $feed->getImage() !!}
        @endif
        @foreach($items as $item)
            <item>
                <title>{{ $item->getTitle() }}</title>
                <link>{{ $item->getUrl() }}</link>
                <guid>{{ $item->getUrl() }}</guid>
                <description><![CDATA[{!! $item->getDescription() !!}]]></description>
                @if($item->hasAuthor())
                    @if($item->getAuthor() instanceof \LaravelSyndication\Feeds\Structure\Atom\Person)
                        <author>{{ $item->getAuthor()->email }}</author>
                    @else
                        @php
                            $authors = $item->getAuthor();
                            $authors = $authors instanceof \Illuminate\Support\Collection ? $authors
                                : collect($authors);
                        @endphp
                        <author>{{ $authors->map(fn (\LaravelSyndication\Feeds\Structure\Atom\Person $author) => $author->email)->join(', ') }}</author>
                    @endif
                @endif
                @if($item->hasComments())
                    <comments>{{ $item->getComments() }}</comments>
                @endif
                @if($item->hasEnclosure())
                    {!! $item->getEnclosure() !!}
                @endif
                @if(!empty($item->hasPublished()))
                    <pubDate>{{ $item->getPublished()->format('D, d M Y H:i:s O') }}</pubDate>
                @endif
            </item>
        @endforeach
    </channel>
</rss>
