<article class="blog-card">
    <div class="blog-img" style="{{ $blog->image_path ? '' : 'background-color: var(--accent-teal)' }}">
        @if($blog->image_path)
            <img src="{{ site_image($blog->image_path) }}" alt="{{ $blog->title }}" style="width: 100%; height: 100%; object-fit: cover;">
        @else
            <i data-lucide="book-open"></i>
        @endif
        <span class="blog-category-badge">{{ $blog->category ?? 'Orthopaedics' }}</span>
    </div>
    <div class="blog-body">
        <span class="blog-date">{{ $blog->published_at ? $blog->published_at->format('F d, Y') : $blog->created_at->format('F d, Y') }}</span>
        <h3>{{ $blog->title }}</h3>
        <p>{{ $blog->summary }}</p>
        <a href="{{ route('blog.show', $blog->slug) }}" class="blog-readmore">Read Article <i data-lucide="chevron-right"></i></a>
    </div>
</article>
