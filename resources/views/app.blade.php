<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Registro Super Carnes</title>
</head>
<body class="bg-slate-100 min-h-screen">
  <div id="app"></div>
  <script>
    window.__APP_PAGE__ = @json($page ?? 'home');
    window.__APP_PROPS__ = @json($props ?? []);
  </script>
  <script type="module">
{!! file_get_contents(resource_path('js/spa.js')) !!}
  </script>
</body>
</html>
