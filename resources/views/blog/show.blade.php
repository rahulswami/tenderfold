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
              "headline": "{{ $blog->title }}",
              "description": "{{ $blog->summary }}",
              "datePublished": "{{ $blog->published_at ? $blog->published_at->toIso8601String() : $blog->created_at->toIso8601String() }}",
              "publisher": {
                "{{ '@type' }}": "Organization",
                "name": "{{ $site['site_name'] ?? 'SumitZ Clinic' }}"
              }
            }
          ]
        }
        </script>
    @endif
@endsection

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 56px 0 40px;">
        <div class="container" style="max-width: 800px;">
            <div style="font-size: 14px; color: var(--muted-text); margin-bottom: 12px; display: flex; gap: 8px; align-items: center;">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('blog') }}">Health Hub</a> /
                <span style="color: var(--primary-teal); font-weight: 600;">{{ $blog->category ?? 'Article' }}</span>
            </div>
            <span class="badge badge-teal" style="margin-bottom: 12px;">{{ $blog->category ?? 'Health Guide' }}</span>
            <h1 style="font-size: clamp(28px, 3.5vw, 42px); margin-bottom: 12px; line-height: 1.25;">{{ $blog->title }}</h1>
            <div style="font-size: 14px; color: var(--muted-text);">
                Published on {{ $blog->published_at ? $blog->published_at->format('F d, Y') : $blog->created_at->format('F d, Y') }} • By SumitZ Medical Team
            </div>
        </div>
    </section>

    <!-- Editorial Article Content -->
    <article class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container" style="max-width: 740px;">
            <!-- Featured Image -->
            <div style="border-radius: var(--radius-xl); overflow: hidden; margin-bottom: 36px; box-shadow: var(--shadow-md); aspect-ratio: 16/9; background-color: var(--mist-blue);">
                @if(!empty($blog->image_path))
                    <img src="{{ site_image($blog->image_path) }}" alt="{{ $blog->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <img src="https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=1000&q=80" alt="{{ $blog->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                @endif
            </div>

            <!-- Article Body -->
            <div class="blog-content-body" style="font-size: 17.5px; line-height: 1.8; color: var(--body-text); margin-bottom: 48px;">
                {!! $blog->content !!}
            </div>

            <style>
                .blog-content-body h2 {
                    font-size: clamp(22px, 2.5vw, 28px);
                    color: var(--heading-navy);
                    margin-top: 32px;
                    margin-bottom: 16px;
                    font-weight: 700;
                    line-height: 1.3;
                }
                .blog-content-body h3 {
                    font-size: clamp(19px, 2vw, 23px);
                    color: var(--heading-navy);
                    margin-top: 28px;
                    margin-bottom: 14px;
                    font-weight: 600;
                    line-height: 1.35;
                }
                .blog-content-body h4 {
                    font-size: 18px;
                    color: var(--heading-navy);
                    margin-top: 24px;
                    margin-bottom: 12px;
                    font-weight: 600;
                }
                .blog-content-body p {
                    margin-bottom: 18px;
                }
                .blog-content-body ul, .blog-content-body ol {
                    margin-bottom: 24px;
                    padding-left: 28px;
                }
                .blog-content-body li {
                    margin-bottom: 8px;
                }
                .blog-content-body img {
                    max-width: 100%;
                    height: auto;
                    border-radius: var(--radius-lg, 12px);
                    box-shadow: var(--shadow-sm, 0 2px 8px rgba(0,0,0,0.06));
                    margin: 24px auto;
                    display: block;
                }
                .blog-content-body a {
                    color: var(--primary-teal);
                    text-decoration: underline;
                    font-weight: 500;
                    transition: color 0.2s ease;
                }
                .blog-content-body a:hover {
                    color: var(--dark-teal);
                }
                .blog-content-body blockquote {
                    border-left: 4px solid var(--primary-teal);
                    padding: 14px 20px;
                    background: var(--light-mint);
                    border-radius: 0 var(--radius-md, 8px) var(--radius-md, 8px) 0;
                    margin: 24px 0;
                    font-style: italic;
                }
                .blog-content-body table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 24px 0;
                }
                .blog-content-body th, .blog-content-body td {
                    border: 1px solid var(--border-color, #e2e8f0);
                    padding: 12px 16px;
                    text-align: left;
                }
                .blog-content-body th {
                    background-color: var(--light-mint);
                    font-weight: 600;
                }
            </style>

            <!-- Medical Disclaimer Box -->
            <div style="background-color: var(--light-mint); border-radius: var(--radius-lg); padding: 24px; border-left: 4px solid var(--primary-teal); margin-bottom: 36px;">
                <h4 style="font-size: 16px; margin-bottom: 6px; color: var(--heading-navy);">Medical Disclaimer</h4>
                <p style="font-size: 13.5px; color: var(--body-text); margin: 0;">
                    This article is for educational and informational purposes only. It should not be used as a substitute for professional orthopaedic diagnosis or personalized treatment. Always consult a qualified specialist for pain or injury.
                </p>
            </div>

            <!-- Consultation Banner CTA -->
            <div class="form-card" style="text-align: center; padding: 36px; background-color: var(--surface-white);">
                <h3 style="font-size: 24px; margin-bottom: 8px;">Consult Our Specialists Today</h3>
                <p style="font-size: 15px; color: var(--body-text); margin-bottom: 24px;">
                    Schedule a consultation at SumitZ Clinic Baner or Hinjewadi for personalized pain evaluation.
                </p>
                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <button onclick="openDialog('callback-dialog');" class="btn btn-primary">
                        <i data-lucide="calendar"></i> Book Appointment
                    </button>
                    <a href="tel:+919545559434" class="btn btn-secondary">
                        <i data-lucide="phone"></i> Call Clinic
                    </a>
                </div>
            </div>
        </div>
    </article>

@endsection
