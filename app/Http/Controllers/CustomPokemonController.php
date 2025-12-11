<?php
/**
 * @author Design by Malina
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CustomPokemon;
use App\Services\CustomPokemonService;
use App\Helpers\ApiResponse;
use App\Exceptions\ExistsInLocalException;
use App\Exceptions\ExistsInOfficialException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomPokemonController extends Controller
{
    public function __construct(
        private readonly CustomPokemonService $service
    ) {
    }

    public function index()
    {
        return ApiResponse::success(CustomPokemon::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'height' => ['required', 'integer', 'min:1'],
            'weight' => ['required', 'integer', 'min:1'],
            'base_experience' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $pokemon = $this->service->create($validated);
            return ApiResponse::success($pokemon, 201);
        } catch (ExistsInOfficialException|ExistsInLocalException $e) {
            Log::warning('Attempting to add Pokemon', [
                'name' => $validated['name'],
                'error' => $e->getMessage(),
            ]);

            return ApiResponse::error($e->getMessage(), 409);
        }
    }

    public function show(int $id)
    {
        $pokemon = CustomPokemon::findOrFail($id);
        return ApiResponse::success($pokemon);
    }

    public function update(Request $request, int $id)
    {
        $pokemon = CustomPokemon::findOrFail($id);

        $validated = $request->validate([
            'height' => ['integer', 'min:1'],
            'weight' => ['integer', 'min:1'],
            'base_experience' => ['integer', 'min:1'],
        ]);

        $updated = $this->service->update($pokemon, $validated);

        return ApiResponse::success($updated);
    }

    public function destroy(int $id)
    {
        $pokemon = CustomPokemon::findOrFail($id);
        $this->service->delete($pokemon);

        return ApiResponse::success(null, 204);
    }
}
