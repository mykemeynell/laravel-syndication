@php /** @var \LaravelSyndication\Feeds\Structure\Atom\Content $content */ @endphp
<content @if(!empty($content->type))type="{{ $content->type }}"@endif
        @if(!empty($content->src))src="{{ $content->src }}"@endif>{{ !empty($content->contents) ? $content->contents : null }}</content>
