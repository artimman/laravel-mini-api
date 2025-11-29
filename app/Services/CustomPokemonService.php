<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CustomPokemon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class CustomPokemonService
{
    public function createCustomPokemon(array $data): CustomPokemon|JsonResponse
    {
        $name = strtolower($data['name']);

        // Sprawdzenie PokeAPI
        $url = config('services.pokeapi.base_url') . "/pokemon/$name";

        $resp = Http::get($url);

        if ($resp->ok()) {
            return response()->json([
                'error' => "Pokemon already exists in official PokeAPI"
            ], 409);
        }

        // Sprawdzenie lokalne
        if (CustomPokemon::where('name', $name)->exists()) {
            return response()->json([
                'error' => 'Pokemon exists in local database'
            ], 409);
        }

        // Utworzenie
        return CustomPokemon::create([
            'name' => $name,
            'height' => $data['height'],
            'weight' => $data['weight'],
            'base_experience' => $data['base_experience'],
        ]);
    }
}
