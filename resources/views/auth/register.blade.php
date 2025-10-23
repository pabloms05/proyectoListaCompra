<x-guest-layout>
    <style>
      /* Misma estética que welcome (solo para register) */
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
        margin: 3rem auto;
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

      .small-link{ color: rgba(255,255,255,0.92); text-decoration: underline; font-size:.92rem; }

      footer.auth-foot { text-align:center; color: rgba(255,255,255,0.75); margin-top:1rem; font-size:.9rem; }

      @keyframes fadeIn{ from{ opacity:0; transform:translateY(10px);} to{ opacity:1; transform:translateY(0);} }
      @media (max-width:560px){ .box{ padding:1.4rem; margin:1rem; } .row{ flex-direction:column-reverse; gap:.6rem; } }
    </style>

    <div class="box" role="main" aria-labelledby="register-title">
      <h1 id="register-title">Crear cuenta</h1>
      <p class="lead">Regístrate para crear y compartir tus listas de compra.</p>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" class="sr-only" />
            <x-text-input id="name" class="input-styled" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nombre" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div style="margin-top:.8rem;">
            <x-input-label for="email" :value="__('Email')" class="sr-only" />
            <x-text-input id="email" class="input-styled" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div style="margin-top:.8rem;">
            <x-input-label for="password" :value="__('Contraseña')" class="sr-only" />
            <x-text-input id="password" class="input-styled" type="password" name="password" required autocomplete="new-password" placeholder="Contraseña" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div style="margin-top:.8rem;">
            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" class="sr-only" />
            <x-text-input id="password_confirmation" class="input-styled" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repite la contraseña" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="row" style="margin-top:.9rem;">
            <a class="small-link" href="{{ route('login') }}">¿Ya tienes cuenta? Iniciar sesión</a>

            <x-primary-button class="primary-btn">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
      </form>

      <footer class="auth-foot">© {{ date('Y') }} Lista de Compras — Diseñado con 💜</footer>
    </div>
</x-guest-layout>
