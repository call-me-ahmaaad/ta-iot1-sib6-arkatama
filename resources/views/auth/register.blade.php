@extends('layouts.loginRegis')
@section('container')
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf
        <div class="container" id="register">
            <h3>Register</h3>
            <div class="input name">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Input your name here">
                @error('name')
                    <p class="erorMsg">{{$message}}</p>
                @enderror
            </div>
            <div class="input email">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Input your email here">
                @error('email')
                    <p class="erorMsg">{{$message}}</p>
                @enderror
            </div>
            <div class="input password">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Input your password here">
                @error('password')
                    <p class="erorMsg">{{$message}}</p>
                @enderror
            </div>
            <div class="input re_password">
                <label for="password_confirmation">Re-input Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-input your password here">
                @error('password_confirmation')
                    <p class="erorMsg">{{$message}}</p>
                @enderror
            </div>
            <div class="navBtn">
                <a class="forgotPw" href="{{ route('login') }}">Already Registered?</a>
                <div class="actBtn">
                    <a href={{ url('/') }}>Back to welcome</a>
                    <button type="submit">Register</button>
                </div>
            </div>
        </div>
    </form>
@endsection
