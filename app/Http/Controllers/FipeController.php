<?php

namespace App\Http\Controllers;

use App\Services\FipeService;
use Illuminate\Http\Request;
use RuntimeException;

class FipeController extends Controller
{
    public function __construct(protected FipeService $fipe)
    {
    }

    public function index()
    {
        return view('fipe.index', ['types' => FipeService::TYPES]);
    }

    public function brands(int $type)
    {
        abort_unless(isset(FipeService::TYPES[$type]), 404);

        return $this->attempt(function () use ($type) {
            $brands = collect($this->fipe->brands($type))
                ->map(fn ($item) => [
                    'id' => $this->pick($item, ['id', 'brand_id', 'codigo', 'code']),
                    'name' => $this->pick($item, ['brand', 'name', 'nome', 'marca']),
                ])
                ->filter(fn ($brand) => filled($brand['id']))
                ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
                ->values();

            return view('fipe.brands', [
                'type' => $type,
                'typeName' => FipeService::TYPES[$type],
                'brands' => $brands,
            ]);
        });
    }

    public function models(Request $request, int $brand)
    {
        return $this->attempt(function () use ($request, $brand) {
            $search = trim((string) $request->query('busca'));

            $models = collect($this->fipe->models($brand))
                ->map(fn ($item) => [
                    'fipe_code' => $this->pick($item, ['fipe_code', 'codigo_fipe', 'code']),
                    'name' => $this->pick($item, ['model', 'name', 'nome', 'modelo']),
                    'years' => $this->pick($item, ['years', 'anos']),
                ])
                ->filter(fn ($model) => filled($model['fipe_code']))
                ->when($search !== '', fn ($models) => $models->filter(
                    fn ($model) => str_contains(mb_strtolower($model['name']), mb_strtolower($search))
                ))
                ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
                ->values();

            return view('fipe.models', [
                'brand' => $brand,
                'brandName' => $request->query('marca'),
                'type' => $request->query('tipo'),
                'search' => $search,
                'models' => $models,
            ]);
        });
    }

    public function years(Request $request, string $fipeCode)
    {
        return $this->attempt(function () use ($request, $fipeCode) {
            $years = collect($this->fipe->years($fipeCode))
                ->map(fn ($item) => [
                    'id' => $this->pick($item, ['year_id', 'id', 'codigo']),
                    'year' => $this->pick($item, ['model_year', 'year', 'ano', 'ano_modelo']),
                    'fuel' => $this->pick($item, ['fuel', 'combustivel']),
                    'price' => $this->pick($item, ['price', 'preco', 'valor']),
                    'model' => $this->pick($item, ['model', 'modelo']),
                    'brand' => $this->pick($item, ['brand', 'marca']),
                    'reference' => $this->pick($item, ['reference_month', 'reference', 'mes_referencia']),
                ])
                ->values();

            return view('fipe.years', [
                'fipeCode' => $fipeCode,
                'modelName' => $request->query('modelo') ?? $years->pluck('model')->filter()->first(),
                'brandName' => $years->pluck('brand')->filter()->first(),
                'years' => $years,
            ]);
        });
    }

    public function history(Request $request, string $fipeCode, string $yearId)
    {
        return $this->attempt(function () use ($request, $fipeCode, $yearId) {
            $history = collect($this->fipe->history($fipeCode, $yearId))
                ->map(fn ($item) => [
                    'reference' => $this->pick($item, ['reference_month', 'reference', 'month', 'mes_referencia', 'mes', 'date']),
                    'price' => $this->pick($item, ['price', 'preco', 'valor']),
                ])
                ->values();

            return view('fipe.history', [
                'fipeCode' => $fipeCode,
                'yearId' => $yearId,
                'modelName' => $request->query('modelo'),
                'history' => $history,
            ]);
        });
    }

    protected function attempt(callable $callback)
    {
        try {
            return $callback();
        } catch (RuntimeException $e) {
            return redirect()->route('fipe.index')->with('error', $e->getMessage());
        }
    }

    protected function pick(mixed $item, array $keys): mixed
    {
        if (! is_array($item)) {
            return null;
        }

        foreach ($keys as $key) {
            if (array_key_exists($key, $item) && $item[$key] !== null) {
                return $item[$key];
            }
        }

        return null;
    }
}
