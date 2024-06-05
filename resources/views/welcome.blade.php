<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href={{URL::asset("/css/before.css")}}>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="main">
        <h1 id="title">🏠 SMART HOME MONITORING 🧠</h1>
        <h3 id="subTitle">😊 ログインしてプロジェクトに参加します 😊</h3>
        @if (Route::has('login'))
            <div class="button">
                @auth
                    <a href={{ url('/dashboard') }} id="dashboard">Dashboard</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" id="logout">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                    <script>
                        function login() {
                            $('#title').text('😏 DESIGNED BY AHMAAAD 😎');
                            $('#subTitle').text('🙂 私のプロジェクトへようこそ! 🫡');
                        }

                        // Call login function on page load if authenticated
                        document.addEventListener('DOMContentLoaded', (event) => {
                            login();
                        });
                    </script>
                @else
                    <a href={{ route('login') }} id="login">Login</a>
                    @if (Route::has('register'))
                        <a href={{ route('register') }} id="register">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</body>
</html>
