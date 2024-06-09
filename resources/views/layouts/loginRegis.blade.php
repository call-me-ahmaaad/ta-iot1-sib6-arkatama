<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Title Page --}}
    <title>{{ $title }}</title>

    {{-- CSS (loginRegis.css) --}}
    <link rel="stylesheet" href={{URL::asset("/css/loginRegis.css")}}>

    {{-- Favicon --}}
    <link rel="shortcut icon" href={{URL::asset("/image/favicon/loginRegis.ico")}} type="image/x-icon">
</head>
<body>
    {{--
        Container:
        The main area of ​​the login and register feature contains forms for login, register and forget password.
    --}}
    @yield('container')
</body>
</html>
