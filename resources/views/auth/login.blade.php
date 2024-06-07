@extends('layouts.loginRegis')
@section('container')
    <header>
        <img src={{URL::asset("/image/Biscuit.jpg")}} alt="Biscuit">
    </header>
    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf
        <div class="container">
            <h3>Login</h3>
            <div class="input email">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Input your email here">
                @error('email')
                    <p class="erorMsg">{{$message}}</p>
                @enderror
            </div>
            <div class="input password">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Input your password here">
                @error('password')
                    <p class="erorMsg">{{$message}}</p>
                @enderror
            </div>
            <div class="input remember_me">
                <label for="remember_me">
                    <input id="remember_me" type="checkbox" class="remember_me" name="remember">
                    <span class="remember_me_span">Remember me</span>
                </label>
            </div>
            <div class="navBtn">
                @if (Route::has('password.request'))
                    <a class="forgotPw" href="{{ route('password.request') }}">Forgot your password?</a>
                @endif
                <div class="actBtn">
                    <a href={{ url('/') }}>Back to welcome</a>
                    <button type="submit">Login</button>
                </div>
            </div>
        </div>
    </form>
@endsection
