  <script>const base_url="{{ url('/') }}"</script>
  <script src="{{ asset('public/js/app.js') }}"></script>
  <script src="{{ asset('public/js/materialize.min.js') }}"></script>
  <script src="{{ asset('public/js/datatables.min.js') }}"></script>
  <script src="{{ asset('public/js/underscore-min.js') }}"></script>
  <script src="{{ asset('public/js/moment.min.js') }}"></script>
  <script src="{{ asset('public/js/script.js') }}?v={{ filemtime(public_path('js/script.js')) }}"></script>
</body>
</html>
