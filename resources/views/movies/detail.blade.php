@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
    <div style="margin-top:20px; margin-bottom:20px;">
        <a href="{{ route('movies.index') }}"
            style="padding:6px 12px; background:#2563eb; color:white; border-radius:4px; text-decoration:none;">
            &larr; {{ __('messages.back_to_list') }}
        </a>
    </div>

    <div id="movie-detail" class="movie-detail-card"></div>
</div>

<style>
    .movie-detail-card {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    .movie-detail-inner {
        display: flex;
        background: #020617;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        max-width: 900px;
        width: 100%;
    }

    .movie-detail-inner .poster {
        flex: 0 0 300px;
        overflow: hidden;
    }

    .movie-detail-inner .poster img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        min-height: 300px;
        background: #333;
    }

    .movie-detail-inner .info {
        padding: 20px;
        flex: 1;
    }

    .movie-detail-inner h2 {
        margin-top: 0;
        margin-bottom: 15px;
    }

    .movie-detail-inner p {
        margin: 5px 0;
    }

    .favorite-btn {
        margin-top: 15px;
        padding: 10px 15px;
        background: #facc15;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        color: #0f172a;
        cursor: pointer;
        transition: 0.2s;
    }

    .favorite-btn:hover {
        background: #eab308;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let imdbID = "{{ $id }}";

    $.get("/movies-api/" + imdbID, function(movie) {
        const poster = movie.Poster && movie.Poster !== 'N/A' ? movie.Poster : '/images/no-image.png';

        $('#movie-detail').html(`
        <div class="movie-detail-inner">
            <div class="poster">
                <img data-src="${poster}" class="lazy" alt="${movie.Title} Poster">
            </div>
            <div class="info">
                <h2>${movie.Title}</h2>
                <p><strong>{{ __('messages.year') }}:</strong> ${movie.Year}</p>
                <p><strong>{{ __('messages.genre') }}:</strong> ${movie.Genre}</p>
                <p><strong>{{ __('messages.director') }}:</strong> ${movie.Director}</p>
                <p><strong>{{ __('messages.plot') }}:</strong> ${movie.Plot}</p>
                <button class="favorite-btn" onclick="addFavorite('${movie.imdbID}','${movie.Title}','${poster}')">
                    {{ __('messages.add_favorite') }}
                </button>
            </div>
        </div>
    `);

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
    });

    function addFavorite(id, title, poster) {
        $.post('/favorites', {
                imdbID: id,
                title: title,
                poster: poster
            })
            .done(res => alert(res.message || '{{ __("messages.added_to_favorite") }}'))
            .fail(err => {
                if (err.status === 409) alert('{{ __("messages.already_in_favorites") }}');
                else alert('{{ __("messages.failed_add_favorite") }}');
            });
    }
</script>
@endsection