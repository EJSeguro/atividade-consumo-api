@extends('layouts.app')

@section('title', 'Histórico de preços')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('fipe.index') }}">Início</a>
        / <a href="{{ route('fipe.years', ['fipeCode' => $fipeCode, 'modelo' => $modelName]) }}">{{ $modelName ?? $fipeCode }}</a>
        / Histórico
    </div>

    <h1>Histórico de preços</h1>
    <p>
        @if ($brandName)
            <strong>Marca:</strong> {{ $brandName }} &middot;
        @endif
        @if ($modelName)
            <strong>Modelo:</strong> {{ $modelName }} &middot;
        @endif
        <strong>Ano:</strong> {{ $modelYear == 32000 ? 'Zero km' : $modelYear }}
        @if ($fuel)
            &middot; <strong>Combustível:</strong> {{ $fuel }}
        @endif
        &middot; <strong>Código FIPE:</strong> {{ $fipeCode }}
    </p>

    @if ($history->isEmpty())
        <p class="empty">Nenhum histórico disponível.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Mês de referência</th>
                    <th>Preço</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $row)
                    <tr>
                        <td>{{ $row['reference'] ?? '-' }}</td>
                        <td class="price">@include('fipe.partials.price', ['value' => $row['price']])</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
