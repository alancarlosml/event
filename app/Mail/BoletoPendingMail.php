<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BoletoPendingMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $order;
    protected $boletoDetails;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $boletoDetails, $subject = 'Boleto Bancário Gerado')
    {
        $this->order = $order;
        $this->boletoDetails = $boletoDetails;
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
            view: 'mails.boleto_pending_mail',
            with: [
                'order' => $this->order,
                'boletoDetails' => $this->boletoDetails
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
} 