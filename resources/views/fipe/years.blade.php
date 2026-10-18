@extends('layouts.app')

@section('title', 'Anos e preços')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('fipe.index') }}">Início</a> / {{ $modelName ?? $fipeCode }}
    </div>

    <h1>{{ $modelName ?? 'Modelo' }}</h1>
    <p>
        @if ($brandName)
            <strong>Marca:</strong> {{ $brandName }} &middot;
        @endif
        <strong>Código FIPE:</strong> {{ $fipeCode }}
        @if ($reference)
            &middot; <strong>Referência:</strong> {{ $reference }}
        @endif
    </p>

    @if ($years->isEmpty())
        <p class="empty">Nenhum ano encontrado para este modelo.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Ano modelo</th>
                    <th>Combustível</th>
                    <th>Preço</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($years as $year)
                    <tr>
                        <td>{{ $year['year'] == 32000 ? 'Zero km' : ($year['year'] ?? '-') }}</td>
                        <td>{{ $year['fuel'] ?? '-' }}</td>
                        <td class="price">@include('fipe.partials.price', ['value' => $year['price']])</td>
                        <td>
                            @if ($year['id'])
                                <a href="{{ route('fipe.history', ['fipeCode' => $fipeCode, 'yearId' => $year['id'], 'modelo' => $modelName]) }}">Histórico</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
