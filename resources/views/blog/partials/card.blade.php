<div class="medical-card">
    <div class="card-img-wrapper" style="aspect-ratio: 16/9;">
        @if(!empty($blog->image_path))
            <img src="{{ site_image($blog->image_path) }}" alt="{{ $blog->title }}" loading="lazy" decoding="async">
        @else
            <img src="https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=600&q=80" alt="{{ $blog->title }}" loading="lazy" decoding="async">
        @endif
    </div>
    <div class="card-body">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <span class="card-category">{{ $blog->category ?? 'Health Guide' }}</span>
            <span style="font-size: 12.5px; color: var(--muted-text);">
                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
            </span>
        </div>
        <h3 class="card-title" style="font-size: 18px; margin-bottom: 10px;">{{ $blog->title }}</h3>
        <p class="card-text" style="font-size: 14.5px;">{{ Str::limit(strip_tags($blog->summary ?? $blog->content), 100) }}</p>
        <div class="card-footer">
            <a href="{{ route('blog.show', $blog->slug) }}" class="card-link">
                Read Article <i data-lucide="arrow-right" style="width: 15px;"></i>
            </a>
        </div>
    </div>
</div>
