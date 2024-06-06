@extends('layouts.loginRegis')
@section('container')
<header id="forgotPw">
    <img src={{URL::asset("/image/Bisco.jpg")}} alt="Biscuit">
    <p id="erorMsg">Eror message here :3</p>
</header>
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="container" id="forgotPw">
        <h3>Forgot Password</h3>
        <p class="info">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
        <div class="input email" id="forgotPw">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="Input your email here">
        </div>
        <div class="navBtn">
            <div class="actBtn" id="forgotPw">
                <a href={{ url('/') }}>Back to welcome</a>
                <button type="submit">Send to Email</button>
            </div>
        </div>
    </div>
</form>
@endsection
