<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="icon" href="<%= BASE_URL %>favicon.ico"> -->

    <title>{{ config('app.name', 'LaraJS') }}</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset(mix('frontend/css/app.css')) }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/logo-tanmnt.png')}}"/>
</head>
<body>
<noscript>
    <strong>We're sorry but Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>
<div id="app">
</div>

<!-- Load polyfills to support older browsers -->
<script src="//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver" crossorigin="anonymous"></script>
<script src="{{ mix('frontend/js/manifest.js') }}"></script>
<script src="{{ mix('frontend/js/vendor.js')}}"></script>
<script src="{{ mix('frontend/js/app.js')}}"></script>
</body>
</html>
