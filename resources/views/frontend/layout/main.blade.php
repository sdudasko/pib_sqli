<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>SQL Injector</title>
    @yield('page-css')
</head>
<body>

@yield('content')

{{--@include('frontend.layout.partials.footer')--}}
<script src="{{ mixer('js/app.js') }}"></script>
@yield('bottomScripts')
</body>

</html>
