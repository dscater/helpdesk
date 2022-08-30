<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notificación</title>
</head>

<body>
    <p><b>Por:</b> {{ $usuario }}</p>
    <p><b>Asunto:</b> {{ $asunto }}</p>
    <p><b>Descripción:</b> {{ $descripcion }}</p>
</body>

</html>
