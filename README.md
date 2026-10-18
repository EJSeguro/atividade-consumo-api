# Atividade - Consumo de API

Aplicação Laravel que consome a [API Tabela FIPE da Invertexto](https://api.invertexto.com/api-tabela-fipe) para consultar preços de veículos. O front-end é feito apenas com Blade.

## Funcionalidades

- Seleção do tipo de veículo (carros, motos e caminhões)
- Listagem de marcas por tipo
- Listagem de modelos por marca, com filtro por nome
- Anos, combustível e preço de cada modelo
- Histórico de preços por ano do modelo

## Requisitos

- PHP 8.3+
- Composer
- Token da Invertexto (gerado em https://api.invertexto.com)

## Instalação

```bash
git clone https://github.com/EJSeguro/atividade-consumo-api.git
cd atividade-consumo-api
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
```

Defina o token no `.env`:

```env
INVERTEXTO_TOKEN=seu_token_aqui
```

## Executando

```bash
php artisan serve
```

Acesse http://localhost:8000.

## Testes

```bash
php artisan test
```

## Estrutura

| Arquivo | Descrição |
| --- | --- |
| `app/Services/FipeService.php` | Comunicação com a API da Invertexto (com cache de 6 horas) |
| `app/Http/Controllers/FipeController.php` | Controller das páginas de consulta |
| `routes/web.php` | Rotas da aplicação |
| `resources/views/` | Views Blade |

## Endpoints utilizados

| Endpoint | Descrição |
| --- | --- |
| `GET /v1/fipe/brands/{type}` | Marcas por tipo de veículo |
| `GET /v1/fipe/models/{brand_id}` | Modelos de uma marca |
| `GET /v1/fipe/years/{fipe_code}` | Anos e preços de um modelo |
| `GET /v1/fipe/history?fipe_code=&year_id=` | Histórico de preços |
