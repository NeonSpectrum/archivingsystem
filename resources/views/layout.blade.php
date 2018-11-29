<!DOCTYPE html>
<html lang="en">
<head>
  <base href="{{ url('/') . '/' }}">
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/packages.css') }}?v={{ filemtime(public_path('css/packages.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <title>Archiving System</title>
</head>
<body class="{{ Auth::user()->role->name ?? '' }}">
  @yield("body")

  <script src="{{ asset('js/packages.js') }}?v={{ filemtime(public_path('js/packages.js')) }}"></script>
  <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
  @yield("extra-scripts")
</body>
</html>
