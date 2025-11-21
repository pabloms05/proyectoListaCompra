<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,600,700&display=swap" rel="stylesheet" />


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Barra superior */
        .navbar {
            width: 100%;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            padding: 0.8rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .navbar a {
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Ajustar contenido para que no quede debajo de la navbar */
        .main-content {
            padding-top: 5rem;
            /* altura aproximada de la navbar */
            width: 100%;
        }
    </style>
</head>

<body class="font-sans antialiased" style="background: linear-gradient(135deg, #4e4376, #2b5876); min-height:100vh;">

    <!-- Navbar superior -->
    <nav class="navbar">
        <div class="flex items-center justify-between w-full">
            {{-- Slot para contenido del navbar desde la vista --}}
            {{ $navbar ?? '' }}
        </div>
    </nav>

    <div class="min-h-screen flex flex-col items-center justify-center main-content">
        <div class="w-full">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
