<?php

namespace App\Http\Controllers;

use App\Actions\Organization\createOrganizationUserAction;
use App\Actions\Organization\DeleteOrganizationAction;
use App\Actions\Organization\StoreOrganizationAction;
use App\Actions\Organization\UpdateOrganizationAction;
use App\Http\Requests\Organization\createOrganizationUser;
use App\Http\Requests\Organization\StoreOrganization;
use App\DTOs\OrganizationDTO;
use App\DTOs\OrganizationUserDTO;
use App\Http\Requests\Organization\UpdateOrganization;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use JsonException;

class OrganizationController extends Controller
{
    /**
     * Enregistre une nouvelle Organization
     * Utilise une FormRequest, un DTO et une Action
     */
    public function store(StoreOrganization $request, StoreOrganizationAction $action)
    {
        $dto = OrganizationDTO::fromRequest($request);
    
        $organization = $action->execute($dto);
        return redirect()->back();
    }
    /**
     * Update une Organization
     * Utilise une FormRequest, un DTO et une Action
     */
    public function update(UpdateOrganization $request, UpdateOrganizationAction $action, Organization $organization)
    {
        $dto = OrganizationDTO::fromRequest($request);

        $organizationResult = $action->execute($dto, $organization);
        return redirect()->back();
    }
    

    /**
     * Supprime une Organization
     * Utilise une Action
     */
    public function delete(Organization $organization, DeleteOrganizationAction $action)
    {
        $organizationBool = $action->execute($organization);
        return redirect()->back();
    }
    /**
     * créer un OrganizationUser
     * Utilise une FormRequest, un DTO et une Action
     */
    public function createOrganizationUser(CreateOrganizationUser $request, Organization $organization, createOrganizationUserAction $action)
    {
        $dto = OrganizationUserDTO::fromRequest($request, $organization);

        $organizationUser = $action->execute($dto);
        return redirect()->back();
    }
}
