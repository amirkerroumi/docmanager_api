<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>DocManager</title>
        <link rel="icon" href="/pics/dm-icon.png">
        <link rel="stylesheet" href="/css/bootstrap.min.css" >
        {{--<link href="/css/app.css" rel="stylesheet">--}}
        <link rel="stylesheet" href="/css/dm.css">


    </head>

    <body>
        @yield('header')
        @yield('content')
        @yield('footer')
    </body>

    {{--<script src="/js/app.js"></script>--}}
    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/dm.js"></script>
</html>