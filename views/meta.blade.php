@foreach($feeds as $feed)
    <link rel="alternate" type="{{ $feed['type'] }}" title="{{ $feed['title'] }}" href="{{ $feed['route'] }}" />
@endforeach
