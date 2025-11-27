<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\SurveyAnswerSubmitted;
use App\Mail\NewAnswerNotification;

class SendNewAnswerNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     * @param  object  $event
     */
    public function handle(SurveyAnswerSubmitted $event): void
    {
        $owner = $event->survey->owner;

        if (!$owner || !$owner->email) {
            return;
        }

        if (!$owner->email_notifications_enabled) {
            return;
        }
        Mail::to($owner->email)->queue(
            new NewAnswerNotification(
                $event->survey,
                $event->answer,
            )
        );
    }    
}

