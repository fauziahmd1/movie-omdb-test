<!DOCTYPE html>
<html lang="{{ session('locale', 'en') }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.app_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .lang-switch a {
            padding: 4px 8px;
            border-radius: 4px;
            text-decoration: none;
            color: #e5e7eb;
            margin-left: 5px;
            font-size: 14px;
        }

        .lang-switch a.active {
            background: #2563eb;
            color: white;
        }

        .lang-switch a:hover {
            background: #3b82f6;
            color: white;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('movies.index') }}" class="logo">🎬 {{ __('messages.app_name') }}</a>

            <div class="navbar-right">
                <span class="lang-switch">
                    <a href="{{ url('/lang/en') }}" class="{{ session('locale', 'en') == 'en' ? 'active' : '' }}">EN</a>
                    |
                    <a href="{{ url('/lang/id') }}" class="{{ session('locale') == 'id' ? 'active' : '' }}">ID</a>
                </span>

                @if(session('logged_in'))
                <form action="{{ route('logout') }}" method="GET" style="display:inline;">
                    <button type="submit" class="logout">{{ __('messages.logout') }}</button>
                </form>
                @else
                <a href="{{ route('login.show') }}" class="logout">{{ __('messages.login') }}</a>
                @endif
            </div>
        </div>
    </nav>

    <main class="container content">
        @yield('content')
    </main>
</body>

</html>