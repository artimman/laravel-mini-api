<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - eSprzedaż</title>
    <!-- Stylesheet-->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    @yield('stylesheet')

</head>
<body>
    <div class="bg-gray-100 flex justify-center items-center h-screen dbm-content">
        @yield('content')
    </div>
    <!-- JavaScript -->
    @yield('javascript')

</body>
</html>
