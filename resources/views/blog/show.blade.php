@extends('layouts.app')

@section('title', ($blog->metatitle ?? ($blog->title . ' | Tenderfold Journal')))
@section('meta_description', ($blog->metadescription ?? Str::limit(strip_tags($blog->summary ?? $blog->content), 160)))
@section('meta_keywords', ($blog->metakeywords ?? 'tenderfold journal, fabric care, dry cleaning tips'))

@section('schema')
  @if(!empty($blog->schema_markup))
    {!! $blog->schema_markup !!}
  @else
    <script type="application/ld+json">
    {
      "{{ '@' }}context": "https://schema.org",
      "{{ '@' }}type": "BlogPosting",
      "headline": "{{ addslashes($blog->title) }}",
      "description": "{{ addslashes($blog->summary ?? Str::limit(strip_tags($blog->content), 160)) }}",
      "datePublished": "{{ $blog->published_at ? $blog->published_at->toIso8601String() : $blog->created_at->toIso8601String() }}",
      "image": "{{ !empty($blog->image_path) ? site_image($blog->image_path) : asset('assets/whiteshirt.jpg') }}",
      "publisher": {
        "{{ '@' }}type": "Organization",
        "name": "{{ $site['site_name'] ?? 'Tenderfold' }}",
        "logo": {
          "{{ '@' }}type": "ImageObject",
          "url": "{{ asset('assets/logo.png') }}"
        }
      }
    }
    </script>
  @endif
@endsection

@section('content')
<main id="main">
  <!-- Article Header & Hero -->
  <section class="bg-cream">
    <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
      <div class="reveal">
        <p class="text-sm font-semibold text-mute">
          <a href="{{ route('home') }}" class="transition hover:text-navy">Home</a> 
          <span aria-hidden="true">/</span> 
          <a href="{{ route('blog') }}" class="transition hover:text-navy">Journal</a> 
          @if($blog->category)
            <span aria-hidden="true">/</span> 
            <span>{{ $blog->category }}</span> 
          @endif
          <span aria-hidden="true">/</span> 
          <span class="text-navy truncate">{{ Str::limit($blog->title, 30) }}</span>
        </p>
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-gold-deep">{{ $blog->category ?? 'Care Notes' }}</p>
        <h1 class="mt-3 font-display text-3xl font-extrabold text-navy sm:text-4xl lg:text-5xl leading-tight">{{ $blog->title }}</h1>
        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-mute">
          <span>Published: {{ $blog->published_at ? $blog->published_at->format('F d, Y') : $blog->created_at->format('F d, Y') }}</span>
          <span aria-hidden="true">•</span>
          <span>Tenderfold Fabric Care Desk</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Article Body -->
  <section class="bg-white py-12 sm:py-16">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <!-- Featured Image -->
      <div class="reveal overflow-hidden rounded-3xl shadow-card bg-cream mb-10">
        @if(!empty($blog->image_path))
          <img src="{{ site_image($blog->image_path) }}" alt="{{ $blog->title }}" class="w-full max-h-[500px] object-cover">
        @else
          <img src="{{ asset('assets/whiteshirt.jpg') }}" alt="{{ $blog->title }}" class="w-full max-h-[500px] object-cover">
        @endif
      </div>

      <!-- Content Container -->
      <style>
        /* Blog article typography and inline images between paragraphs */
        .article-prose {
          color: #1A2744;
        }
        .article-prose h1, .article-prose h2, .article-prose h3, .article-prose h4, .article-prose h5, .article-prose h6 {
          font-family: 'Nunito', sans-serif;
          font-weight: 800;
          color: #143572;
          line-height: 1.35;
          margin-top: 2rem;
          margin-bottom: 0.85rem;
        }
        .article-prose h1 { font-size: 2.25rem; }
        .article-prose h2 { font-size: 1.75rem; }
        .article-prose h3 { font-size: 1.35rem; }
        .article-prose h4 { font-size: 1.15rem; }
        .article-prose p {
          margin-bottom: 1.25rem;
          line-height: 1.8;
        }
        .article-prose ul {
          list-style-type: disc !important;
          padding-left: 1.75rem !important;
          margin-top: 0.75rem;
          margin-bottom: 1.25rem;
        }
        .article-prose ol {
          list-style-type: decimal !important;
          padding-left: 1.75rem !important;
          margin-top: 0.75rem;
          margin-bottom: 1.25rem;
        }
        .article-prose li {
          margin-bottom: 0.5rem;
          line-height: 1.7;
          display: list-item !important;
        }
        .article-prose a {
          color: #143572;
          font-weight: 600;
          text-decoration: underline;
          text-underline-offset: 3px;
          transition: color 0.2s ease;
        }
        .article-prose a:hover {
          color: #F5B400;
        }
        .article-prose blockquote {
          border-left: 4px solid #F5B400;
          padding: 0.75rem 1.25rem;
          margin: 1.75rem 0;
          background: #F4F7FB;
          border-radius: 0 12px 12px 0;
          font-style: italic;
          color: #5C6A82;
        }
        /* Inline Images placed between paragraphs */
        .article-prose img {
          max-width: 100% !important;
          height: auto !important;
          border-radius: 1rem;
          box-shadow: 0 10px 30px rgba(20, 53, 114, 0.08);
          margin: 2rem auto !important;
          display: block !important;
          object-fit: cover;
        }
        .article-prose p > img {
          margin: 1.5rem auto !important;
          display: block !important;
        }
        .article-prose figure {
          margin: 2rem auto !important;
          text-align: center;
        }
        .article-prose figure img {
          margin: 0 auto !important;
        }
        .article-prose figcaption {
          font-size: 0.875rem;
          color: #5C6A82;
          margin-top: 0.75rem;
          text-align: center;
          font-style: italic;
        }

        /* ==========================================================================
           TenderFold Blog Article Table Formatting (Global Responsive Tables)
           ========================================================================== */
        
        /* Table Container / Wrapper */
        .article-prose .blog-table-wrapper,
        .article-prose .table-responsive,
        .blog-table-wrapper,
        .table-responsive {
          width: 100%;
          margin: 2rem 0 !important;
          overflow-x: auto;
          -webkit-overflow-scrolling: touch;
          background: #ffffff;
          border-radius: 1rem;
          border: 1px solid rgba(20, 53, 114, 0.12);
          box-shadow: 0 4px 20px -2px rgba(20, 53, 114, 0.06), 0 2px 6px -1px rgba(20, 53, 114, 0.03);
          position: relative;
        }

        /* Custom Scrollbar for Smooth Mobile/Desktop Experience */
        .article-prose .blog-table-wrapper::-webkit-scrollbar,
        .article-prose .table-responsive::-webkit-scrollbar {
          height: 6px;
        }
        .article-prose .blog-table-wrapper::-webkit-scrollbar-track,
        .article-prose .table-responsive::-webkit-scrollbar-track {
          background: #F4F7FB;
          border-radius: 4px;
        }
        .article-prose .blog-table-wrapper::-webkit-scrollbar-thumb,
        .article-prose .table-responsive::-webkit-scrollbar-thumb {
          background: rgba(20, 53, 114, 0.22);
          border-radius: 4px;
        }
        .article-prose .blog-table-wrapper::-webkit-scrollbar-thumb:hover,
        .article-prose .table-responsive::-webkit-scrollbar-thumb:hover {
          background: #F5B400;
        }

        /* Table Root Element */
        .article-prose table {
          width: 100% !important;
          min-width: 540px; /* Prevents column squashing on small viewports */
          border-collapse: separate !important;
          border-spacing: 0 !important;
          margin: 0 !important;
          font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
          font-size: 0.95rem;
          line-height: 1.6;
          color: #1A2744;
          text-align: left;
          background-color: #ffffff;
        }

        /* Reset arbitrary fixed widths from TinyMCE colgroup */
        .article-prose table colgroup,
        .article-prose table col {
          width: auto !important;
        }

        /* Optional Table Caption */
        .article-prose table caption {
          caption-side: top;
          font-family: 'Nunito', sans-serif;
          font-weight: 800;
          font-size: 1.15rem;
          color: #143572;
          padding: 0.85rem 1rem;
          text-align: center;
          background: #F4F7FB;
          border-bottom: 2px solid rgba(20, 53, 114, 0.1);
        }

        /* Table Title / Header Row (Visually Distinct Gold Header with Centered Deep Navy Text) */
        .article-prose table thead th,
        .article-prose table thead td,
        .article-prose table th,
        .article-prose table thead tr.table-header-row th,
        .article-prose table tbody tr.table-header-row td,
        .article-prose table thead tr th,
        .article-prose table tbody tr:first-child:has(strong):not(:has(td:not(:has(strong)))) td {
          background: #F5B400 !important;
          background-image: linear-gradient(180deg, #F9BE1A 0%, #F5B400 100%) !important;
          color: #0C2454 !important;
          font-family: 'Nunito', sans-serif !important;
          font-weight: 800 !important;
          font-size: 0.975rem !important;
          letter-spacing: 0.03em;
          padding: 1.15rem 1.25rem !important;
          text-align: center !important;
          vertical-align: middle !important;
          border-bottom: 3px solid #0C2454 !important;
          border-right: 1px solid rgba(12, 36, 84, 0.16) !important;
          border-top: none !important;
          border-left: none !important;
          white-space: nowrap;
        }

        /* Header Inner Typography Reset - Centered Horizontally & Vertically */
        .article-prose table thead th p,
        .article-prose table thead td p,
        .article-prose table th p,
        .article-prose table thead th strong,
        .article-prose table thead td strong,
        .article-prose table th strong,
        .article-prose table tbody tr.table-header-row td p,
        .article-prose table tbody tr.table-header-row td strong,
        .article-prose table tbody tr:first-child:has(strong):not(:has(td:not(:has(strong)))) td p,
        .article-prose table tbody tr:first-child:has(strong):not(:has(td:not(:has(strong)))) td strong {
          color: #0C2454 !important;
          font-family: 'Nunito', sans-serif !important;
          font-weight: 800 !important;
          font-size: inherit !important;
          letter-spacing: inherit !important;
          margin: 0 auto !important;
          padding: 0 !important;
          text-align: center !important;
          display: block !important;
          line-height: 1.35 !important;
        }

        /* Header Corner Radius */
        .article-prose table thead tr:first-child th:first-child,
        .article-prose table thead tr:first-child td:first-child,
        .article-prose table tbody tr:first-child td:first-child,
        .article-prose table tbody tr:first-child th:first-child {
          border-top-left-radius: 0.95rem;
        }
        .article-prose table thead tr:first-child th:last-child,
        .article-prose table thead tr:first-child td:last-child,
        .article-prose table tbody tr:first-child td:last-child,
        .article-prose table tbody tr:first-child th:last-child {
          border-top-right-radius: 0.95rem;
          border-right: none !important;
        }

        /* Table Data Cells */
        .article-prose table tbody td {
          padding: 0.95rem 1.25rem !important;
          vertical-align: middle;
          border-bottom: 1px solid rgba(20, 53, 114, 0.08) !important;
          border-right: 1px solid rgba(20, 53, 114, 0.06) !important;
          border-top: none !important;
          border-left: none !important;
          color: #1A2744;
          font-size: 0.935rem;
          line-height: 1.6;
        }

        /* Remove borders on outer edges */
        .article-prose table tr td:last-child,
        .article-prose table tr th:last-child {
          border-right: none !important;
        }
        .article-prose table tbody tr:last-child td {
          border-bottom: none !important;
        }

        /* Bottom Corner Radius */
        .article-prose table tbody tr:last-child td:first-child {
          border-bottom-left-radius: 0.95rem;
        }
        .article-prose table tbody tr:last-child td:last-child {
          border-bottom-right-radius: 0.95rem;
        }

        /* Alternating Row Striping */
        .article-prose table tbody tr:nth-child(even) {
          background-color: #F8FAFD;
        }
        .article-prose table tbody tr:nth-child(odd) {
          background-color: #ffffff;
        }

        /* Hover Interaction on Rows */
        .article-prose table tbody tr:hover {
          background-color: #F0F4FA !important;
          transition: background-color 0.15s ease-in-out;
        }

        /* Distinct Emphasis for First Column (Labels/Features) */
        .article-prose table tbody tr td:first-child {
          font-weight: 600;
          color: #143572;
        }

        /* Reset paragraph margins and line heights inside table cells */
        .article-prose table td p,
        .article-prose table th p {
          margin: 0 !important;
          padding: 0 !important;
          line-height: 1.55 !important;
          color: inherit !important;
          font-size: inherit !important;
        }

        .article-prose table td ul,
        .article-prose table td ol {
          margin: 0.35rem 0 !important;
          padding-left: 1.25rem !important;
        }

        .article-prose table td li {
          margin-bottom: 0.25rem !important;
          line-height: 1.5 !important;
        }

        /* Responsive Mobile Adjustments */
        @media (max-width: 768px) {
          .article-prose .blog-table-wrapper,
          .article-prose .table-responsive {
            margin: 1.5rem 0 !important;
            border-radius: 0.75rem;
          }
          .article-prose table {
            min-width: 480px;
            font-size: 0.875rem;
          }
          .article-prose table thead th,
          .article-prose table thead td,
          .article-prose table th,
          .article-prose table thead tr.table-header-row th,
          .article-prose table tbody tr.table-header-row td,
          .article-prose table thead tr th {
            padding: 0.85rem 0.95rem !important;
            font-size: 0.875rem !important;
            text-align: center !important;
            vertical-align: middle !important;
          }
          .article-prose table tbody td {
            padding: 0.8rem 1rem !important;
            font-size: 0.875rem !important;
          }
        }
      </style>
      <div class="prose prose-lg article-prose max-w-none text-ink text-base sm:text-lg leading-relaxed space-y-6">
        {!! clean_blog_content($blog->content) !!}
      </div>

      <!-- Action Card in Article -->
      <div class="reveal mt-12 rounded-3xl bg-cream p-8 shadow-card flex flex-col sm:flex-row items-center justify-between gap-6 border border-navy/10">
        <div>
          <h3 class="font-display text-xl font-extrabold text-navy">Need help caring for your clothes?</h3>
          <p class="mt-1 text-sm text-mute">We pick up and deliver across Gurgaon from Sector 49 & Sector 83.</p>
        </div>
        <div class="flex flex-wrap gap-3">
          <button type="button" data-open-book class="rounded-full bg-gold px-6 py-3 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Schedule a Pickup</button>
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site['whatsapp_number'] ?? '919582466445') }}?text={{ urlencode('Hi Tenderfold, I read your article: ' . $blog->title . ' and have a question.') }}" target="_blank" rel="noopener" class="rounded-full border border-navy/20 bg-white px-5 py-3 font-display text-sm font-extrabold text-navy transition hover:bg-navy hover:text-white">Ask on WhatsApp</a>
        </div>
      </div>

      <!-- Related Posts -->
      @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
        <div class="mt-16 pt-12 border-t border-navy/10">
          <h2 class="font-display text-2xl font-extrabold text-navy mb-8">
            @if($blog->category)
              More in {{ $blog->category }}
            @else
              More Care Notes
            @endif
          </h2>
          <div class="grid gap-6 sm:grid-cols-2">
            @foreach($relatedBlogs as $rBlog)
              @include('blog.partials.card', ['blog' => $rBlog])
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </section>

  <!-- CTA Banner -->
  <section class="bg-navy">
    <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-12 sm:px-6 md:flex-row md:items-center lg:px-8">
      <div class="max-w-xl">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold">Free pickup &amp; delivery</p>
        <h2 class="mt-2 font-display text-3xl font-extrabold text-white">Ready to experience tender fabric care?</h2>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Schedule your free pickup now or reach out to our desk on WhatsApp.</p>
      </div>
      <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
        <button type="button" data-open-book class="inline-flex items-center justify-center rounded-full bg-gold px-6 py-3.5 font-display text-sm font-extrabold text-navy transition duration-300 hover:-translate-y-0.5 hover:bg-gold-deep">Schedule a Pickup</button>
        <a href="tel:{{ preg_replace('/[^0-9\+]/', '', $site['phone'] ?? '+919582466445') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white/25 px-6 py-3.5 font-display text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-navy">Call the store</a>
      </div>
    </div>
  </section>
</main>

<script>
  // Ensure every table inside blog content has a responsive scroll wrapper
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.article-prose table').forEach(function(table) {
      if (!table.closest('.blog-table-wrapper, .table-responsive')) {
        var wrapper = document.createElement('div');
        wrapper.className = 'blog-table-wrapper table-responsive';
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
      }
    });
  });
</script>
@endsection
