<?php
namespace App\Actions\Organization;
use App\DTOs\OrganizationUserDTO;
use App\Models\Organization;
use App\Models\OrganizationUser;
use Illuminate\Support\Facades\DB;
use Laravel\Prompts\Key;

final class createOrganizationUserAction
{
    public function __construct() {}

    /**
     * create a organizationUser
     * @param OrganizationUserDTO $dto
     * @return array
     */
    public function execute(OrganizationUserDTO $dto): OrganizationUser
    {
        $organizationUser = OrganizationUser::create([
            'user_id'           => $dto->user_id,
            'organization_id'   => $dto->organization_id,
            'created_at'        => $dto->created_at,
            'updated_at'        => $dto->updated_at,
        ]);

        return $organizationUser;
    }
}