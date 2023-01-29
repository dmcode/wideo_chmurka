<x-layout>
    <x-slot:title>
        Utwórz nowe konto
    </x-slot>

    <div class="page-singup">

        <div class="form-auth-card">

        <x-branding/>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('singup_submit') }}"
              class="login singup">
            @csrf

            <div class="row">
                <label for="name">Nazwa użytkownika</label>
                <input id="name" type="text" name="name" minlength="2" maxlength="30" required
                       value="{{ old('name') }}" class="@error('name') is-invalid @enderror">
            </div>

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

            <div class="row">
                <label for="password_confirmation">Powtórz hasło</label>
                <input id="password_confirmation" type="password" name="password_confirmation" minlength="8" maxlength="128" required
                       class="@error('password') is-invalid @enderror">
            </div>

            <button type="submit" class="btn-submit">Załóż konto</button>
        </form>

        </div>

    </div>
</x-layout>
