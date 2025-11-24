<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use Illuminate\Support\Facades\DB;

final class DeleteOrganizationAction
{
    public function __construct() {}

    /**
     * Delete an organization
     * @param OrganizationDTO $dto
     * @return array
     */
    public function handle(OrganizationDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
        });
    }
}
