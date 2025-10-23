<x-guest-layout>
    <style>
      /* Copia de la estética del welcome (solo para login) */
      body {
        background: linear-gradient(135deg, #4e4376, #2b5876) !important;
        color: #fff;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        margin: 0;
        background-attachment: fixed;
      }

      .box {
        background: rgba(255,255,255,0.08);
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 12px 36px rgba(0,0,0,0.35);
        backdrop-filter: blur(8px);
        max-width: 520px;
        margin: 2rem auto;
        color: #fff;
        animation: fadeIn .9s ease-out;
      }

      .box h1 { font-size: 1.6rem; margin: 0 0 .6rem 0; font-weight:700; text-align:center; }
      .box p.lead { color: rgba(255,255,255,0.9); text-align:center; margin:0 0 1rem 0; }

      .input-styled {
        width: 100%;
        padding: .68rem .9rem;
        border-radius: 10px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        color: #fff;
        margin-top: .5rem;
        box-sizing: border-box;
      }
      .input-styled::placeholder{ color: rgba(255,255,255,0.62); }
      .input-styled:focus{ outline:none; border-color:#ffde59; box-shadow:0 6px 20px rgba(255,222,89,0.08); }

      .row { display:flex; align-items:center; justify-content:space-between; gap:.8rem; margin-top:.9rem; }
      .primary-btn {
        background: linear-gradient(90deg,#ffde59,#ffb84d);
        color: #1f1f1f;
        font-weight: 700;
        border: none;
        padding: .66rem 1.1rem;
        border-radius: 10px;
        cursor: pointer;
      }

      .google-btn{
        display:inline-flex; align-items:center; gap:.6rem;
        background:#fff; color:#222; border-radius:10px; padding:.55rem .8rem; font-weight:600;
        box-shadow:0 6px 18px rgba(0,0,0,0.12); text-decoration:none;
      }
      .google-btn img{ width:18px; height:18px; }

      .small-link{ color: rgba(255,255,255,0.92); text-decoration: underline; font-size:.92rem; }

      footer.auth-foot { text-align:center; color: rgba(255,255,255,0.75); margin-top:1rem; font-size:.9rem; }

      @keyframes fadeIn{ from{ opacity:0; transform:translateY(10px);} to{ opacity:1; transform:translateY(0);} }
      @media (max-width:560px){ .box{ padding:1.4rem; margin:1rem; } .row{ flex-direction:column-reverse; gap:.6rem; } }
    </style>

    <div class="box" role="main" aria-labelledby="login-title">
      <h1 id="login-title">Iniciar sesión</h1>
      <p class="lead">Accede para gestionar tus listas de compra personales y compartidas.</p>

      <!-- Session Status -->
      <x-auth-session-status class="mb-4" :status="session('status')" />

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
          <x-input-label for="email" :value="__('Email')" class="sr-only" />
          <x-text-input id="email" class="input-styled" type="email" name="email" :value="old('email')" required
              autofocus autocomplete="username" placeholder="Email" />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div style="margin-top:.8rem;">
          <x-input-label for="password" :value="__('Password')" class="sr-only" />
          <x-text-input id="password" class="input-styled" type="password" name="password" required
              autocomplete="current-password" placeholder="Contraseña" />
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="row">
          <label style="display:flex;align-items:center;gap:.5rem;font-weight:600;">
            <input id="remember_me" type="checkbox" name="remember" style="width:16px;height:16px;border-radius:4px;">
            <span style="color:rgba(255,255,255,0.9);font-size:.94rem;">Recordarme</span>
          </label>

          <div style="display:flex;gap:.6rem;align-items:center;">
            @if (Route::has('password.request'))
              <a class="small-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif

            <x-primary-button class="primary-btn">
              {{ __('Log in') }}
            </x-primary-button>
          </div>
        </div>

        <!-- Separador -->
        <div style="margin: 1.5rem 0; text-align: center; color: rgba(255,255,255,0.6);">
          ¿No tienes cuenta aún?
        </div>

        <!-- Botón de registro -->
        <div style="display:flex; justify-content:center;">
          <a href="{{ route('register') }}" class="primary-btn" style="text-decoration:none; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
            Crear cuenta nueva
          </a>
        </div>

        <!-- Boton Inicio Sesion Google -->
        <div style="margin-top:1.5rem; display:flex; gap:.7rem; justify-content:center;">
          <a href="{{ route('google.login') }}" class="google-btn" aria-label="Iniciar sesión con Google">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google">
            <span>Iniciar con Google</span>
          </a>
        </div>
      </form>

      <footer class="auth-foot">© {{ date('Y') }} Lista de Compras — Diseñado con 💜</footer>
    </div>
</x-guest-layout>