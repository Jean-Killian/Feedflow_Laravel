<?php

namespace App\Mail;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAnswerNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Survey $survey,
        public SurveyAnswer $answer,
    )
    {
        //
    }

    /**
     * Get the message envelope.
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): envelope
    {
        return new Envelope(
            subject: 'Nouvelle réponse au sondage reçue : ' . $this->survey->title,
        );
    }

    /**
     * Get the message content definition.
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_answer_notification',
        );
    }  
    
    /**
     * Get the attachments for the message.
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}