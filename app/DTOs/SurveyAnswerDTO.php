<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyAnswerDTO
{
    private function __construct(
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
        );
    }
}
