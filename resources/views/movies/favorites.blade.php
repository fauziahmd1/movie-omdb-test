@extends('layouts.app')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('movies.index') }}"
        style="padding:6px 12px; background:#2563eb; color:white; border-radius:4px; text-decoration:none;">
        &larr; {{ __('messages.back_to_list') }}
    </a>
</div>

<h2>{{ __('messages.favorite_movies') }}</h2>
<div class="movie-grid">
    @forelse($favorites as $fav)
    @php
    $poster = $fav->poster && $fav->poster !== 'N/A' ? $fav->poster : '/images/no-image.png';
    @endphp
    <div class="movie-card">
        <img data-src="{{ $poster }}" class="lazy" alt="{{ $fav->title }} Poster" style="width:100%; min-height:300px; object-fit:cover; background:#333;">
        <div class="info">
            <h4>{{ $fav->title }}</h4>
            <button onclick="removeFavorite('{{ $fav->id }}')">
                {{ __('messages.remove') }}
            </button>
        </div>
    </div>
    @empty
    <p>{{ __('messages.no_favorite_movies') }}</p>
    @endforelse
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function lazyLoadFavorites() {
        const lazyImages = document.querySelectorAll('.lazy');
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    observer.unobserve(img);
                }
            });
        });
        lazyImages.forEach(img => observer.observe(img));
    }

    lazyLoadFavorites();

    function removeFavorite(id) {
        $.ajax({
            url: '/favorites/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                location.reload();
            }
        });
    }
</script>
@endsection