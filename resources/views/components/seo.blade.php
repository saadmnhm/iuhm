{{-- Dynamic SEO metadata (title, description, canonical, robots, Open Graph, Twitter, JSON-LD). --}}
@if(filled($seo->title))
<title>{{ $seo->title }}</title>
@endif

@if(filled($seo->description))
<meta name="description" content="{{ $seo->description }}">
@endif

@if(count($seo->keywords) > 0)
<meta name="keywords" content="{{ implode(', ', $seo->keywords) }}">
@endif

<meta name="robots" content="{{ $seo->robots }}">

@if(filled($seo->canonical))
<link rel="canonical" href="{{ $seo->canonical }}">
<meta property="og:url" content="{{ $seo->canonical }}">
@endif

<meta property="og:site_name" content="{{ $seo->siteName }}">
<meta property="og:type" content="{{ $seo->ogType }}">
@if(filled($seo->ogTitle))
<meta property="og:title" content="{{ $seo->ogTitle }}">
@endif
@if(filled($seo->ogDescription))
<meta property="og:description" content="{{ $seo->ogDescription }}">
@endif
@if(filled($seo->ogImage))
<meta property="og:image" content="{{ $seo->ogImage }}">
@if(filled($seo->imageAlt))
<meta property="og:image:alt" content="{{ $seo->imageAlt }}">
@endif
@endif
@if($seo->publishedAt)
<meta property="article:published_time" content="{{ $seo->publishedAt->toAtomString() }}">
@endif
@if($seo->modifiedAt)
<meta property="article:modified_time" content="{{ $seo->modifiedAt->toAtomString() }}">
@endif

<meta name="twitter:card" content="{{ $seo->twitterCard }}">
@if(filled($seo->twitterTitle))
<meta name="twitter:title" content="{{ $seo->twitterTitle }}">
@endif
@if(filled($seo->twitterDescription))
<meta name="twitter:description" content="{{ $seo->twitterDescription }}">
@endif
@if(filled($seo->twitterImage))
<meta name="twitter:image" content="{{ $seo->twitterImage }}">
@endif

@if(filled($seo->author))
<meta name="author" content="{{ $seo->author }}">
@endif

<script type="application/ld+json">{!! $jsonLd() !!}</script>
