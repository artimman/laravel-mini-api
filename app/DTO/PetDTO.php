<?php

declare(strict_types=1);

namespace App\DTO;

class PetDTO
{
    public int $id;
    public array $category;
    public string $name;
    public array $photoUrls;
    public array $tags;
    public string $status;

    public function __construct(array $data, ?int $id = null)
    {
        $this->id = $id ?? random_int(1000, 9999);
        $this->category = [
            'id' => $data['category_id'] ?? 0,
            'name' => $data['category_name'] ?? 'Uncategorized'
        ];
        $this->name = $data['name'];
        $this->photoUrls = array_map('trim', explode(',', $data['photo_urls'] ?? ''));
        $this->tags = array_map(fn ($tag) => ['id' => 0, 'name' => trim($tag)], explode(',', $data['tags'] ?? ''));
        $this->status = $data['status'];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category,
            'name' => $this->name,
            'photoUrls' => $this->photoUrls,
            'tags' => $this->tags,
            'status' => $this->status,
        ];
    }
}
