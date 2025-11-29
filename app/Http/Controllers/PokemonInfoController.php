<?php
/**
 * @author Design by Malina
 */

namespace App\Http\Controllers;

use App\Models\BannedPokemon;
use App\Models\CustomPokemon;
use App\Services\PokemonInfoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/* class PokemonInfoController extends Controller
{
    public function info(Request $request)
    {
        // --- Walidacja wejścia
        $request->validate([
            'names' => 'required|array|min:1',
            'names.*' => 'string'
        ]);

        // Normalizacja nazw
        $names = collect($request->input('names'))
            ->map(fn($name) => strtolower(trim($name)));

        // Lista zakazanych nazw z bazy
        $banned = BannedPokemon::pluck('name')
            ->map(fn($n) => strtolower($n))
            ->toArray();

        // Odfiltrowujemy zakazane
        $requestedBanned = $names->filter(fn($name) => in_array($name, $banned));
        $requestedAllowed = $names->reject(fn($name) => in_array($name, $banned));

        // --- Pobieramy dane z PokeAPI (oficjalne) z CACHE
        // Pierwsze żądanie -> pobiera z PokeAPI, każde kolejne aż do 12:00 następnego dnia -> pobierze z cache.
        // O godzinie 12:00 cache wygasa automatycznie, a pierwsze żądanie pobierze ponownie aktualne dane.
        $now = now('Europe/Warsaw');
        $nextUpdate = $now->copy()->setTime(12, 0);

        if ($now->greaterThan($nextUpdate)) {
            $nextUpdate->addDay();
        }

        $ttl = $nextUpdate->diffInSeconds($now);

        $official = $requestedAllowed->map(function ($name) use ($ttl) {
            $cacheKey = "pokemon_official_{$name}";

            return cache()->remember($cacheKey, $ttl, function () use ($name) {

                $url = "https://pokeapi.co/api/v2/pokemon/{$name}";
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
        })->filter();

        // --- Pobieramy dane z Local DB (custom)
        $custom = CustomPokemon::whereIn('name', $requestedAllowed)
            ->get()
            ->map(function ($p) {
                return [
                    'name' => strtolower($p->name),
                    'height' => (int) $p->height,
                    'weight' => (int) $p->weight,
                    'base_experience' => (int) $p->base_experience,
                    'source' => 'custom'
                ];
            });

        // --- Łączymy oficjalne + custom w jedną listę
        // UWAGA: Jeśli to samo imię istnieje w obu źródłach, wersja CUSTOM nadpisze oficjalną.
        $combined = collect($official)
            ->keyBy('name')
            ->merge($custom->keyBy('name'))
            ->values();

        // --- Odpowiedź
        return response()->json([
            'requested' => $names->values(),
            'banned' => $requestedBanned->values(),
            'allowed' => $combined->values(),
        ]);
    }
} */

class PokemonInfoController extends Controller
{
    public function __construct(private PokemonInfoService $service)
    {
    }

    public function info(Request $request)
    {
        $request->validate([
            'names' => 'required|array|min:1',
            'names.*' => 'string'
        ]);

        $result = $this->service->process(collect($request->input('names')));

        return response()->json($result);
    }
}
