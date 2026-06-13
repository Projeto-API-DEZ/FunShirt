<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderClosedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your order has been shipped - FunShirt',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.orders.order_closed',
        );
    }

    public function attachments(): array
    {
        if ($this->order->receipt_url && \Storage::disk('private')->exists('pdf_receipts/' . $this->order->receipt_url)) {
            return [
                Attachment::fromStorageDisk('private', 'pdf_receipts/' . $this->order->receipt_url)
                    ->as('receipt.pdf')
                    ->withMime('application/pdf'),
            ];
        }
        return [];
    }
}
