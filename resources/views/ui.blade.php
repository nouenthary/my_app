<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="access-token" content="{{auth()->user()->tokens()->first()->token}}" />

    <title>K STOCK</title>
    <link rel="shortcut icon" href="/uploads/icon.png"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons">

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">

    <style>
        body{
            margin: 0;
        }
    </style>
</head>
<body>
<div id="app">
    <audio id="water_droplet" >
        <source src="{{URL::asset('sounds/water_droplet.mp3')}}" type="audio/mpeg"></source>
    </audio>

    <audio id="camera_flashing_2" >
        <source src="{{URL::asset('sounds/camera_flashing_2.mp3')}}" type="audio/mpeg"></source>
    </audio>

    <audio id="door_bell" >
        <source src="{{URL::asset('sounds/door_bell.mp3')}}" type="audio/mpeg"></source>
    </audio>

    <app-component></app-component>
</div>
<script defer  src="{{ mix('/js/app.js') }}"></script>
<script>
    const thary = 'app';
</script>
</body>
</html>
