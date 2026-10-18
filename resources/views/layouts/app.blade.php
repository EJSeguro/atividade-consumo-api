<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Consulta FIPE') - {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; color: #1f2937; }
        header { background: #1e3a8a; color: #fff; padding: 1rem 0; }
        header a { color: #fff; text-decoration: none; font-weight: 700; font-size: 1.25rem; }
        .container { max-width: 960px; margin: 0 auto; padding: 0 1rem; }
        main { padding: 2rem 0; }
        h1 { margin-top: 0; font-size: 1.6rem; }
        .breadcrumb { font-size: .9rem; margin-bottom: 1rem; color: #6b7280; }
        .breadcrumb a { color: #1d4ed8; text-decoration: none; }
        .card { background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: .75rem; }
        .grid a { display: block; padding: .9rem 1rem; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; color: #1f2937; text-decoration: none; }
        .grid a:hover { border-color: #1d4ed8; color: #1d4ed8; }
        .types a { text-align: center; font-size: 1.1rem; font-weight: 600; padding: 2rem 1rem; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: .7rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-size: .85rem; text-transform: uppercase; color: #6b7280; }
        td a { color: #1d4ed8; }
        .alert { padding: 1rem; border-radius: 6px; margin-bottom: 1rem; background: #fee2e2; color: #991b1b; }
        .empty { color: #6b7280; }
        form.search { display: flex; gap: .5rem; margin-bottom: 1rem; }
        form.search input { flex: 1; padding: .6rem; border: 1px solid #d1d5db; border-radius: 6px; }
        button { padding: .6rem 1rem; background: #1d4ed8; color: #fff; border: 0; border-radius: 6px; cursor: pointer; }
        .price { font-weight: 600; color: #047857; }
        footer { text-align: center; color: #9ca3af; font-size: .85rem; padding: 1rem 0 2rem; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="{{ route('fipe.index') }}">Consulta Tabela FIPE</a>
        </div>
    </header>

    <main class="container">
        @if (session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer>Dados fornecidos pela API Tabela FIPE da Invertexto</footer>
</body>
</html>
