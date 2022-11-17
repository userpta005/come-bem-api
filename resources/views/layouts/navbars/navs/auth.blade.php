<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <div class="navbar-toggle d-inline">
        <button type="button"
          class="navbar-toggler">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </button>
      </div>
    </div>
    <button class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navigation"
      aria-expanded="false"
      aria-label="{{ __('Toggle navigation') }}">
      <span class="navbar-toggler-bar navbar-kebab"></span>
      <span class="navbar-toggler-bar navbar-kebab"></span>
      <span class="navbar-toggler-bar navbar-kebab"></span>
    </button>
    <div class="collapse navbar-collapse"
      id="navigation">
      <ul class="navbar-nav ml-auto d-flex align-items-center">
        @if (session()->exists('stores'))
          <li class="dropdown nav-item text-dark ">
            <a href="#"
              class="dropdown-toggle nav-link"
              data-toggle="dropdown">
              <span class="group-title">
                <b class="font-weight-bold text-dark">{{ session('store')['name'] }}</b>
                <i class="fas fa-caret-down align-baseline" style="font-size: 13px"></i>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
              @foreach (session('stores') as $store)
                <li class="nav-link text-dark">
                  <a href="{{ route('change.store', $store['id']) }}"
                    class="nav-item dropdown-item btn-store"
                    data-id="{{ $store['id'] }}">
                    {{ $store['name'] }}
                  </a>
                </li>
              @endforeach
            </ul>

          </li>
        @endif
        <li class="dropdown nav-item">
          <a href="#"
            class="dropdown-toggle nav-link"
            data-toggle="dropdown">
            <div class="photo">
              <img src="{{ asset('white') }}/img/anime3.png"
                alt="{{ __('Profile Photo') }}">
            </div>
            <span class="group-title">
              <b class="font-weight-bold text-dark">{{ auth()->user()->name }}</b>
              <i class="fas fa-caret-down align-baseline" style="font-size: 13px"></i>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-navbar">
            <li class="nav-link">
              <a href="{{ route('profile.edit') }}"
                class="nav-item dropdown-item">Perfil</a>
            </li>
            <li class="dropdown-divider"></li>
            <li class="nav-link">
              <a href="{{ route('change-password.edit') }}"
                class="nav-item dropdown-item">Alterar Senha</a>
            </li>
            <li class="dropdown-divider"></li>
            <li class="nav-link">
              <a href="{{ route('logout') }}"
                class="nav-item dropdown-item"
                onclick="event.preventDefault();  document.getElementById('logout-form').submit();">Sair</a>
            </li>
          </ul>
        </li>
        <li class="separator d-lg-none"></li>
      </ul>
    </div>
  </div>
</nav>
