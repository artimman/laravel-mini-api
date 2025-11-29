<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PokeApiService
{
    // --- Pobieramy dane z PokeAPI (oficjalne) z CACHE
    // Pierwsze żądanie -> pobiera z PokeAPI, każde kolejne aż do 12:00 następnego dnia -> pobierze z cache.
    // O godzinie 12:00 cache wygasa automatycznie, a pierwsze żądanie pobierze ponownie aktualne dane.
    public function getPokemon(string $name): ?array
    {
        $name = strtolower($name);

        $cacheKey = "pokemon_official_{$name}";
        $ttl = $this->getCacheTTLToNextNoon();

        return cache()->remember($cacheKey, $ttl, function () use ($name) {
            $url = config('services.pokeapi.base_url') . "/pokemon/$name";
            $response = Http::get($url);

            if (!$response->ok()) {
                return null;
            }

            return [
                'name' => $response['name'],
                'height' => $response['height'],
                'weight' => $response['weight'],
                'base_experience' => $response['base_experience'],
                'source' => 'official'
            ];
        });
    }

    private function getCacheTTLToNextNoon(): int
    {
        $now = now('Europe/Warsaw');
        $nextUpdate = $now->copy()->setTime(12, 0);

        if ($now->greaterThan($nextUpdate)) {
            $nextUpdate->addDay();
        }

        return (int) $nextUpdate->diffInSeconds($now);
    }
}
