<div>
    <div class="mb-4">
        <a href="{{ route('user.blog') }}" class="text-decoration-none text-muted">
            <i class="ri-arrow-left-line me-1"></i> Retour aux articles
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden max-w-4xl m-auto">
        @if($post->image)
        <img src="{{ Str::startsWith($post->image, ['http','https','/storage']) ? $post->image : asset($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="max-height: 400px; object-fit: cover;">
        @endif

        <div class="card-body p-4 p-md-5">
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                @if($post->category)
                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $post->category }}</span>
                @endif
                @if($post->tags)
                @foreach($post->tags as $tag)
                <span class="badge bg-light text-muted">{{ $tag }}</span>
                @endforeach
                @endif
            </div>

            <h1 class="fw-bold mb-3" style="font-size: 2rem;">{{ $post->title }}</h1>

            @if($post->title_ar)
            <h2 class="text-muted mb-4" dir="rtl" style="font-size: 1.5rem;">{{ $post->title_ar }}</h2>
            @endif

            <div class="d-flex align-items-center gap-4 text-muted small mb-4 pb-4 border-bottom">
                <span><i class="ri-user-line me-1"></i> {{ $post->author->name ?? 'Auteur' }}</span>
                <span><i class="ri-calendar-line me-1"></i> {{ $post->published_at->format('d F Y') }}</span>
                <span><i class="ri-eye-line me-1"></i> {{ $post->views_count }} vues</span>
            </div>

            @if($post->excerpt)
            <div class="lead text-muted mb-4 fst-italic border-start border-primary border-3 ps-3">
                {{ $post->excerpt }}
            </div>
            @endif

            <div class="article-content" style="line-height: 1.8; font-size: 1rem;">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>
    </div>
</div>
