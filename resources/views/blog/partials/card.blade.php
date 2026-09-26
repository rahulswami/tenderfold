<article class="reveal overflow-hidden rounded-[2rem] bg-white shadow-card transition-all duration-300 hover:-translate-y-1 hover:shadow-lift flex flex-col">
  <div class="aspect-[16/10] overflow-hidden bg-cream">
    @if(!empty($blog->image_path))
      <img src="{{ site_image($blog->image_path) }}" alt="{{ $blog->title }}" class="h-full w-full object-cover transition duration-500 hover:scale-105" loading="lazy">
    @else
      <img src="{{ asset('assets/blog1.jpg') }}" alt="{{ $blog->title }}" class="h-full w-full object-cover transition duration-500 hover:scale-105" loading="lazy">
    @endif
  </div>
  <div class="p-6 sm:p-7 flex flex-1 flex-col justify-between">
    <div>
      <div class="flex items-center justify-between gap-2 text-xs font-bold uppercase tracking-wider text-gold-deep mb-2">
        <span>{{ $blog->category ?? 'Care Notes' }}</span>
        <span class="text-mute font-semibold lowercase tracking-normal">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
      </div>
      <h3 class="font-display text-xl font-extrabold text-navy">
        <a href="{{ route('blog.show', $blog->slug) }}" class="transition hover:text-gold-deep">{{ $blog->title }}</a>
      </h3>
      <p class="mt-3 text-sm leading-relaxed text-mute line-clamp-3">
        {{ Str::limit(strip_tags($blog->summary ?? $blog->content), 130) }}
      </p>
    </div>
    <div class="mt-6 pt-4 border-t border-navy/10 flex items-center justify-between">
      <a href="{{ route('blog.show', $blog->slug) }}" class="inline-flex items-center gap-1 font-display text-sm font-extrabold text-navy transition hover:text-gold-deep">
        Read note <span aria-hidden="true">→</span>
      </a>
      <span class="text-xs font-semibold text-mute">Tenderfold Desk</span>
    </div>
  </div>
</article>
