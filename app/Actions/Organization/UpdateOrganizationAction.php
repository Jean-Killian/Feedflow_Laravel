<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

final class UpdateOrganizationAction
{
    public function __construct() {}

    /**
     * Update an organization
     * @param OrganizationDTO $dto
     * @return array
     */
    public function execute(OrganizationDTO $dto, Organization $organization): Organization
    {
        $organization->update([
            'name'      => $dto->name,
            'user_id'   => $dto->user_id,
            'created_at'=> $dto->created_at,
            'updated_at'=> $dto->updated_at,
        ]);
        return $organization;
    }

}
