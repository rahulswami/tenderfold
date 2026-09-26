@extends('layouts.app')

@section('title', ($page->metatitle ?? ($page->title . ' | ' . ($site['site_name'] ?? 'Tenderfold'))))
@section('meta_description', $page->metadescription ?? Str::limit(strip_tags($page->content), 150))
@section('meta_keywords', $page->metakeywords ?? '')

@section('schema')
  @if(!empty($page->schema_markup))
    {!! $page->schema_markup !!}
  @else
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "{{ $page->title }}"
    }
    </script>
  @endif
@endsection

@section('content')
<main id="main">
  <!-- Header -->
  <section class="bg-cream">
    <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
      <div class="reveal">
        <p class="text-sm font-semibold text-mute">
          <a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> 
          <span aria-hidden="true">/</span> 
          <span class="text-navy">{{ $page->title }}</span>
        </p>
        <h1 class="mt-4 font-display text-3xl font-extrabold text-navy sm:text-4xl lg:text-5xl">{{ $page->title }}</h1>
        @if($page->updated_at)
          <p class="mt-2 text-xs font-semibold text-mute">Last updated: {{ $page->updated_at->format('F d, Y') }}</p>
        @endif
      </div>
    </div>
  </section>

  <!-- Page Content -->
  <section class="bg-white py-12 sm:py-16">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <div class="reveal prose prose-lg max-w-none text-ink leading-relaxed">
        {!! $page->content !!}
      </div>
    </div>
  </section>
</main>
@endsection
