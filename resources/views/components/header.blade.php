<header>
    <x-branding/>
    <nav class="account">
        @if(!Auth::check())
            <a href="{{ route('login') }}">Zaloguj się</a>
        @else
            <a href="{{ route('library') }}" class="btn-library"
               title="Przejdż do twojej biblioteki wideo">Biblioteka</a>
            <div class="user"
                 title="Zalogowano na użytkownika"><i class="fa-solid fa-circle-user"></i><span class="username">{{ Auth::user()->email }}</span></div>
            <a href="{{ route('logout') }}" title="Wyloguj" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i></a>
        @endif
    </nav>
</header>
