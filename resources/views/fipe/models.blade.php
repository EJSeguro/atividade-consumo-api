@extends('layouts.app')

@section('title', 'Modelos')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('fipe.index') }}">Início</a>
        @if ($type)
            / <a href="{{ route('fipe.brands', $type) }}">Marcas</a>
        @endif
        / {{ $brandName ?? 'Modelos' }}
    </div>

    <h1>Modelos{{ $brandName ? ' - '.$brandName : '' }}</h1>

    <form class="search" method="GET" action="{{ route('fipe.models', $brand) }}">
        <input type="hidden" name="tipo" value="{{ $type }}">
        <input type="hidden" name="marca" value="{{ $brandName }}">
        <input type="text" name="busca" value="{{ $search }}" placeholder="Filtrar modelo...">
        <button type="submit">Buscar</button>
    </form>

    @if ($models->isEmpty())
        <p class="empty">Nenhum modelo encontrado.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th>Código FIPE</th>
                    <th>Anos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($models as $model)
                    <tr>
                        <td>
                            <a href="{{ route('fipe.years', ['fipeCode' => $model['fipe_code'], 'modelo' => $model['name']]) }}">
                                {{ $model['name'] ?? $model['fipe_code'] }}
                            </a>
                        </td>
                        <td>{{ $model['fipe_code'] }}</td>
                        <td>{{ is_array($model['years']) ? implode(', ', $model['years']) : ($model['years'] ?? '-') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
