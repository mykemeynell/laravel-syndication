@php
    /**
     * @var \LaravelSyndication\Feeds\AtomFeed $feed
     * @var \LaravelSyndication\Feeds\FeedItem $item
     */
@endphp
{!! '<?xml version="1.0" encoding="' . $encoding . '" ?>' !!}

<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{{ $feed->getTitle() }}</title>
    <link rel="self" href="{{ $feed->getAtomFeed() }}"/>
    @if($feed->hasUrl())
    <link rel="alternate" href="{{ $feed->getUrl() }}"/>
    @endif
    <updated>{{ $feed->getUpdated()->format('Y-m-d\TH:i:s\Z') }}</updated>
    <id>{{ $feed->getId() }}</id>
    @if($feed->hasAuthor())
        @php
            $authors = $feed->getAuthor();
            $authors = $authors instanceof \LaravelSyndication\Feeds\Structure\Atom\Person
                ? collect([$authors]) : $authors;
            $authors = !$authors instanceof \Illuminate\Support\Collection
                ? collect($authors) : $authors;
        @endphp
        @foreach($authors as $author)
            <author>
                @if($author->hasName())
                    <name>{{ $author->name }}</name>
                @endif
                @if($author->hasEmail())
                    <email>{{ $author->email }}</email>
                @endif
                @if($author->hasUri())
                    <uri>{{ $author->uri }}</uri>
                @endif
            </author>
        @endforeach
    @endif
    @if($feed->hasGenerator())
        {!! $feed->getGenerator() !!}
    @endif
    @if($feed->hasCopyright())
        <rights>{{ $feed->getCopyright() }}</rights>
    @endif
    @if($feed->hasCategory())
        <category term="{{ $feed->getCategory() }}"/>
    @endif
    @if($feed->hasIcon())
        <icon>{{ $feed->getIcon() }}</icon>
    @endif
    @if($feed->hasLogo())
        <logo>{{ $feed->getLogo() }}</logo>
    @endif
    @if($feed->hasSubtitle() || $feed->hasDescription())
        <subtitle><![CDATA[{{ $feed->getSubtitle() ?? $feed->getDescription() }}]]></subtitle>
    @endif
    @if($feed->hasContributors())
        @php
            $contributors = $feed->getContributors();
            $contributors = $contributors instanceof \LaravelSyndication\Feeds\Structure\Atom\Person
                ? collect([$contributors]) : $contributors;
            $contributors = !$contributors instanceof \Illuminate\Support\Collection
                ? collect($contributors) : $contributors;
        @endphp
        @foreach($contributors as $contributor)
            <contributor>
                @if($contributor->hasName())
                    <name>{{ $contributor->name }}</name>
                @endif
                @if($contributor->hasEmail())
                    <email>{{ $contributor->email }}</email>
                @endif
                @if($contributor->hasUri())
                    <uri>{{ $contributor->uri }}</uri>
                @endif
            </contributor>
        @endforeach
    @endif

    @foreach($items as $item)
        <entry>
            <id>{{ $item->getId() }}</id>
            <title>{{ $item->getTitle() }}</title>
            <link href="{{ $item->getUrl() }}"/>
            <updated>{{ $item->getUpdated()->format('Y-m-d\TH:i:s\Z') }}</updated>
            <summary><![CDATA[{{ $item->getDescription() }}]]></summary>
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
                <category term="{{ $item->getCategory() }}"/>
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
                        @if($author->hasName())
                            <name>{{ $author->name }}</name>
                        @endif
                        @if($author->hasEmail())
                            <email>{{ $author->email }}</email>
                        @endif
                        @if($author->hasUri())
                            <uri>{{ $author->uri }}</uri>
                        @endif
                    </author>
                @endforeach
            @endif

            @if($item->hasSource())
                <source>
                <id>{{ $item->getSource()->id }}</id>
                <title>{{ $item->getSource()->title }}</title>
                <updated>{{ $item->getSource()->updated->format('Y-m-d\TH:i:s\Z') }}</updated>
                @if($item->hasSource() && $item->getSource()->hasAuthor())
                    <author>
                        @if($item->getSource()->author->hasName())
                            <name>{{ $item->getSource()->author->name }}</name>
                        @endif
                        @if($item->getSource()->author->hasEmail())
                            <email>{{ $item->getSource()->author->email }}</email>
                        @endif
                        @if($item->getSource()->author->hasUri())
                            <uri>{{ $item->getSource()->author->uri }}</uri>
                        @endif
                    </author>
                    @endif
                    </source>
                @endif
        </entry>
    @endforeach

</feed>
