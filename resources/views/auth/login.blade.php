@extends('layouts.app')

@section('content')
<div class="login-box">
    <h2>{{ __('messages.login') }}</h2>

    <form method="POST" action="{{ route('login.perform') }}">
        @csrf
        <input type="text" name="username" placeholder="{{ __('messages.username') }}">
        <input type="password" name="password" placeholder="{{ __('messages.password') }}">
        <button type="submit">{{ __('messages.login') }}</button>
    </form>
</div>

@if ($errors->has('msg'))
<script>
    alert("{{ $errors->first('msg') }}");
</script>
@endif
@endsection