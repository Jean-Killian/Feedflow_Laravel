<?php

namespace App\Http\Controllers;

use App\Actions\Organization\DeleteOrganizationAction;
use App\Actions\Organization\StoreOrganizationAction;
use App\Actions\Organization\UpdateOrganizationAction;
use App\Http\Requests\Organization\StoreOrganization;
use App\DTOs\OrganizationDTO;
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

        // Si la requête attend du JSON, retourner JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Created Organization successfully.',
                'data'    => $organization,
            ], 201);
        }

        // Sinon, rediriger vers le dashboard
        return redirect()->route('dashboard')->with('success', 'Organisation créée avec succès.');
    }

    public function update(UpdateOrganization $request, UpdateOrganizationAction $action, Organization $organization): JsonResponse
    {
        $dto = OrganizationDTO::fromRequest($request);

        $organizationResult = $action->execute($dto, $organization);


        return response()->json([
            'message' => 'Updated Organization successfully.',
            'data'    => $organizationResult,
        ], 201); 


    }
    


    public function delete(Organization $organization, DeleteOrganizationAction $action): JsonResponse
    {
        $organizationBool = $action->execute($organization);

        return response()->json([
            'message' => 'Deleted Organization successfully.',
            'data'    => $organizationBool,
        ], 201);
    }
}
