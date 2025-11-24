<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use Illuminate\Support\Facades\DB;

final class UpdateSurveyAction
{
    public function __construct() {}

    /**
     * Update a Survey
     * @param SurveyDTO $dto
     * @return array
     */
    public function handle(SurveyDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
        });
    }
}
