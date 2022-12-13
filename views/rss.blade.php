@php
/**
 * @var \LaravelSyndication\Feeds\Feed $feed
 * @var \LaravelSyndication\Feeds\FeedItem $item
 */
@endphp
{!! '<?xml version="1.0" encoding="' . $encoding . '" ?>' !!}

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        @if($feed->hasAtomLink())
        <atom:link href="{{ $feed->atomSelfLink() }}" rel="self" type="application/rss+xml" />
        @endif
        <title>{{ $feed->title() }}</title>
        <link>{{ $feed->url() }}</link>
        <description><![CDATA[{!! $feed->description() !!}]]></description>
        @if($feed->hasCategory())<category>{{ $feed->category() }}</category>@endif
        @if($feed->hasCopyright())<copyright>{{ $feed->copyright() }}</copyright>@endif
        @if(!empty($feed->ttl()))
            <ttl>{{ $feed->ttl() }}</ttl>
        @endif
        @if(!empty($feed->generator()))
            <generator>{{ $feed->generator() }}</generator>
        @endif
        @if($feed->hasCloud())
           {!! $feed->cloud() !!}
        @endif
        @if($feed->hasImage())
            {!! $feed->image() !!}
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
