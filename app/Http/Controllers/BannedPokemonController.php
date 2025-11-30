<?php
/**
 * @author Design by Malina
 */

namespace App\Http\Controllers;

use App\Services\BannedPokemonService;
use App\Helpers\ApiResponse;
use App\Exceptions\BannedAlreadyExistsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BannedPokemonController extends Controller
{
    public function __construct(
        private readonly BannedPokemonService $service
    ) {
    }

    public function index()
    {
        return ApiResponse::success($this->service->list());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
        ]);

        try {
            $pokemon = $this->service->create($validated['name']);

            return ApiResponse::success($pokemon, 201);
        } catch (BannedAlreadyExistsException $e) {
            Log::warning('Attempt to re-ban Pokemon', [
                'name' => $validated['name'],
                'error' => $e->getMessage(),
            ]);

            return ApiResponse::error($e->getMessage(), 409);
        }
    }

    public function destroy(string $name)
    {
        $this->service->delete($name);

        return ApiResponse::success(null, 204);
    }
}
