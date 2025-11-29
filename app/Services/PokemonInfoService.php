<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BannedPokemon;
use App\Models\CustomPokemon;
use Illuminate\Support\Collection;

class PokemonInfoService
{
    public function __construct(private PokeApiService $pokeApi)
    {
    }

    public function process(Collection $names): array
    {
        // Normalizacja nazw
        $normalized = $names->map(fn($n) => strtolower(trim($n)));

        // Lista zakazanych nazw z bazy
        $banned = BannedPokemon::pluck('name')
            ->map(fn($n) => strtolower($n))
            ->toArray();

        // Odfiltrowujemy zakazane
        $requestedBanned = $normalized->filter(fn($n) => in_array($n, $banned));
        $requestedAllowed = $normalized->reject(fn($n) => in_array($n, $banned));

        // Pobieramy dane z PokeAPI (oficjalne) z CACHE
        $official = $requestedAllowed->map(fn($name) => $this->pokeApi->getPokemon($name))
            ->filter();

        // Pobieramy dane z Local DB (custom)
        $custom = CustomPokemon::whereIn('name', $requestedAllowed)
            ->get()
            ->map(fn($p) => [
                'name' => strtolower($p->name),
                'height' => (int)$p->height,
                'weight' => (int)$p->weight,
                'base_experience' => (int)$p->base_experience,
                'source' => 'custom'
            ]);

        $combined = collect($official)
            ->keyBy('name')
            ->merge($custom->keyBy('name'))
            ->values();

        return [
            'requested' => $normalized->values(),
            'banned' => $requestedBanned->values(),
            'allowed' => $combined->values()
        ];
    }
}
