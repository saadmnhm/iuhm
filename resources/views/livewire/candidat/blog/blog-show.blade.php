@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('user.blog') }}" class="inline-flex items-center gap-1 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="ri-arrow-left-line"></i> {{ $tr('Retour aux articles', 'العودة إلى المقالات') }}
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($post->image)
            <img src="{{ Str::startsWith($post->image, ['http','https','/storage']) ? $post->image : asset($post->image) }}" class="w-full h-64 md:h-80 object-cover" alt="{{ $post->title }}">
            @endif

            <div class="p-5 sm:p-8">
                <div class="flex flex-wrap gap-2 items-center mb-4">
                    @if($post->category)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $post->category }}</span>
                    @endif
                    @if($post->tags)
                    @foreach($post->tags as $tag)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $tag }}</span>
                    @endforeach
                    @endif
                </div>

                <h1 class="font-bold mb-3 text-gray-900" style="font-size: 2rem;">{{ $post->title }}</h1>

                @if($post->title_ar)
                <h2 class="text-gray-500 mb-5" dir="rtl" style="font-size: 1.5rem;">{{ $post->title_ar }}</h2>
                @endif

                <div class="flex flex-wrap items-center gap-4 text-gray-500 text-sm mb-5 pb-4 border-b border-gray-100">
                    <span class="flex items-center gap-1"><i class="ri-user-line"></i> {{ $post->author->name ?? $tr('Auteur', 'الكاتب') }}</span>
                    <span class="flex items-center gap-1"><i class="ri-calendar-line"></i> {{ $post->published_at->format('d F Y') }}</span>
                    <span class="flex items-center gap-1"><i class="ri-eye-line"></i> {{ $post->views_count }} {{ $tr('vues', 'مشاهدة') }}</span>
                </div>

                @if($post->excerpt)
                <div class="text-gray-500 leading-relaxed mb-5 italic border-l-4 border-green-500 pl-4">
                    {{ $post->excerpt }}
                </div>
                @endif

                <div class="article-content prose prose-gray max-w-none">
                    {!! $post->content !!}
                </div>
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
.article-content a { color: #648454; }
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