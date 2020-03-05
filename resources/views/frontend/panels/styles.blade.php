        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600">
        <link rel="stylesheet" href="{{ asset(mix('frontend/vendors/css/vendors.min.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('frontend/vendors/css/ui/prism.min.css')) }}">
        {{-- Vendor Styles --}}
        @yield('vendor-style')
        {{-- Theme Styles --}}
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/bootstrap.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/bootstrap-extended.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/colors.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/components.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/themes/dark-layout.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/themes/semi-dark-layout.css')) }}">
{{-- {!! FrontendHelper::applClasses() !!} --}}
@php
$configData = FrontendHelper::applClasses();
@endphp

{{-- Layout Styles works when don't use customizer --}}

{{-- @if($configData['theme'] == 'dark-layout')
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/themes/dark-layout.css')) }}">
@endif
@if($configData['theme'] == 'semi-dark-layout')
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/themes/semi-dark-layout.css')) }}">
@endif --}}
{{-- Page Styles --}}
@if($configData['mainLayoutType'] === 'horizontal')
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/core/menu/menu-types/horizontal-menu.css')) }}">
@endif
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/core/menu/menu-types/vertical-menu.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/core/colors/palette-gradient.css')) }}">
{{-- Page Styles --}}
        @yield('page-style')
{{-- Laravel Style --}}
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/custom-laravel.css')) }}">
{{-- Custom RTL Styles --}}
@if($configData['direction'] === 'rtl')
        <link rel="stylesheet" href="{{ asset(mix('frontend/css/custom-rtl.css')) }}">
@endif
{{--        Tailwind css--}}
{{--        <link rel="stylesheet" href="{{ asset(mix('frontend/css/tailwind.css')) }}">--}}

