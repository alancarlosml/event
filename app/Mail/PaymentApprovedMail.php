<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentApprovedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $order;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $subject = '🎉 Pagamento Aprovado!')
    {
        $this->order = $order;
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
            view: 'mails.payment_approved_mail',
            with: [
                'order' => $this->order
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
} 