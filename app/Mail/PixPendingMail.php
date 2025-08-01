<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PixPendingMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $order;
    protected $pixDetails;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $pixDetails, $subject = 'Pagamento PIX Pendente')
    {
        $this->order = $order;
        $this->pixDetails = $pixDetails;
        $this->subject = $subject;
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
            view: 'mails.pix_pending_mail',
            with: [
                'order' => $this->order,
                'pixDetails' => $this->pixDetails
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
} 