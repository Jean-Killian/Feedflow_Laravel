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
                title: $request->title,
                description: $request->description,
                start_date: $request->start_date,
                end_date: $request->end_date,
                is_anonymous: $request->has('is_anonymous'),
                organization_id: 1, 
                user_id: $request->user()->id
            );
        }



}
