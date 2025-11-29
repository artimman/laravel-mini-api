<?php

namespace App\Http\Controllers;

use App\Models\BannedPokemon;
use Illuminate\Http\Request;

class BannedPokemonController extends Controller
{
    public function index()
    {
        return BannedPokemon::all();
        // return response()->json(BannedPokemon::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:banned_pokemon,name'
        ]);

        return BannedPokemon::create($request->only('name'));
    }

    public function destroy($name)
    {
        $pokemon = BannedPokemon::where('name', $name)->first();

        if (!$pokemon) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $pokemon->delete();

        return response()->json(['status' => 'deleted']);
    }
}
