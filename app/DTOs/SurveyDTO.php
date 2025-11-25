<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyDTO
{
      public function __construct(
        public string $title,
        public string $description,
        public string $start_date,
        public string $end_date,
        public bool $is_anonymous,
        public int $organization_id,
        public int $user_id,
    ) {}

      public static function fromRequest($request)
        {
            return new self(
                $request->title,
                $request->description,
                $request->start_date,
                $request->end_date,
                $request->boolean('is_anonymous'),
                1, 
                $request->user()->id
            );
        }



}
