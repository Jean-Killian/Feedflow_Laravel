<?php

namespace App\DTOs;

use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;

final class OrganizationUserDTO
{
    private function __construct(
        public readonly int $user_id,
        public readonly int $organization_id,
        public readonly string $role,
        public readonly Carbon $created_at,
        public readonly Carbon $updated_at,
    ) {}

    public static function fromRequest(Request $request, Organization $organization): self
    {
        return new self(
            user_id: $request->user()->id,
            organization_id: $organization->id,
            role: $request->role,
            created_at: now(),
            updated_at: now(),
            
        );
    }

}