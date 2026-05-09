<?php

namespace App\Modules\Items\DTO;
use Illuminate\Support\Facades\Auth;
class ItemData
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $type,
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?string $contact_number,
        public readonly ?string $date_lost,
        public readonly string $location_text,
        public ?int $primary_index = null,

    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: Auth::id(),
            type: $data['type'],
            title: $data['title'],
            description: $data['description'] ?? null,
            contact_number: $data['contact_number'] ?? null,
            date_lost: $data['date_lost'] ?? null,
            location_text: $data['location_text'],
            primary_index: $data['primary_index'] ?? null,
        );
    }
}