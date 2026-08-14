@extends('layouts.app')

@section('title', ($page->metatitle ?? ($page->title . ' | ' . ($site['site_name'] ?? 'SumitZ Clinic'))))
@section('meta_description', $page->metadescription ?? Str::limit(strip_tags($page->content), 150))
@section('meta_keywords', $page->metakeywords ?? '')

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
              "name": "{{ $page->title }}"
            }
          ]
        }
        </script>
    @endif
@endsection

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 56px 0 40px;">
        <div class="container text-center" style="max-width: 800px;">
            <h1 style="font-size: clamp(30px, 3.5vw, 44px); margin-bottom: 8px;">{{ $page->title }}</h1>
            @if($page->updated_at)
                <span style="font-size: 13.5px; color: var(--muted-text);">Last updated: {{ $page->updated_at->format('F d, Y') }}</span>
            @endif
        </div>
    </section>

    <!-- Page Content -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container" style="max-width: 800px; font-size: 16.5px; line-height: 1.8; color: var(--body-text);">
            {!! $page->content !!}
        </div>
    </section>

@endsection
