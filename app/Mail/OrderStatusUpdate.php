<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OrderStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your order status has been updated - FunShirt',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.status_updated',
        );
    }

    public function attachments(): array
    {
        // Only attach the receipt if the order is closed and the file exists
        if ($this->order->status === 'closed' && 
            $this->order->receipt_url && 
            Storage::disk('private')->exists('pdf_receipts/' . $this->order->receipt_url)) {
            return [
                Attachment::fromStorageDisk('private', 'pdf_receipts/' . $this->order->receipt_url)
                    ->as('receipt.pdf')
                    ->withMime('application/pdf'),
            ];
        }
        return [];
    }
}