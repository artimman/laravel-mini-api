<?php
/**
 * @author Design by Malina
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CustomPokemon;
use App\Services\CustomPokemonService;
use Illuminate\Http\Request;

class CustomPokemonController extends Controller
{
    public function __construct(
        private readonly CustomPokemonService $service
    ) {
    }

    public function index()
    {
        return CustomPokemon::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'height' => 'required|integer|min:1',
            'weight' => 'required|integer|min:1',
            'base_experience' => 'required|integer|min:1'
        ]);

        return $this->service->createCustomPokemon($validated);
    }

    public function show($id)
    {
        return CustomPokemon::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $pokemon = CustomPokemon::findOrFail($id);

        $pokemon->update($request->all());

        return $pokemon;
    }

    public function destroy($id)
    {
        $pokemon = CustomPokemon::findOrFail($id);

        $pokemon->delete();

        return response()->json(['status' => 'deleted']);
    }
}
