<?php

namespace App\Modules\Permission\DTO;
use App\Enums\PermissionStatus;

class PermissionData
{
    public function __construct(
        public readonly string $name,
        public readonly int $group_id,
          public readonly PermissionStatus $status, 
    ) {}

public static function fromArray(array $data): self
{
    return new self(
        name: $data['name'],
        group_id: (int) $data['group_id'],
        status: isset($data['status'])
            ? PermissionStatus::from($data['status'])
            : PermissionStatus::ACTIVE,
    );
}
}