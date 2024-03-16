<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
    <title>Document</title>
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">

        @include('layouts.header')
        @include('layouts.sidebar')
        
        @yield('content')
    </div>
    @include ('layouts.script')
    @yield('scripts')
    @include('layouts.script2')
</body>
</html>