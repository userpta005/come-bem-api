<div class="sidebar">
  <div class="sidebar-wrapper">
    <div class="logo">
      <a href="#"
        class="simple-text logo-normal"
        style="text-align:center;">
        <img src="{{ asset('images/logoDixBranca.png') }}"
          alt="Logo"
          style="max-width: 150px;">
      </a>
    </div>
    <ul class="nav">
      @can('panel_view')
        <li>
          <a href="{{ route('home') }}">
            <i class="fas fa-chart-pie"></i>
            <p>Painel</p>
          </a>
        </li>
      @endcan
      @canany(['leads_view', 'tenants_view', 'cities_view', 'states_view'])
        <li>
          <a data-toggle="collapse"
            href="#register"
            aria-expanded="false"
            class="collapsed">
            <i class="fas fa-edit"></i>
            <span class="nav-link-text">Cadastros</span>
            <b class="caret mt-1"></b>
          </a>
          <div class="collapse"
            id="register"
            style="">
            <ul class="nav pl-4">
              @canany(['leads_view', 'tenants_view'])
                <li>
                  <a data-toggle="collapse"
                    href="#people"
                    aria-expanded="false"
                    class="collapsed">
                    <i class="fas fa-users"></i>
                    <span class="nav-link-text">Pessoas</span>
                    <b class="caret mt-1"></b>
                  </a>

                  <div class="collapse"
                    id="people"
                    style="">
                    <ul class="nav pl-4">
                      @can('leads_view')
                        <li>
                          <a href="{{ route('leads.index') }}">
                            <i class="fas fa-user"></i>
                            <p>Leads</p>
                          </a>
                        </li>
                      @endcan
                      @can('tenants_view')
                        <li>
                          <a href="{{ route('tenants.index') }}">
                            <i class="fas fa-user-tag"></i>
                            <p>Contratantes</p>
                          </a>
                        </li>
                      @endcan
                    </ul>
                  </div>
                </li>
              @endcanany
              @canany(['sections_view', 'payment-methods_view', 'measurement-units_view'])
                <li>
                  <a data-toggle="collapse"
                    href="#operational"
                    aria-expanded="false"
                    class="collapsed">
                    <i class="fas fa-clipboard-list"></i>
                    <span class="nav-link-text">Operacionais</span>
                    <b class="caret mt-1"></b>
                  </a>

                  <div class="collapse"
                    id="operational"
                    style="">
                    <ul class="nav pl-4">
                      @can('sections_view')
                        <li>
                          <a href="{{ route('sections.index') }}">
                            <i class="fas fa-tag"></i>
                            <p>Seções</p>
                          </a>
                        </li>
                      @endcan
                      @can('payment-methods_view')
                      <li>
                          <a href="{{ route('payment-methods.index') }}">
                              <i class="fas fa-money-check-alt"></i>
                              <p>Formas de<br/> Pagamento</p>
                          </a>
                      </li>
                      @endcan
                      @can('measurement-units_view')
                      <li>
                          <a href="{{ route('measurement-units.index') }}">
                              <i class="fab fa-algolia"></i>
                              <p>Unidade de<br/> Medida</p>
                          </a>
                      </li>
                      @endcan
                    </ul>
                  </div>
                </li>
              @endcanany
              @canany(['cities_view', 'states_view'])
                <li>
                  <a data-toggle="collapse"
                    href="#generalCreate"
                    aria-expanded="false"
                    class="collapsed">
                    <i class="fas fa-bars"></i>
                    <span class="nav-link-text">Gerais</span>
                    <b class="caret mt-1"></b>
                  </a>

                  <div class="collapse"
                    id="generalCreate"
                    style="">
                    <ul class="nav pl-4">
                      @can('cities_view')
                        <li>
                          <a href="{{ route('cities.index') }}">
                            <i class="fas fa-city"></i>
                            <p>Cidades</p>
                          </a>
                        </li>
                      @endcan
                      @can('states_view')
                        <li>
                          <a href="{{ route('states.index') }}">
                            <i class="fas fa-layer-group"></i>
                            <p>Estados</p>
                          </a>
                        </li>
                      @endcan
                    </ul>
                  </div>
                </li>
              @endcanany
            </ul>
          </div>
        </li>
      @endcanany
      @canany(['users_view', 'roles_view', 'permissions_view'])
        <li>
          <a data-toggle="collapse"
            href="#management"
            aria-expanded="false"
            class="collapsed">
            <i class="fas fa-users-cog"></i>
            <span class="nav-link-text">Gerenciamento</span>
            <b class="caret mt-1"></b>
          </a>

          <div class="collapse"
            id="management"
            style="">
            <ul class="nav pl-4">
              @can('users_view')
                <li>
                  <a href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i>
                    <p>Usuários</p>
                  </a>
                </li>
              @endcan

              @can('roles_view')
                <li>
                  <a href="{{ route('roles.index') }}">
                    <i class="fas fa-user-lock"></i>
                    <p>Atribuições</p>
                  </a>
                </li>
              @endcan
              @can('permissions_view')
                <li>
                  <a href="{{ route('permissions.index') }}">
                    <i class="fas fa-lock"></i>
                    <p>Permissões</p>
                  </a>
                </li>
              @endcan
            </ul>
          </div>
        </li>
      @endcanany
      @canany(['parameters_view', 'faqs_view', 'banners_view', 'settings_edit'])
        <li>
          <a data-toggle="collapse"
            href="#settings"
            aria-expanded="false"
            class="collapsed">
            <i class="fas fa-cogs"></i>
            <span class="nav-link-text">Configurações</span>
            <b class="caret mt-1"></b>
          </a>

          <div class="collapse"
            id="settings"
            style="">
            <ul class="nav pl-4">
              @can('parameters_view')
                <li>
                  <a href="{{ route('parameters.index') }}">
                    <i class="fas fa-cog"></i>
                    <p>Parâmetros</p>
                  </a>
                </li>
              @endcan
              @can('faqs_view')
                <li>
                  <a href="{{ route('faqs.index') }}">
                    <i class="fas fa-question"></i>
                    <p>Faqs</p>
                  </a>
                </li>
              @endcan
              @can('banners_view')
                <li>
                  <a href="{{ route('banners.index') }}">
                    <i class="fas fa-image"></i>
                    <p>Mídias</p>
                  </a>
                </li>
              @endcan
              @can('settings_edit')
                <li>
                  <a href="{{ route('settings.edit') }}">
                    <i class="fas fa-cogs"></i>
                    <p>Configurações</p>
                  </a>
                </li>
              @endcan
            </ul>
          </div>
        </li>
      @endcanany
    </ul>
  </div>
</div>
