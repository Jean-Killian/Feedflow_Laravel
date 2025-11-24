<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Http\Request;

final class OrganizationDTO
{
    private function __construct(
        public readonly string $name,
        public readonly int $user_id,
        public readonly Carbon $created_at,
        public readonly Carbon $updated_at,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->name,
            user_id: $request->user()->id,
            created_at: now(),
            updated_at: now(),
            
        );
    }
}
