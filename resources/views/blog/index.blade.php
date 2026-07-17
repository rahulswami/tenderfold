@extends('layouts.app')

@section('title', ($blogsPage->metatitle ?? 'Orthopaedic, Joint Care & Physiotherapy Blog | SumitZ Clinic Baner'))
@section('meta_description', ($blogsPage->metadescription ?? 'Read expert health advice, recovery guides, arthritis tips, and physiotherapy exercises written by Dr. Sumit at SumitZ Clinic, Pune.'))
@section('meta_keywords', ($blogsPage->metakeywords ?? 'health blog sumitz, orthopedic guides, back pain tips, joint health articles'))


@section('schema')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@graph' }}": [
    {
      "{{ '@type' }}": "Blog",
      "{{ '@id' }}": "{{ route('blog') }}/#blog",
      "url": "{{ route('blog') }}",
      "name": "Orthopaedic, Joint Care & Physiotherapy Blog | SumitZ Clinic",
      "description": "Read expert health advice, recovery guides, arthritis tips, and physiotherapy exercises written by Dr. Sumit at SumitZ Clinic, Pune.",
      "publisher": {
        "{{ '@type' }}": "Organization",
        "{{ '@id' }}": "{{ route('home') }}/#organization"
      }
    }
  ]
}
</script>
@endsection

@section('content')

<section class="details-hero">
    <div class="container text-center">
        <span class="color-mint font-semibold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.9rem;">HEALTH EDUCATION</span>
        <h1 style="margin-top: 10px;">SumitZ Health & Wellness Hub</h1>
        <p style="color: var(--text-light-muted); max-width: 600px; margin: 0 auto;">Articles, recovery guides, and expert wellness tips from our specialists to keep your joints and spine active and healthy.</p>
    </div>
</section>

<!-- Blog Grid -->
<section class="section">
    <div class="container blog-grid animate-on-scroll" id="blogs-wrapper">
        @forelse($blogs as $blog)
            @include('blog.partials.card', ['blog' => $blog])
        @empty
            <div class="text-center" id="empty-blogs-msg" style="grid-column: 1 / -1; padding: 40px; color: var(--text-light-muted);">
                <p>No blog posts found at the moment. Please check back later.</p>
            </div>
        @endforelse
    </div>
    
    <div id="blogs-loading" style="text-align: center; display: none; padding: 3rem 1rem 1rem 1rem;">
        <span style="color: var(--accent-teal); font-weight: 600; font-size: 1.1rem; letter-spacing: 1px;">Loading more recovery guides...</span>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let nextPageUrl = "{{ $blogs->nextPageUrl() }}";
    let loading = false;
    
    const wrapper = document.getElementById('blogs-wrapper');
    const loader = document.getElementById('blogs-loading');

    window.addEventListener('scroll', function() {
        if (!nextPageUrl || loading) return;
        
        // If scroll reaches near bottom of the viewport
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 800) {
            loading = true;
            loader.style.display = 'block';

            fetch(nextPageUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(res => {
                nextPageUrl = res.next_page;
                
                // Append new card elements
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = res.html;
                
                // Append children one by one
                while (tempDiv.firstChild) {
                    wrapper.appendChild(tempDiv.firstChild);
                }
                
                // Re-initialize lucide icons for newly loaded content if lucide is present
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                loading = false;
                loader.style.display = 'none';
            })
            .catch(err => {
                console.error('Error loading page:', err);
                loading = false;
                loader.style.display = 'none';
            });
        }
    });
});
</script>

@endsection
