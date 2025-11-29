<?php
/**
 * @author Design by Malina
 */

declare(strict_types=1);

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\CustomPokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomPokemonController extends Controller
{
    public function index()
    {
        return CustomPokemon::all();
        // return response()->json(CustomPokemon::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'height' => 'required|integer|min:1',
            'weight' => 'required|integer|min:1',
            'base_experience' => 'required|integer|min:1'
        ]);

        $name = strtolower($request->name);

        // Czy istnieje w oficjalnym PokeAPI?
        $resp = Http::get("https://pokeapi.co/api/v2/pokemon/$name");
        if ($resp->ok()) {
            return response()->json(['error' => 'Pokemon already exists in official PokeAPI'], 409);
        }

        // Czy istnieje lokalnie?
        if (CustomPokemon::where('name', $name)->exists()) {
            return response()->json(['error' => 'Pokemon exists in local database'], 409);
        }

        return CustomPokemon::create([
            'name' => $name,
            'height' => $request->height,
            'weight' => $request->weight,
            'base_experience' => $request->base_experience
        ]);
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
