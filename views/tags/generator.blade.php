@php /** @var \LaravelSyndication\Feeds\Structure\FeedGenerator $generator */ @endphp
<generator
        @if(!empty($generator->uri)) uri="{{ $generator->uri }}" @endif
        @if(!empty($generator->version)) version="{{ $generator->version }}" @endif
>{{ $generator->name }}</generator>
