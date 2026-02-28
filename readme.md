OMDb Movie App
Aplikasi Laravel 5 untuk menampilkan daftar film dari OMDb API, detail film, dan fitur favorit.

Fitur

1. Login wajib (Username: aldmic, Password: 123abc123)
2. Daftar Film dengan Infinite Scroll
3. Detail Film lengkap dengan plot, genre, sutradara, dan poster
4. Tambah / hapus Favorite Movie
5. Multi Language (English / Indonesia) untuk teks statis
6. Pencarian film dan empty layout handling
7. Lazy Load poster film

Library & Tools

1. Laravel 5
2. GuzzleHttp (request OMDb API)
3. jQuery (AJAX & DOM)
4. CSS Grid & Flexbox (layout responsive)
5. IntersectionObserver API (lazy load gambar)

Cara Menjalankan

1. Clone atau unzip project
2. composer install
3. Copy file .env.example menjadi .env
4. Masukkan OMDb API key anda di .env
5. Generate Laravel app key
6. Setup database "movie_omdb_test" dan buat table "favorites"
7. Jalankan server
8. Akses aplikasi: http://localhost:8000/login

Demo
Link Demo Online
– masukkan link server 000webhost atau hosting lain
