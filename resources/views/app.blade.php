<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Concejo Municipal de Potosí</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
