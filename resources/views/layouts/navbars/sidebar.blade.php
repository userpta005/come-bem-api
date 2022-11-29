<div class="sidebar">
  <div class="sidebar-wrapper">
    <div class="logo">
      <a href="#"
        class="simple-text logo-normal"
        style="text-align:center;">
        <img src="{{ asset('images/logo.png') }}"
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
      @canany(['leads_view', 'tenants_view', 'cities_view', 'states_view', 'stores_view'])
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
              @canany(['leads_view', 'tenants_view', 'stores_view', 'clients_view'])
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
                      @can('stores_view')
                        <li>
                          <a href="{{ route('stores.index') }}">
                            <i class="fas fa-user-tag"></i>
                            <p>Lojas</p>
                          </a>
                        </li>
                      @endcan
                      @can('clients_view')
                        <li>
                          <a href="{{ route('clients.index') }}">
                            <i class="fas fa-user-tag"></i>
                            <p>Clientes</p>
                          </a>
                        </li>
                      @endcan
                    </ul>
                  </div>
                </li>
              @endcanany
              @canany(['sections_view', 'payment-methods_view', 'measurement-units_view', 'products_view'])
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
                      @if (session()->has('store'))
                        @can('sections_view')
                          <li>
                            <a href="{{ route('sections.index') }}">
                              <i class="fas fa-tag"></i>
                              <p>Seções</p>
                            </a>
                          </li>
                        @endcan
                      @endif
                      @can('measurement-units_view')
                        <li>
                          <a href="{{ route('measurement-units.index') }}">
                            <i class="fab fa-algolia"></i>
                            <p>Unidades de<br /> Medida</p>
                          </a>
                        </li>
                      @endcan
                      @can('payment-methods_view')
                        <li>
                          <a href="{{ route('payment-methods.index') }}">
                            <i class="fas fa-money-check-alt"></i>
                            <p>Formas de<br /> Pagamento</p>
                          </a>
                        </li>
                      @endcan
                      @if (session()->has('store'))
                        @can('products_view')
                          <li>
                            <a href="{{ route('products.index') }}">
                              <i class="fas fa-box"></i>
                              <p>Produtos</p>
                            </a>
                          </li>
                        @endcan
                      @endif
                    </ul>
                  </div>
                </li>
              @endcanany
              @canany(['cities_view', 'states_view', 'ncms_view'])
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
                      @can('ncms_view')
                        <li>
                          <a href="{{ route('ncms.index') }}">
                            <i class="fas fa-layer-group"></i>
                            <p>Ncms</p>
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
      @if (session()->has('store'))
        @canany(['requisitions_view', 'devolutions_view', 'openingbalances_view', 'stocks_view'])
          <li>
            <a data-toggle="collapse"
              href="#collapseEstoque"
              aria-expanded="false"
              class="collapsed">
              <i class="fas fa-cubes"></i>
              <span class="nav-link-text">Estoque</span>
              <b class="caret mt-1"></b>
            </a>

            <div class="collapse"
              id="collapseEstoque"
              style="">
              <ul class="nav pl-4">
                @can('requisitions_view')
                  <li>
                    <a href="{{ route('requisitions.index') }}">
                      <i class="fas fa-sign-out-alt"></i>
                      <p>Requisição/Saída</p>
                    </a>
                  </li>
                @endcan
                @can('devolutions_view')
                  <li>
                    <a href="{{ route('devolutions.index') }}">
                      <i class="fas fa-sign-in-alt"></i>
                      <p>Devolução/Entrada</p>
                    </a>
                  </li>
                @endcan
                @can('openingbalances_view')
                  <li>
                    <a href="{{ route('openingbalances.index') }}">
                      <i class="fas fa-arrow-left"></i>
                      <p>Saldo Inicial</p>
                    </a>
                  </li>
                @endcan
                @can('stocks_view')
                  <li>
                    <a href="{{ route('stocks.index') }}">
                      <i class="fas fa-boxes"></i>
                      <p>Saldo Estoque</p>
                    </a>
                  </li>
                @endcan
              </ul>
            </div>
          </li>
        @endcanany
      @endif
      @canany(['financialcategories_view'])
        <li>
          <a data-toggle="collapse"
            href="#collapseFiance"
            aria-expanded="false"
            class="collapsed">
            <i class="fas fa-money-bill"></i>
            <span class="nav-link-text">Financeiro</span>
            <b class="caret mt-1"></b>
          </a>
          <div class="collapse"
            id="collapseFiance"
            style="">
            <ul class="nav pl-4">
              @canany(['financialcategories_view'])
                <li>
                  <a data-toggle="collapse"
                    href="#general1"
                    aria-expanded="false"
                    class="collapsed">
                    <i class="fas fa-bars"></i>
                    <span class="nav-link-text">Gerais</span>
                    <b class="caret mt-1"></b>
                  </a>
                  <div class="collapse"
                    id="general1"
                    style="">
                    <ul class="nav pl-4">
                      @can('financialcategories_view')
                        <li>
                          <a href="{{ route('financialcategories.index') }}">
                            <i class="fas fa-tags"></i>
                            <p>Categorias <br /> Financeira</p>
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

      @canany(['client-dependents-report_view', 'stocks-report_view'])
        <li>
          <a data-toggle="collapse"
            href="#reports"
            aria-expanded="false"
            class="collapsed">
            <i class="fas fa-file"></i>
            <span class="nav-link-text">Relatórios</span>
            <b class="caret mt-1"></b>
          </a>
          <div class="collapse"
            id="reports"
            style="">
            <ul class="nav pl-4">
              @can('client-dependents-report_view')
                <li>
                  <a href="{{ route('reports.client-dependents') }}">
                    <i class="fas fa-child"></i>
                    <p>Dependentes</p>
                  </a>
                </li>
              @endcan
              @can('stocks-report_view')
                <li>
                  <a href="{{ route('reports.stocks') }}">
                    <i class="fas fa-boxes"></i>
                    <p>Estoque</p>
                  </a>
                </li>
              @endcan
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
              @if (session()->has('store'))
                @can('settings_edit')
                  <li>
                    <a href="{{ route('settings.edit') }}">
                      <i class="fas fa-cogs"></i>
                      <p>Configurações</p>
                    </a>
                  </li>
                @endcan
              @endif
            </ul>
          </div>
        </li>
      @endcanany
    </ul>
  </div>
</div>
