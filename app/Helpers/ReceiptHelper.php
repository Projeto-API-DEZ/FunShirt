<?php

namespace App\Helpers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReceiptHelper
{
    public static function generate(Order $order)
    {
        $pdf = Pdf::loadView('pdf.receipt', compact('order'));
        $filename = 'receipt_' . $order->id . '_' . time() . '.pdf';
        $path = 'pdf_receipts/' . $filename;
        Storage::disk('private')->put($path, $pdf->output());

        $order->receipt_url = $filename;
        $order->saveQuietly();

        return $path;
    }
}