<!DOCTYPE html>
<html lang="en">
<head>
  <base href="{{ url('/') . '/' }}">
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('public/css/materialize.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <title>Archiving System</title>
</head>
<body class="{{ Auth::user()->role ?? '' }}">
  @yield("body")

  <script src="{{ asset('public/js/app.js') }}"></script>
  <script src="{{ asset('public/js/materialize.min.js') }}"></script>
  <script src="{{ asset('public/js/datatables.min.js') }}"></script>
  <script src="{{ asset('public/js/underscore-min.js') }}"></script>
  <script src="{{ asset('public/js/moment.min.js') }}"></script>
  <script src="{{ asset('public/js/dt-custom.js') }}"></script>
  <script src="{{ asset('public/js/script.js') }}?v={{ filemtime(public_path('js/script.js')) }}"></script>
  @yield("extra-scripts")
</body>
</html>
