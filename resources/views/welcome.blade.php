<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Listas de la Compra</title>
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
      font-size: clamp(1.8em, 5vw, 3.2em);
      margin-bottom: 0.4em;
      text-shadow: 0 3px 12px rgba(0,0,0,0.4);
      padding: 0 1rem;
    }

    p {
      font-size: clamp(0.9em, 2.5vw, 1.2em);
      max-width: 650px;
      opacity: 0.9;
      line-height: 1.6;
      margin-bottom: 1.5em;
      margin-left: auto;
      margin-right: auto;
      text-align: center;
      padding: 0 1.5rem;
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
      padding: 0.8rem 1rem;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255,255,255,0.1);
      z-index: 100;
      flex-wrap: wrap;
      gap: 0.5rem;
    }

    header h2 {
      font-size: clamp(1rem, 3vw, 1.5em);
      background: linear-gradient(to right, #ffde59, #ffffff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    nav {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      justify-content: flex-end;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      margin-left: 0;
      padding: 0.5em 1em;
      border-radius: 8px;
      transition: all 0.3s ease;
      font-size: clamp(0.8rem, 2vw, 1rem);
      white-space: nowrap;
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

    /* Menú desplegable de usuario */
    .user-menu {
        position: relative;
    }
    
    .user-menu-btn {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        font-weight: 600;
        font-family: inherit;
        font-size: clamp(0.8rem, 2vw, 1rem);
        padding: 0.5em 1em;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-left: 0;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .user-menu-btn:hover {
        background: rgba(255,255,255,0.25);
    }
    
    .user-menu-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        background: rgba(30, 30, 50, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 12px;
        padding: 1rem;
        min-width: 250px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        z-index: 1000;
    }
    
    .user-menu.active .user-menu-dropdown {
        display: block;
        animation: fadeIn 0.2s ease-out;
    }
    
    .user-info {
        padding-bottom: 0.75rem;
        margin-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    
    .user-name {
        font-weight: 700;
        font-size: 1rem;
        color: #fff;
        margin-bottom: 0.25rem;
    }
    
    .user-email {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.7);
        word-break: break-all;
    }
    
    .btn-logout {
        background: linear-gradient(90deg, #ef4444, #dc2626);
        border: none;
        color: #fff;
        font-weight: 600;
        font-family: inherit;
        font-size: 0.9rem;
        padding: 0.6em 1em;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        white-space: nowrap;
    }
    
    .btn-logout:hover {
        background: linear-gradient(90deg, #dc2626, #b91c1c);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
    
    .menu-arrow {
        transition: transform 0.3s ease;
    }
    
    .user-menu.active .menu-arrow {
        transform: rotate(180deg);
    }

    /* --- BOX CENTRAL --- */
    .box {
      background: rgba(255, 255, 255, 0.1);
      padding: clamp(1.5rem, 4vw, 3em) clamp(1rem, 5vw, 4em);
      border-radius: 25px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      backdrop-filter: blur(8px);
      margin-top: clamp(6rem, 10vh, 4em);
      margin-left: 1rem;
      margin-right: 1rem;
      animation: fadeIn 1.2s ease-out;
      max-width: 95%;
      width: auto;
    }

    /* --- BOTONES DE ACCIÓN --- */
    .actions {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 1rem;
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
      padding: clamp(1rem, 3vw, 1.8em) clamp(1.2rem, 4vw, 2.5em);
      width: clamp(140px, 45vw, 240px);
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
      width: clamp(35px, 8vw, 50px);
      height: clamp(35px, 8vw, 50px);
      margin-bottom: 0.8em;
      color: #ffde59;
    }

    .action-btn span {
      font-size: clamp(0.9em, 2.5vw, 1.2em);
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    /* --- FOOTER --- */
    footer {
      position: relative;
      margin-top: 2rem;
      padding: 1rem;
      font-size: clamp(0.75rem, 2vw, 0.9em);
      opacity: 0.7;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.4);
    }

    @media (max-width: 640px) {
      header {
        padding: 0.6rem 0.8rem;
      }
      .actions {
        gap: 0.8rem;
      }
      .box {
        margin-top: 5rem;
      }
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
          <div class="user-menu" id="userMenu">
            <button type="button" class="user-menu-btn" onclick="toggleUserMenu()">
              <span>Menú</span>
              <svg class="menu-arrow" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
              </svg>
            </button>
            <div class="user-menu-dropdown">
              <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-email">{{ Auth::user()->email }}</div>
              </div>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                  🚪 Cerrar sesión
                </button>
              </form>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}" class="btn-login">Iniciar sesión</a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
          @endif
        @endauth
      @endif
    </nav>
  </header>
  
  <script>
    function toggleUserMenu() {
      const menu = document.getElementById('userMenu');
      menu.classList.toggle('active');
    }
    
    // Cerrar el menú al hacer clic fuera
    document.addEventListener('click', function(event) {
      const menu = document.getElementById('userMenu');
      if (menu && !menu.contains(event.target)) {
        menu.classList.remove('active');
      }
    });
  </script>

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
    © {{ date('Y') }} Listas de la Compra — Alejandro y Pablo.
  </footer>
</body>
</html>
