@extends('layouts.app')

@section('title', ($page->metatitle ?? ($page->title . ' | ' . ($site['site_name'] ?? 'SumitZ Clinic'))))
@section('meta_description', $page->metadescription ?? Str::limit(strip_tags($page->content), 150))

@section('schema')
    @if(!empty($page->schema_markup))
        {!! $page->schema_markup !!}
    @else
        <script type="application/ld+json">
        {
          "{{ '@' }}context": "https://schema.org",
          "{{ '@graph' }}": [
            {
              "{{ '@type' }}": "WebPage",
              "{{ '@id' }}": "{{ route('cms.show', $page->slug) }}/#webpage",
              "url": "{{ route('cms.show', $page->slug) }}",
              "name": "{{ $page->title }}",
              "description": "{{ $page->metadescription ?? strip_tags(Str::limit($page->content, 150)) }}"
            }
          ]
        }
        </script>
    @endif
@endsection

@section('content')
<section class="details-hero">
    <div class="container text-center">
        <h1>{{ $page->title }}</h1>
        @if($page->updated_at)
            <p style="color: var(--text-light-muted); font-size: 0.9rem; margin-top: 5px;">Last updated: {{ $page->updated_at->format('F d, Y') }}</p>
        @endif
    </div>
</section>

<section class="section">
    <div class="container animate-on-scroll" style="max-width: 900px; margin: 0 auto;">
        <div class="blog-content" style="line-height: 1.8; font-size: 1.05rem; color: var(--text-dark);">
            {!! $page->content !!}
        </div>
    </div>
</section>
@endsection
