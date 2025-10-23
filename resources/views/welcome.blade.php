<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lista de Compras</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=poppins:400,600,700&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* --- BASE --- */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #4e4376, #2b5876);
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      text-align: center;
      overflow-x: hidden;
    }

    h1 {
      font-size: 3.2em;
      margin-bottom: 0.4em;
      text-shadow: 0 3px 12px rgba(0,0,0,0.4);
    }

    p {
      font-size: 1.2em;
      max-width: 650px;
      opacity: 0.9;
      line-height: 1.6;
      margin-bottom: 1.5em;
      margin-left: auto;    /* Añadir esto */
      margin-right: auto;   /* Añadir esto */
      text-align: center;   /* Añadir esto */
    }

    /* --- HEADER --- */
    header {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.2em 2em;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255,255,255,0.1);
      z-index: 100;
    }

    header h2 {
      font-size: 1.5em;
      background: linear-gradient(to right, #ffde59, #ffffff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      margin-left: 1.2em;
      padding: 0.5em 1.2em;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-login {
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.3);
    }
    .btn-login:hover {
      background: rgba(255,255,255,0.25);
    }

    .btn-register {
      background: linear-gradient(90deg, #ffde59, #ffb84d);
      color: #2b2b2b;
    }
    .btn-register:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }

    .btn-logout {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        font-weight: 600;
        font-family: inherit;
        font-size: inherit;
        padding: 0.5em 1.2em;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-left: 1.2em;
    }
    
    .btn-logout:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
    }

    /* --- BOX CENTRAL --- */
    .box {
      background: rgba(255, 255, 255, 0.1);
      padding: 3em 4em;
      border-radius: 25px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      backdrop-filter: blur(8px);
      margin-top: 4em;
      animation: fadeIn 1.2s ease-out;
    }

    /* --- BOTONES DE ACCIÓN --- */
    .actions {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 2em;
      margin-top: 2em;
    }

    .action-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 18px;
      padding: 1.8em 2.5em;
      width: 240px;
      color: #fff;
      box-shadow: 0 8px 25px rgba(0,0,0,0.25);
      text-decoration: none;
      transition: all 0.35s ease;
      position: relative;
      overflow: hidden;
    }

    .action-btn:hover {
      transform: translateY(-5px) scale(1.03);
      background: rgba(255, 255, 255, 0.25);
      box-shadow: 0 10px 35px rgba(0,0,0,0.35);
    }

    .action-btn svg {
      width: 50px;
      height: 50px;
      margin-bottom: 1em;
      color: #ffde59;
    }

    .action-btn span {
      font-size: 1.2em;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    /* --- FOOTER --- */
    footer {
      position: absolute;
      bottom: 20px;
      font-size: 0.9em;
      opacity: 0.7;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.4);
    }

    /* --- ANIMACIÓN --- */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>
  <!-- HEADER -->
  <header>
    <h2>🛒 Listas de la Compra</h2>
    <nav>
      @if (Route::has('login'))
        @auth
          <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout">
              Cerrar sesión
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn-login">Iniciar sesión</a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
          @endif
        @endauth
      @endif
    </nav>
  </header>

  <!-- CONTENIDO CENTRAL -->
  <div class="box">
    <h1>✨ Bienvenido a tus Listas de la Compra</h1>
    <p>Organiza tus compras de manera inteligente, crea listas personales o colabora con familia y amigos.  
    Todo desde una interfaz elegante y moderna.</p>

    <div class="actions">
      @auth
        <a href="{{ url('/mis-listas') }}" class="action-btn">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M9 12h6m2 4H7m10-8H7m2-4h6a2 2 0 012 2v12a2 2 0 01-2 2H9a2 2 0 01-2-2V6a2 2 0 012-2z"/>
          </svg>
          <span>Mis Listas</span>
        </a>

        <a href="{{ url('/listas-compartidas') }}" class="action-btn">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a9 9 0 00-9 9h18a9 9 0 00-9-9z"/>
          </svg>
          <span>Listas Compartidas</span>
        </a>
      @else
        <a href="{{ route('login') }}" class="action-btn">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M9 12h6m2 4H7m10-8H7m2-4h6a2 2 0 012 2v12a2 2 0 01-2 2H9a2 2 0 01-2-2V6a2 2 0 012-2z"/>
          </svg>
          <span>Mis Listas</span>
        </a>

        <a href="{{ route('login') }}" class="action-btn">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a9 9 0 00-9 9h18a9 9 0 00-9-9z"/>
          </svg>
          <span>Listas Compartidas</span>
        </a>
      @endauth
    </div>
  </div>

  <footer>
    © {{ date('Y') }} Lista de Compras — Diseñado con 💜 y Apache.
  </footer>
</body>
</html>
