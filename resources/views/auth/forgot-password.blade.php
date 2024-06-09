@extends('layouts.loginRegis')
@section('container')

{{-- Header Section: a place to display images. --}}
<header id="forgotPw">
    <img src="{{ URL::asset('/image/Bisco.jpg') }}" alt="Biscuit">
</header>

{{-- Forgot Password Form --}}
<form method="POST" action="{{ route('password.email') }}" novalidate>
    @csrf

    {{-- Container for storing elements in the form of labels, input, and buttons. --}}
    <div class="container" id="forgotPw">

        {{-- Title --}}
        <h3>Forgot Password</h3>

        {{-- Information --}}
        <p class="info">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>

        {{-- Input Email Section --}}
        <div class="input email">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Input your email here">
            @error('email')
                <p class="errorMsg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Navigation Button Section --}}
        <div class="navBtn">
            <div class="actBtn" id="forgotPw">
                <a href="{{ url('/') }}">Back to welcome</a>
                <button type="submit">Send to Email</button>
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
