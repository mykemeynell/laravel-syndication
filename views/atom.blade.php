@php
/**
 * @var \LaravelSyndication\Feeds\Feed|\LaravelSyndication\Contracts\Feeds\AtomFeed $feed
 * @var \LaravelSyndication\Feeds\FeedItem $item
 */
@endphp
{{--{!! '<?xml version="1.0" encoding="' . $encoding . '" ?>' !!}--}}

<feed xmlns="http://www.w3.org/2005/Atom">

    <title>{{ $feed->title() }}</title>
    <link rel="self" href="{{ $feed->atomSelfLink() }}"/>
    <updated>{{ $feed->updated()->format('Y-m-d\TH:i:s\Z') }}</updated>
    @if(!empty($feed->atomAuthor()))
        @php
            $authors = $feed->atomAuthor();
            $authors = $authors instanceof \LaravelSyndication\Feeds\Structure\Atom\Person
                ? collect([$authors]) : $authors;
            $authors = !$authors instanceof \Illuminate\Support\Collection
                ? collect($authors) : $authors;
        @endphp
        @foreach($authors as $author)
            <author>
                @if($author->hasName())<name>{{ $author->name }}</name>@endif
                @if($author->hasEmail())<email>{{ $author->email }}</email>@endif
                @if($author->hasUri())<uri>{{ $author->uri }}</uri>@endif
            </author>
        @endforeach
    @endif
    <id>{{ $feed->getAtomId() }}</id>
    @if(!empty($feed->generator()))
        <generator>
            {{ $feed->generator() }}
        </generator>
    @endif
    @if($feed->hasCopyright())
        <rights>{{ $feed->copyright() }}</rights>
    @endif
    @if($feed->hasCategory())
        <category term="{{ $feed->category() }}"/>
    @endif
    @if(!empty($feed->icon()))
        <icon>{{ $feed->icon() }}</icon>
    @endif
    @if(!empty($feed->logo()))
        <logo>{{ $feed->logo() }}</logo>
    @endif
    @if(!empty($feed->subtitle()))
        <subtitle>{{ $feed->subtitle() }}</subtitle>
    @endif
    @if($feed->hasContributors())
        @foreach($feed->contributors() as $contributor)
            <contributor>
                @if($contributor->hasName())<name>{{ $contributor->name }}</name>@endif
                @if($contributor->hasEmail())<email>{{ $contributor->email }}</email>@endif
                @if($contributor->hasUri())<uri>{{ $contributor->uri }}</uri>@endif
            </contributor>
        @endforeach
    @endif

    @foreach($items as $item)
    <entry>
        <id>{{ $item->getId() }}</id>
        <title>{{ $item->getTitle() }}</title>
        <link href="{{ $item->getUrl() }}"/>
        <updated>{{ $item->getUpdated()->format('Y-m-d\TH:i:s\Z') }}</updated>
        <summary>{{ $item->getDescription() }}</summary>
        @if($item->hasContent())
            {!! $item->getContent() !!}
        @endif
        @if($item->hasCopyright())
            <rights>{{ $item->getCopyright() }}</rights>
        @endif
        @if($item->hasPublished())
            <published>{{ $item->getPublished()->format('Y-m-d\TH:i:s\Z') }}</published>
        @endif
        @if($item->hasCategory())
            <category term="{{ $item->getCategory() }}" />
        @endif
        @if($item->hasAuthor())
            @php
                $authors = $item->getAuthor();
                $authors = $authors instanceof \LaravelSyndication\Feeds\Structure\Atom\Person
                    ? collect([$authors]) : $authors;
                $authors = !$authors instanceof \Illuminate\Support\Collection
                    ? collect($authors) : $authors;
            @endphp
            @foreach($authors as $author)
                <author>
                    @if($author->hasName())<name>{{ $author->name }}</name>@endif
                    @if($author->hasEmail())<email>{{ $author->email }}</email>@endif
                    @if($author->hasUri())<uri>{{ $author->uri }}</uri>@endif
                </author>
            @endforeach
        @endif

        @if($item->hasSource())
            <source>
                <id>{{ $item->getSource()->id }}</id>
                <title>{{ $item->getSource()->title }}</title>
                <updated>{{ $item->getSource()->updated->format('Y-m-d\TH:i:s\Z') }}</updated>
                @if($item->getSource()->hasAuthor())
                <author>
                    @if($item->getSource()->author->hasName())<name>{{ $item->getSource()->author->name }}</name>@endif
                    @if($item->getSource()->author->hasEmail())<email>{{ $item->getSource()->author->email }}</email>@endif
                    @if($item->getSource()->author->hasUri())<uri>{{ $item->getSource()->author->uri }}</uri>@endif
                </author>
                @endif
            </source>
        @endif
    </entry>
    @endforeach

</feed>
