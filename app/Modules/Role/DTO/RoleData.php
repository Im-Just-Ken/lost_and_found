<?php

namespace App\Modules\Role\DTO;
use App\Enums\RoleStatus;

class RoleData
{
    public function __construct(
        public readonly string $name,
        public readonly int $group_id,
          public readonly RoleStatus $status, 
    ) {}

public static function fromArray(array $data): self
{
    return new self(
        name: $data['name'],
        group_id: (int) $data['group_id'],
        status: isset($data['status'])
            ? RoleStatus::from($data['status'])
            : RoleStatus::ACTIVE,
    );
}
}