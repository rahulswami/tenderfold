@extends('layouts.app')

@section('title', ($blog->metatitle ?? ($blog->title . ' | ' . ($site['site_name'] ?? 'SumitZ Clinic'))))
@section('meta_description', ($blog->metadescription ?? $blog->summary))
@section('meta_keywords', ($blog->metakeywords ?? ''))


@section('schema')
    @if(!empty($blog->schema_markup))
        {!! $blog->schema_markup !!}
    @else
        <script type="application/ld+json">
        {
          "{{ '@' }}context": "https://schema.org",
          "{{ '@graph' }}": [
            {
              "{{ '@type' }}": "BlogPosting",
              "{{ '@id' }}": "{{ route('blog.show', $blog->slug) }}/#post",
              "headline": "{{ $blog->title }}",
              "description": "{{ $blog->summary }}",
              "datePublished": "{{ $blog->published_at->toIso8601String() }}",
              "dateModified": "{{ $blog->updated_at->toIso8601String() }}",
              "mainEntityOfPage": "{{ route('blog.show', $blog->slug) }}",
              "image": "{{ $blog->image_path ? site_image($blog->image_path) : asset('assets/images/blog-default.jpg') }}",
              "author": {
                "{{ '@type' }}": "Organization",
                "name": "SumitZ Medical Team",
                "url": "{{ route('home') }}"
              },
              "publisher": {
                "{{ '@type' }}": "Organization",
                "{{ '@id' }}": "{{ route('home') }}/#organization",
                "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}",
                "url": "{{ route('home') }}"
              }
            }
          ]
        }
        </script>
    @endif
@endsection

@section('content')

    <!-- Blog Header -->
    <header class="blog-article-header" style="padding: 100px 0 40px; background-color: var(--bg-light); text-align: center;">
        <div class="container">
            <span class="color-accent font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">{{ $blog->category ?? 'Orthopaedics' }}</span>
            <h1 style="margin-top: 10px; font-size: 2.5rem; color: var(--primary-teal-dark);">{{ $blog->title }}</h1>
            <div class="blog-article-meta" style="margin-top: 15px; color: var(--text-light-muted); font-size: 0.9rem;">
                <span>Published on</span>
                <span>•</span>
                <span>{{ $blog->published_at ? $blog->published_at->format('F d, Y') : $blog->created_at->format('F d, Y') }}</span>
            </div>
        </div>
    </header>

    <!-- Article Body -->
    <article class="blog-article-body" style="max-width: 800px; margin: 40px auto; padding: 0 20px; line-height: 1.8; color: var(--text-dark);">
        @if($blog->image_path)
            <div style="margin-bottom: 30px; border-radius: var(--radius-md); overflow: hidden;">
                <img src="{{ site_image($blog->image_path) }}" alt="{{ $blog->title }}" style="width: 100%; height: auto; max-height: 400px; object-fit: cover;">
            </div>
        @endif
        
        <div class="blog-content animate-on-scroll">
            {!! $blog->content !!}
        </div>

        <div style="background-color: var(--bg-light); padding: 2rem; border-radius: var(--radius-md); border-left: 4px solid var(--accent-teal); margin: 3rem 0;">
            <h3 style="margin-bottom:0.5rem; color:var(--primary-teal-dark);">Consult Dr. Sumit Today</h3>
            <p style="font-size:0.95rem; color:var(--text-muted); margin-bottom:1.5rem;">Schedule a physical consultation or physiotherapy appointment for joint pain, spine issues, or general orthopaedic conditions.</p>
            <button onclick="document.getElementById('callback-dialog').style.display='flex';" class="btn btn-primary">Book Appointment</button>
        </div>
    </article>

@endsection
