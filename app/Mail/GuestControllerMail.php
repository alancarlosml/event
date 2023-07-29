<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Event;
use App\Models\Participante;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestControllerMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Event
     */
    protected $event;
    public $subject;
    public $guest;
    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Event $event, $subject, Participante $guest, Participante $admin)
    {
        $this->event = $event;
        $this->subject = $subject;
        $this->guest = $guest;
        $this->admin = $admin;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.guest_invite_mail',
            with: [
                'event' => $this->event,
                'guest' => $this->guest,
                'admin' => $this->admin,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
