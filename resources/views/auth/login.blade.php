<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link rel="stylesheet" href={{URL::asset("/css/login.css")}}>
</head>
<body>
    <header>
        <img src={{URL::asset("/image/Biscuit.jpg")}} alt="Biscuit">
        <p id="erorMsg">Eror message here :3</p>
    </header>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="container">
            <h3>Login</h3>
            <div class="input email">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Input your email here">
            </div>
            <div class="input password">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Input your password here">
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
</body>
</html>
