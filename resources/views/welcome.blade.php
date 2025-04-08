<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Seguro - Laravel</title>

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Estilos inline con colores agradables -->
    <style>
        /* Fondo general y tipografía */
        body {
            background-color: #f0f4f8;
            /* color azul muy claro para el fondo */
            color: #333;
            /* texto en gris oscuro */
            font-family: 'Instrument Sans', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            padding: 20px;
        }

        /* Contenedor principal */
        header {
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin-bottom: 40px;
            background-color: #ffffff;
            /* fondo blanco para el contenedor */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Título */
        h1 {
            margin-bottom: 20px;
            font-size: 2rem;
            color: #2c3e50;
        }

        /* Navegación y botones */
        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        a {
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        /* Botón Dashboard (sólo se muestra si el usuario está autenticado) */
        .btn-dashboard {
            display: inline-block;
            padding: 10px 25px;
            border: 1px solid #2c3e50;
            color: #2c3e50;
            border-radius: 4px;
            background-color: #ecf0f1;
        }

        .btn-dashboard:hover {
            background-color: #d0dce3;
            border-color: #34495e;
        }

        /* Botón Log in */
        .btn-login {
            display: inline-block;
            padding: 10px 25px;
            color: #ffffff;
            background-color: #3498db;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .btn-login:hover {
            background-color: #2980b9;
        }

        /* Botón Register */
        .btn-register {
            display: inline-block;
            padding: 10px 25px;
            color: #3498db;
            background-color: #ffffff;
            border: 1px solid #3498db;
            border-radius: 4px;
        }

        .btn-register:hover {
            background-color: #ecf0f1;
        }

        /* Sección extra de contenido */
        section {
            max-width: 600px;
            text-align: center;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Acceso Seguro</h1>
        <img src="images/caballo.jpg" alt="Foto de Caballo" style="max-width:100%; height:auto; border-radius:8px; margin-bottom:20px;">
        @if (Route::has('login'))
            <nav>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-dashboard">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-register">Register</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>
</body>

</html>
