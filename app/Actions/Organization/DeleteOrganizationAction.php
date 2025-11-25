<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

final class DeleteOrganizationAction
{
    public function __construct() {}

    /**
     * Delete an organization
     * @param OrganizationDTO $dto
     * @return array
     */
    public function execute (int $id): bool|null
    {
        return Organization::where('id',$id)->delete();
    }
}
