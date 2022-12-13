@php /** @var \LaravelSyndication\Feeds\Structure\Atom\Content $content */ @endphp
<content @if(!empty($content->type))type="{{ $content->type }}"@endif
        @if(!empty($content->src))src="{{ $content->src }}"@endif><![CDATA[{!! !empty($content->contents) && empty($content->src) ? $content->contents : null !!}]]></content>
