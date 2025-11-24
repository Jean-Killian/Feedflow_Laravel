<?php

namespace App\Http\Controllers;

use App\Actions\Organization\StoreOrganizationAction;
use App\Http\Requests\Organization\StoreOrganization;
use App\DTOs\OrganizationDTO;

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

        return response()->json([
            'message' => 'Created Organization successfully.',
            'data'    => $organization,
        ], 201);
    }
}
