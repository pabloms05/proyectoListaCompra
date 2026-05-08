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
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Barra superior */
        .navbar {
            width: 100%;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .navbar a {
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .navbar a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Ajustar contenido para que no quede debajo de la navbar */
        .main-content {
            padding-top: clamp(4rem, 10vh, 5rem);
            width: 100%;
        }

        @media (max-width: 640px) {
            .navbar {
                padding: 0.5rem 0.75rem;
            }
            .navbar a {
                font-size: 0.75rem;
                padding: 0.35rem 0.6rem;
            }
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
