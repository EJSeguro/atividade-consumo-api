# Atividade - Consumo de API

Aplicação Laravel que consome a API Tabela FIPE da Invertexto para consultar preços de veículos. O front-end é feito apenas com Blade.

## Funcionalidades

- Seleção do tipo de veículo (carros, motos e caminhões)
- Listagem de marcas por tipo
- Listagem de modelos por marca, com filtro por nome
- Anos, combustível e preço de cada modelo no mês de referência atual
- Histórico mensal de preços por ano do modelo

## Sobre a API Invertexto

A [Invertexto](https://api.invertexto.com) disponibiliza diversas APIs públicas brasileiras, como consulta de CEP, CNPJ, feriados, validação de CPF/CNPJ e a [Tabela FIPE](https://api.invertexto.com/api-tabela-fipe), que é a utilizada neste projeto.

A Tabela FIPE informa o preço médio de veículos no mercado nacional e é atualizada mensalmente. Ela é usada como referência em negociações de compra e venda, seguros e financiamentos.

Todas as requisições exigem um token de acesso. O plano gratuito permite **3.000 requisições por mês**, e o consumo pode ser acompanhado no menu **Consumo** do painel.

### Endpoints utilizados

Base URL: `https://api.invertexto.com/v1`

| Endpoint | Parâmetros | Descrição |
| --- | --- | --- |
| `GET /fipe/brands/{type}` | `type`: 1 = carros, 2 = motos, 3 = caminhões | Marcas por tipo de veículo |
| `GET /fipe/models/{brand_id}` | `brand_id`: id retornado na listagem de marcas | Modelos de uma marca |
| `GET /fipe/years/{fipe_code}` | `fipe_code`: código retornado na listagem de modelos | Anos e preços de um modelo |
| `GET /fipe/history` | `fipe_code` e `year_id` (query string) | Histórico de preços de um modelo/ano |

O token pode ser enviado no cabeçalho `Authorization: Bearer SEU_TOKEN` (forma usada no projeto) ou pela query string `?token=SEU_TOKEN`.

### Exemplos de resposta

`GET /fipe/brands/1`

```json
[
    { "id": 1, "brand": "Acura" },
    { "id": 2, "brand": "Agrale" }
]
```

`GET /fipe/models/21`

```json
[
    { "id": 437, "fipe_code": "001124-0", "model": "147 C/ CL", "years": "1985 a 1987" }
]
```

`GET /fipe/years/001424-9`

```json
{
    "brand": "Fiat",
    "model": "UNO ATTRACTIVE 1.0 Fire Flex 8V 5p",
    "reference": "outubro de 2026",
    "years": [
        { "year_id": "2021-5", "model_year": "2021", "fuel": "Flex", "price": 44844 }
    ]
}
```

`GET /fipe/history?fipe_code=001424-9&year_id=2021-5`

```json
{
    "brand": "Fiat",
    "model": "UNO ATTRACTIVE 1.0 Fire Flex 8V 5p",
    "model_year": "2021",
    "fuel": "Flex",
    "history": [
        { "month": "2026-10", "price": 44844 },
        { "month": "2026-09", "price": 44941 }
    ]
}
```

Sem token, ou com token inválido, a API responde `401` com `{"message": "Unauthenticated."}`.

## Como gerar o token

1. Acesse [api.invertexto.com](https://api.invertexto.com) e clique em **Cadastrar** (ou em **Entrar**, se já tiver conta).
2. Preencha o cadastro e confirme o e-mail, caso seja solicitado.
3. Já logado, abra o menu **Tokens** ([api.invertexto.com/tokens](https://api.invertexto.com/tokens)).
4. Copie o token exibido (ou crie um novo, se não houver nenhum).
5. Cole o token no arquivo `.env` do projeto:

```env
INVERTEXTO_TOKEN=seu_token_aqui
```

O token é pessoal. Não faça commit do arquivo `.env` (ele já está no `.gitignore`).

## Requisitos

- PHP 8.3+
- Composer
- Token da Invertexto

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

Depois, configure o `INVERTEXTO_TOKEN` no `.env` conforme a seção anterior.

## Executando

```bash
php artisan serve
```

Acesse http://localhost:8000.

## Cache

As respostas da API ficam em cache por 6 horas, o que economiza requisições do plano gratuito. Para limpar o cache:

```bash
php artisan cache:clear
```

## Testes

Os testes simulam as respostas da API com `Http::fake`, então não consomem requisições nem precisam de token.

```bash
php artisan test
```

## Estrutura

| Arquivo | Descrição |
| --- | --- |
| `config/services.php` | Configuração da URL e do token da Invertexto |
| `app/Services/FipeService.php` | Comunicação com a API e tratamento de erros |
| `app/Http/Controllers/FipeController.php` | Controller das páginas de consulta |
| `routes/web.php` | Rotas da aplicação |
| `resources/views/` | Views Blade |
| `tests/Feature/FipeTest.php` | Testes das páginas |
