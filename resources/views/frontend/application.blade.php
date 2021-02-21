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
    <link rel="shortcut icon" href="{{asset(env('APP_LOGO'))}}"/>
</head>
<body>
<noscript>
    <strong>We're sorry but Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>
<div id="app">
</div>

<script src="{{ asset(mix('frontend/js/app.js')) }}"></script>

</body>
</html>
