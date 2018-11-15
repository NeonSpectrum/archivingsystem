<!DOCTYPE html>
<html lang="en">
<head>
  <base href="{{ url('/') . '/' }}">
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('public/css/packages.css') }}">
  <link rel="stylesheet" href="{{ asset('public/css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <title>Archiving System</title>
</head>
<body class="{{ Auth::user()->role ?? '' }}">
  @yield("body")

  <script src="{{ asset('public/js/packages.js') }}"></script>
  <script src="{{ asset('public/js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
  @yield("extra-scripts")
</body>
</html>
