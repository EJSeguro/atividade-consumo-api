@extends('layouts.app')

@section('title', 'Início')

@section('content')
    <h1>Selecione o tipo de veículo</h1>

    <div class="grid types">
        @foreach ($types as $id => $name)
            <a href="{{ route('fipe.brands', $id) }}">{{ $name }}</a>
        @endforeach
    </div>
@endsection
