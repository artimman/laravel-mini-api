<?php
/**
 * PetController
 *
 * Kontroler obsługujący operacje CRUD dla zwierząt poprzez API.
 *
 * @author (C) Design by Malina
 * @package App\Http\Controllers
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\PetRequest;
use App\DTO\PetDTO;
use App\Services\PetService;

/**
 * Klasa PetController zarządza operacjami CRUD dla zwierząt.
 */
class PetController extends Controller
{
    /**
     * @var PetService $petService Serwis do obsługi zwierząt.
     */
    protected PetService $petService;

    /**
     * Konstruktor PetController.
     *
     * @param PetService $petService Serwis zwierząt.
     */
    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    /**
     * Zwraca widok formularza zarządzania zwierzętami.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pet.index');
    }

    /**
     * Pobiera dane zwierzęcia na podstawie ID.
     *
     * @param int $id ID zwierzęcia.
     * @return JsonResponse JSON z danymi zwierzęcia.
     */
    public function getPet(int $id): JsonResponse
    {
        return response()->json($this->petService->getPetById($id));
    }

    /**
     * Tworzy nowe zwierzę na podstawie danych z formularza.
     *
     * @param PetRequest $request Obiekt żądania z danymi wejściowymi.
     * @return JsonResponse JSON z nowo utworzonym zwierzęciem.
     */
    public function createPet(PetRequest $request): JsonResponse
    {
        $petData = new PetDTO($request->validated());
        return response()->json($this->petService->addPet($petData->toArray()));
    }

    /**
     * Aktualizuje dane zwierzęcia.
     *
     * @param PetRequest $request Obiekt żądania zawierający nowe dane.
     * @param int $id ID zwierzęcia do aktualizacji.
     * @return JsonResponse JSON z danymi zaktualizowanego zwierzęcia.
     */
    public function updatePet(PetRequest $request, int $id): JsonResponse
    {
        $petData = new PetDTO($request->validated(), $id);
        return response()->json($this->petService->updatePet($id, $petData->toArray()));
    }

    /**
     * Usuwa zwierzę na podstawie ID.
     *
     * @param int $id ID zwierzęcia do usunięcia.
     * @return JsonResponse JSON z wynikiem operacji.
     */
    public function deletePet(int $id): JsonResponse
    {
        return response()->json($this->petService->deletePet($id));
    }
}
