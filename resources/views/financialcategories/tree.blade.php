@unless($data->isEmpty())
  @foreach ($data as $item)
    <tr class="{{ in_array($item->depth, [0, 1, 2]) ? 'font-weight-bold' : '' }}">
      <td>{{ $item->code }}</td>
      <td>{{ $item->class->name() }}</td>
      <td>{{ $item->type->name() }}</td>
      <td>{{ $item->description }}</td>
      <td>{{ $item->status->name() }}</td>
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
            <form action="{{ route('financialcategories.destroy', $item->id) }}"
              method="post"
              id="form-{{ $item->id }}">
              @csrf
              @method('delete')
              @can('financialcategories_view')
                <a class="dropdown-item"
                  href="{{ route('financialcategories.show', $item) }}">
                  Visualizar
                </a>
              @endcan
              @if ($item->status->isActive() && strlen($mask->value) !== $item->depth + 1)
                <a class="dropdown-item"
                  href="{{ route('financialcategories.create', ['parent_id' => $item->getKey()]) }}">
                  Cria descendente
                </a>
              @endif
              @can('financialcategories_edit')
                <a class="dropdown-item"
                  href="{{ route('financialcategories.edit', $item) }}">
                  Editar
                </a>
              @endcan
              @can('financialcategories_delete')
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
    @include('financialcategories.tree', ['data' => $item->children])
  @endforeach
@endunless
