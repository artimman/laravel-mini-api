<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BannedPokemon;
use App\Exceptions\BannedAlreadyExistsException;

class BannedPokemonService
{
    public function list()
    {
        return BannedPokemon::all();
    }

    public function create(string $name): BannedPokemon
    {
        $normalized = strtolower($name);

        if (BannedPokemon::where('name', $normalized)->exists()) {
            throw new BannedAlreadyExistsException();
        }

        return BannedPokemon::create(['name' => $normalized]);
    }

    public function delete(string $name): void
    {
        $pokemon = BannedPokemon::where('name', strtolower($name))->firstOrFail();

        $pokemon->delete();
    }
}
