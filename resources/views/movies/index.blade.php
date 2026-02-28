@extends('layouts.app')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>{{ __('messages.movie_list') }}</h2>
    <a href="{{ route('movies.favorites') }}" class="btn-favorites" style="padding:6px 12px; background:#2563eb; color:white; border-radius:4px; text-decoration:none;">
        {{ __('messages.my_favorites') }}
    </a>
</div>

<input type="text" id="search" placeholder="{{ __('messages.search_placeholder') }}">
<div class="movie-grid" id="movies"></div>
<div id="loading" style="text-align:center; display:none;">Loading...</div>

<style>
    .movie-card img {
        width: 100%;
        min-height: 300px;
        object-fit: cover;
        background: #333;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let page = 1;
    let search = '';
    let loading = false;

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                observer.unobserve(img);
            }
        });
    });

    function loadMovies() {
        if (loading) return;
        loading = true;
        $('#loading').show();

        $.get("/movies-api", {
            s: search,
            page: page
        }, function(data) {
            if (!data.Search) {
                if (page == 1) $('#movies').html(`<p>{{ __('messages.no_movies_found') }}</p>`);
                $('#loading').hide();
                loading = false;
                return;
            }

            data.Search.forEach(movie => {
                const poster = movie.Poster && movie.Poster !== 'N/A' ? movie.Poster : '/images/no-image.png';

                const movieCard = $(`
            <div class="movie-card">
                <img data-src="${poster}" class="lazy" alt="${movie.Title} Poster">
                <div class="info">
                    <h4>${movie.Title}</h4>
                    <p>${movie.Year}</p>
                    <a href="/movies/${movie.imdbID}" class="detail-btn">Detail</a>
                    <button onclick="addFavorite('${movie.imdbID}','${movie.Title}','${poster}')">
                        {{ __('messages.add_favorite') }}
                    </button>
                </div>
            </div>
            `);

                $('#movies').append(movieCard);

                movieCard.find('img.lazy').each((i, img) => {
                    observer.observe(img);
                });
            });

            page++;
            loading = false;
            $('#loading').hide();
        });
    }

    $('#search').on('keypress', function(e) {
        if (e.which == 13) {
            search = $(this).val();
            page = 1;
            $('#movies').html('');
            loadMovies();
        }
    });

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadMovies();
        }
    });

    $(document).ready(function() {
        loadMovies();
    });

    function addFavorite(id, title, poster) {
        $.post('/favorites', {
                _token: '{{ csrf_token() }}',
                imdbID: id,
                title: title,
                poster: poster
            })
            .done(function(res) {
                alert(res.message || '{{ __("messages.added_to_favorite") }}');
            })
            .fail(function(err) {
                if (err.status === 409) {
                    alert('{{ __("messages.already_in_favorites") }}');
                } else {
                    alert('{{ __("messages.failed_add_favorite") }}');
                }
            });
    }
</script>
@endsection