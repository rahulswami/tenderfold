@extends('layouts.app')

@section('title', ($blogsPage->metatitle ?? 'Orthopaedic, Joint Care & Physiotherapy Blog | SumitZ Clinic'))
@section('meta_description', ($blogsPage->metadescription ?? 'Read expert health advice, recovery guides, arthritis tips, and physiotherapy exercises written by Dr. Sumit at SumitZ Clinic in Baner & Hinjewadi, Pune.'))
@section('meta_keywords', ($blogsPage->metakeywords ?? 'health blog sumitz, orthopedic guides, back pain tips, joint health articles'))

@section('content')

    <!-- Hero Header -->
    <section class="section-spacing" style="background: linear-gradient(135deg, var(--light-mint) 0%, var(--mist-blue) 100%); padding: 64px 0 48px;">
        <div class="container text-center" style="max-width: 760px;">
            <span class="badge badge-teal" style="margin-bottom: 12px;">Health & Wellness Hub</span>
            <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 16px;">Orthopaedic & Recovery Guides</h1>
            <p style="font-size: 17px; color: var(--body-text); line-height: 1.7;">
                Medical insights, exercise routines, arthritis care, and rehabilitation advice written by our clinical team to help you maintain healthy joints and spine.
            </p>
        </div>
    </section>

    <!-- Blog Listing Grid -->
    <section class="section-spacing" style="background-color: var(--warm-ivory);">
        <div class="container">
            <div class="card-grid" id="blogs-wrapper">
                @forelse($blogs as $blog)
                    @include('blog.partials.card', ['blog' => $blog])
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--muted-text);">
                        <p>No health guides published yet.</p>
                    </div>
                @endforelse
            </div>

            <div id="blogs-loading" style="text-align: center; display: none; padding: 32px 0;">
                <span style="color: var(--primary-teal); font-weight: 600; font-size: 15px;">Loading more recovery guides...</span>
            </div>
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
                
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 800) {
                    loading = true;
                    loader.style.display = 'block';

                    fetch(nextPageUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(res => {
                        nextPageUrl = res.next_page;
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = res.html;
                        
                        while (tempDiv.firstChild) {
                            wrapper.appendChild(tempDiv.firstChild);
                        }
                        
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
