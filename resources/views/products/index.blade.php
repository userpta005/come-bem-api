@extends('layouts.app', ['page' => 'Produtos', 'pageSlug' => 'products'])

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header">
          <div class="row">
            <div class="col-8">
              <h4 class="card-title">Produtos</h4>
            </div>
            @can('products_create')
              <div class="col-4 text-right">
                <a href="{{ route('products.create') }}"
                  class="btn btn-sm btn-primary">Adicionar Novo</a>
              </div>
            @endcan
          </div>
        </div>

        <div class="card-body">
          @include('alerts.success')
          @include('alerts.error')

          <div class="row">
            <div class="col-md-12 text-left">
              <p style="font-size:15px;">
                <b>Classificação Nutricional:</b>
                <span style="margin-right: 10px;">
                  <span class="dot"
                    style="background-color:#808080;"></span>
                  Em Análise
                </span>
                <span style="margin-right: 10px;">
                  <span class="dot"
                    style="background-color:#ff0000;"></span>
                  Pouco Nutritivo
                </span>
                <span style="margin-right: 10px;">
                  <span class="dot"
                    style="background-color: #ffa500;"></span>
                  Moderado
                </span>
                <span style="margin-right: 10px;">
                  <span class="dot"
                    style="background-color: #008000;"></span>
                  Muito Nutritivo
                </span>
              </p>
            </div>
          </div>

          <div class="">
            <table class="table tablesorter table-striped">
              <thead class="text-primary">
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Categoria</th>
                <th scope="col">NCM</th>
                <th scope="col">Ativo</th>
                <th scope="col"
                  class="text-right">Ação</th>
              </thead>
              <tbody>
                @forelse ($data as $item)
                  <tr>
                    <td class="text-center">
                      @if ($item->nutritional_classification == \App\Enums\NutritionalClassification::UNDER_ANALYSIS)
                        <span class="dot"
                          style="background-color: #808080;"></span>
                      @elseif ($item->nutritional_classification == \App\Enums\NutritionalClassification::LITTLE_NUTRITIOUS)
                        <span class="dot"
                          style="background-color: #ff0000;"></span>
                      @elseif ($item->nutritional_classification == \App\Enums\NutritionalClassification::MODERATE)
                        <span class="dot"
                          style="background-color: #ffa500;"></span>
                      @elseif ($item->nutritional_classification == \App\Enums\NutritionalClassification::VERY_NUTRITIOUS)
                        <span class="dot"
                          style="background-color: #008000;"></span>
                      @endif
                    </td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->section->name }}</td>
                    <td>{{ $item->ncm->description }}</td>
                    <td>{{ $item->is_active->name() }}</td>
                    <td class="text-right">
                      <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light"
                          href="#"
                          role="button"
                          data-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                          <form action="{{ route('products.destroy', $item->id) }}"
                            method="post"
                            id="form-{{ $item->id }}">
                            @csrf
                            @method('delete')
                            @can('products_view')
                              <a class="dropdown-item"
                                href="{{ route('products.show', $item) }}">Visualizar</a>
                            @endcan
                            @can('products_edit')
                              <a class="dropdown-item"
                                href="{{ route('products.edit', $item) }}">Editar</a>
                            @endcan
                            @can('products_delete')
                              <button type="button"
                                class="dropdown-item btn-delete">
                                Excluir
                              </button>
                            @endcan
                          </form>
                        </div>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="20"
                      style="text-align: center; font-size: 1.1em;">
                      Nenhuma informação cadastrada.
                    </td>
                  </tr>
                @endforelse

              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer py-4">
          <nav class="d-flex justify-content-end"
            aria-label="...">
            {{ $data->links() }}
          </nav>
        </div>
      </div>
    </div>
  </div>
@endsection
