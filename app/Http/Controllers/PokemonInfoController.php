<?php
/**
 * @author Design by Malina
 */

namespace App\Http\Controllers;

use App\Services\PokemonInfoService;
use Illuminate\Http\Request;

class PokemonInfoController extends Controller
{
    public function __construct(
        private readonly PokemonInfoService $service
    ) {
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
