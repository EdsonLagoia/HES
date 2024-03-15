<header>
    <div class="icon-menu">
        <i class="fa-solid fa-bars" id="btn-open-close"></i>
    </div>
</header>

<div class="menu-side" id="menu-side">
    <a href="/" class="name-page">
        <img src="/img/brasao.png" alt="Brasão do Estado" class="logo">
        <h4>HES</h4>
    </a>

    <div class="option-menu">
        @foreach($menu as $menu)
            <a href="/{{ $menu->module }}" class="{{ strpos(Request::url(), $menu->module)  ? 'selected' : '' }}">
                <div class="option">
                    <i class="{{ $menu->icon }}" title="{{ $menu->title }}"></i>
                    <h4>{{ $menu->title }}</h4>
                </div>
            </a>
        @endforeach

        <form action="/logout" method="post">
            @csrf
            <button type="submit" id="logout">
                <div class="option">
                    <i class="fa-solid fa-right-from-bracket" title="Sair"></i>
                    <h4>Sair</h4>
                </div>
            </button>
        </form>
    </div>
</div>
