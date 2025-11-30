<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ExistsInLocalException;
use App\Models\CustomPokemon;
use App\Exceptions\ExistsInOfficialException;
use Illuminate\Support\Facades\Http;

class CustomPokemonService
{
    public function create(array $data): CustomPokemon
    {
        $name = strtolower($data['name']);

        $this->ensureDoesNotExistInOfficial($name);
        $this->ensureDoesNotExistInLocally($name);

        return CustomPokemon::create([
            'name' => $name,
            'height' => $data['height'],
            'weight' => $data['weight'],
            'base_experience' => $data['base_experience'],
        ]);
    }

    public function update(CustomPokemon $pokemon, array $data): CustomPokemon
    {
        unset($data['name']);

        $pokemon->update($data);
        return $pokemon;
    }

    public function delete(CustomPokemon $pokemon): void
    {
        $pokemon->delete();
    }

    // --- Helpers

    private function ensureDoesNotExistInOfficial(string $name): void
    {
        $url = config('services.pokeapi.base_url') . "/pokemon/$name";

        if (Http::get($url)->ok()) {
            throw new ExistsInOfficialException();
        }
    }

    private function ensureDoesNotExistInLocally(string $name): void
    {
        if (CustomPokemon::where('name', $name)->exists()) {
            throw new ExistsInLocalException();
        }
    }
}
