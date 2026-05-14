@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif>
    <div class="mb-4">
        <a href="{{ route('user.blog') }}" class="text-decoration-none text-muted">
            <i class="ri-arrow-left-line me-1"></i> {{ $tr('Retour aux articles', 'العودة إلى المقالات') }}
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
                <span><i class="ri-user-line me-1"></i> {{ $post->author->name ?? $tr('Auteur', 'الكاتب') }}</span>
                <span><i class="ri-calendar-line me-1"></i> {{ $post->published_at->format('d F Y') }}</span>
                <span><i class="ri-eye-line me-1"></i> {{ $post->views_count }} {{ $tr('vues', 'مشاهدة') }}</span>
            </div>

            @if($post->excerpt)
            <div class="lead text-muted mb-4 fst-italic border-start border-primary border-3 ps-3">
                {{ $post->excerpt }}
            </div>
            @endif

            <div class="article-content">
                {!! $post->content !!}
            </div>
        </div>
    </div>
</div>

<style>
.article-content { line-height: 1.8; font-size: 1rem; color: #4A5568; }
.article-content h2 { font-size: 1.75rem; font-weight: 700; color: #0B1528; margin-top: 2.5rem; margin-bottom: 1rem; }
.article-content h3 { font-size: 1.35rem; font-weight: 700; color: #0B1528; margin-top: 2rem; margin-bottom: 0.75rem; }
.article-content h4 { font-size: 1.1rem; font-weight: 700; color: #0B1528; margin-top: 1.5rem; margin-bottom: 0.5rem; }
.article-content p { margin-bottom: 1.25rem; }
.article-content a { color: #214f95; }
.article-content ul, .article-content ol { padding-left: 1.5rem; margin-bottom: 1.25rem; }
.article-content li { margin-bottom: 0.4rem; }
.article-content strong { font-weight: 700; }
.article-content em { font-style: italic; }
.article-content hr { border: none; border-top: 2px solid #e2e8f0; margin: 2rem 0; }
.article-content blockquote {
    background: #F0F2F5;
    border-radius: 1rem;
    border-left: 6px solid #82E682;
    padding: 1.25rem 1.75rem;
    margin: 1.75rem 0;
    color: #0B1528;
    font-size: 1.05rem;
    font-style: normal;
}
.article-content img { max-width: 100%; border-radius: 1rem; height: auto; display: block; }
/* Multi-image row */
.article-content .iuhm-img-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin: 1.5rem 0; }
.article-content .iuhm-img-row img { flex: 1 1 0; min-width: 0; width: auto; max-width: 100%; border-radius: 1rem; object-fit: cover; }
/* Tables */
.article-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; font-size: 0.95rem; }
.article-content table td, .article-content table th { border: 1px solid #e2e8f0; padding: 0.6rem 0.9rem; }
.article-content table th { background: #f8fafc; font-weight: 700; color: #0B1528; }
.article-content table tr:nth-child(even) td { background: #fafafa; }
/* Callout boxes */
.article-content [style*="border-left"] { border-radius: 0.75rem !important; }
</style>
