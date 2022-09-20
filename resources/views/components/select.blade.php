@props(['options', 'selected' => null, 'label' => null, 'behavior' => null])

{{-- @php
    dd($attributes)
@endphp --}}

<div class="form-group">
    @isset($label)
        <label class="{{$attributes['required']}}">{{$label}}</label>
    @endisset
    <select {{$attributes->merge(['class' => 'select2 select-item '])}}>
        @foreach ($options as $key => $option)
        <option value="{{ $option->id ?? $key }}" data-item="{{ isset($option->id) ? $option : '' }}" @selected(
            isset($selected) && ($option->id ?? $key) == $selected ??
            ($option->id ?? $key) == old('selected')
            )>
            {{ $option->name ?? ($option->description ?? $option) }}
        </option>
        @endforeach
    </select>
    @if($errors->has($attributes['name']))
        <div class="invalid-feedback d-block">{{$errors->first($attributes['name'])}}</div>
    @endif
</div>