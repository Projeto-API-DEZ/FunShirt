<?php

namespace App\Helpers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReceiptHelper
{
    /**
     * Generates Receipt for each order
     *
     * @param Order $order
     * @return void
     */
    public static function generate(Order $order): void
    {
        // Load the PDF view with order data
        $pdf = Pdf::loadView('pdf.receipt', compact('order'));
        
        // Generate a unique filename
        $filename = 'receipt_' . $order->id . '_' . time() . '.pdf';
        
        // Store the PDF in the private disk
        Storage::disk('private')->put('pdf_receipts/' . $filename, $pdf->output());
        
        // Update the order's receipt_url and save quietly (to avoid event loops)
        $order->receipt_url = $filename;
        $order->saveQuietly();
    }
}