@extends('layouts.app')

@section('title', 'Marcas')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('fipe.index') }}">Início</a> / {{ $typeName }}
    </div>

    <h1>Marcas - {{ $typeName }}</h1>

    @if ($brands->isEmpty())
        <p class="empty">Nenhuma marca encontrada.</p>
    @else
        <div class="grid">
            @foreach ($brands as $brand)
                <a href="{{ route('fipe.models', ['brand' => $brand['id'], 'tipo' => $type, 'marca' => $brand['name']]) }}">
                    {{ $brand['name'] ?? $brand['id'] }}
                </a>
            @endforeach
        </div>
    @endif
@endsection
