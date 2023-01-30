<x-layout>
    <x-slot:title>
        Zaloguj się
    </x-slot>

    <div class="page-login">

        <div class="form-auth-card">

        <x-branding/>
        
        <h1 class="page-title">Logowanie</h1>
        <p class="no-account">
            Nie masz konta? <a href="{{ route('singup') }}">Załóż nowe konto</a>
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login_submit') }}"
              class="login">
            @csrf

            <div class="row">
                <label for="email">Adres e-mail</label>
                <input id="email" type="email" name="email" minlength="3" maxlength="128" required
                       value="{{ old('email') }}" class="@error('email') is-invalid @enderror">
            </div>

            <div class="row">
                <label for="password">Hasło</label>
                <input id="password" type="password" name="password" minlength="8" maxlength="128" required
                       class="@error('password') is-invalid @enderror">
            </div>

            <button type="submit" class="btn-submit">Zaloguj</button>
        </form>

        </div>

    </div>
</x-layout>
