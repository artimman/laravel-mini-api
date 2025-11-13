<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PetService
{
    private string $apiUrl;
    private bool $verifySSL;

    public function __construct()
    {
        $this->apiUrl = config('petstore.api_url');
        $this->verifySSL = config('petstore.verify_ssl');
    }

    public function getPetById(int $id): array
    {
        $response = Http::withOptions(['verify' => $this->verifySSL])->get("{$this->apiUrl}/{$id}");
        return $response->json();
    }

    public function addPet(array $data): array
    {
        $response = Http::withOptions(['verify' => $this->verifySSL])->post($this->apiUrl, $data);
        return $response->json();
    }

    public function updatePet(int $id, array $data): array
    {
        $data['id'] = $id;
        $response = Http::withOptions(['verify' => $this->verifySSL])->put($this->apiUrl, $data);
        return $response->json();
    }

    public function deletePet(int $id): array
    {
        $response = Http::withOptions(['verify' => $this->verifySSL])->delete("{$this->apiUrl}/{$id}");
        return ['status' => $response->status()];
    }
}
