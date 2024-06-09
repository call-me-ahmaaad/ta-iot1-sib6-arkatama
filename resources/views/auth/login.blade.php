@extends('layouts.loginRegis')
@section('container')

    {{-- Header Section: a place to display images. --}}
    <header>
        <img src="{{ URL::asset('/image/Biscuit.jpg') }}" alt="Biscuit" title="Biscuit-chan">
    </header>

    {{-- Login Form --}}
    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- Container for storing elements in the form of labels, input, and buttons. --}}
        <div class="container">

            {{-- Title --}}
            <h3>Login</h3>

            {{-- Input Email Section --}}
            <div class="input email">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Input your email here">
                @error('email')
                    <p class="errorMsg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Password Section --}}
            <div class="input password">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Input your password here">
                @error('password')
                    <p class="errorMsg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Remember Me Section --}}
            <div class="input remember_me">
                <label for="remember_me">
                    <input id="remember_me" type="checkbox" class="remember_me" name="remember">
                    <span class="remember_me_span">Remember me</span>
                </label>
            </div>

            {{-- Navigation Button Section --}}
            <div class="navBtn">

                {{-- Forgot Password Button --}}
                @if (Route::has('password.request'))
                    <a class="forgotPw" href="{{ route('password.request') }}">Forgot your password?</a>
                @endif

                {{-- Submit and Back to Welcome Button --}}
                <div class="actBtn">
                    <a href="{{ url('/') }}">Back to welcome</a>
                    <button type="submit">Login</button>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const inputs = document.querySelectorAll('.input input');

                    // Function to check if input has value
                    function checkValue(input) {
                        if (input.value.trim() !== '') {
                            input.classList.add('has-value');
                        } else {
                            input.classList.remove('has-value');
                        }
                    }

                    // Initial check when page loads for each input
                    inputs.forEach(input => checkValue(input));

                    // Add event listeners to each input
                    inputs.forEach(input => {
                        input.addEventListener('input', () => checkValue(input));
                        input.addEventListener('change', () => checkValue(input));
                    });
                });
            </script>
        </div>
    </form>
@endsection
