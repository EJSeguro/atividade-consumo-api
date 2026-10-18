<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FipeService
{
    public const TYPES = [
        1 => 'Carros',
        2 => 'Motos',
        3 => 'Caminhões',
    ];

    public function brands(int $type): array
    {
        return $this->get("fipe/brands/{$type}");
    }

    public function models(int $brandId): array
    {
        return $this->get("fipe/models/{$brandId}");
    }

    public function years(string $fipeCode): array
    {
        return $this->get('fipe/years/'.urlencode($fipeCode));
    }

    public function history(string $fipeCode, string $yearId): array
    {
        return $this->get('fipe/history', [
            'fipe_code' => $fipeCode,
            'year_id' => $yearId,
        ]);
    }

    protected function get(string $endpoint, array $query = []): array
    {
        $key = 'fipe:'.md5($endpoint.'?'.http_build_query($query));

        return Cache::remember($key, now()->addHours(6), function () use ($endpoint, $query) {
            try {
                $response = $this->client()->get($endpoint, $query);
            } catch (ConnectionException) {
                throw new RuntimeException('Não foi possível conectar à API da Invertexto.');
            }

            if ($response->status() === 401) {
                throw new RuntimeException('Token da Invertexto inválido ou não configurado.');
            }

            if ($response->status() === 429) {
                throw new RuntimeException('Limite de requisições da API atingido. Tente novamente mais tarde.');
            }

            if ($response->failed()) {
                throw new RuntimeException($response->json('message') ?? 'Erro ao consultar a API ('.$response->status().').');
            }

            $data = $response->json() ?? [];

            return isset($data['data']) && is_array($data['data']) ? $data['data'] : $data;
        });
    }

    protected function client(): PendingRequest
    {
        $token = config('services.invertexto.token');

        if (blank($token)) {
            throw new RuntimeException('Defina INVERTEXTO_TOKEN no arquivo .env.');
        }

        return Http::baseUrl(config('services.invertexto.url'))
            ->withToken($token)
            ->acceptJson()
            ->timeout(15);
    }
}
