<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FipeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['services.invertexto.token' => 'token-teste']);
    }

    public function test_home_lists_vehicle_types(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Carros')
            ->assertSee('Motos')
            ->assertSee('Caminhões');
    }

    public function test_lists_brands(): void
    {
        Http::fake([
            'api.invertexto.com/v1/fipe/brands/1' => Http::response([
                ['id' => 21, 'brand' => 'Fiat'],
                ['id' => 59, 'brand' => 'VW - VolksWagen'],
            ]),
        ]);

        $this->get('/tipos/1')
            ->assertOk()
            ->assertSee('Fiat')
            ->assertSee('VW - VolksWagen');

        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer token-teste'));
    }

    public function test_lists_and_filters_models(): void
    {
        Http::fake([
            'api.invertexto.com/v1/fipe/models/21' => Http::response([
                ['model' => 'Uno Mille 1.0', 'fipe_code' => '001004-9'],
                ['model' => 'Palio 1.0', 'fipe_code' => '001001-4'],
            ]),
        ]);

        $this->get('/marcas/21?busca=uno')
            ->assertOk()
            ->assertSee('Uno Mille 1.0')
            ->assertDontSee('Palio 1.0');
    }

    public function test_lists_years_with_prices(): void
    {
        Http::fake([
            'api.invertexto.com/v1/fipe/years/*' => Http::response([
                ['year_id' => '2010-1', 'model_year' => 2010, 'fuel' => 'Gasolina', 'price' => 15000.5],
            ]),
        ]);

        $this->get('/modelos/001004-9')
            ->assertOk()
            ->assertSee('2010')
            ->assertSee('R$ 15.000,50');
    }

    public function test_shows_price_history(): void
    {
        Http::fake([
            'api.invertexto.com/v1/fipe/history*' => Http::response([
                ['reference_month' => 'janeiro de 2026', 'price' => 14000],
            ]),
        ]);

        $this->get('/modelos/001004-9/anos/2010-1')
            ->assertOk()
            ->assertSee('janeiro de 2026')
            ->assertSee('R$ 14.000,00');
    }

    public function test_shows_error_when_token_is_invalid(): void
    {
        Http::fake([
            'api.invertexto.com/*' => Http::response(['message' => 'Unauthenticated.'], 401),
        ]);

        $this->followingRedirects()
            ->get('/tipos/2')
            ->assertOk()
            ->assertSee('Token da Invertexto inválido ou não configurado.');
    }
}
