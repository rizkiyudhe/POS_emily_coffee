<?php

namespace App\Helpers;

use App\Models\Transaction;

class TransactionHelper
{
    public static function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');
        $last = Transaction::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();
        $sequence = $last ? intval(substr($last->invoice_number, -4)) + 1 : 1;
        return 'INV-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public static function generateQueueNumber()
    {
        $today = now()->toDateString();
        $last = Transaction::whereDate('created_at', $today)
            ->orderBy('queue_number', 'desc')
            ->first();
        if (!$last) {
            $number = 1;
        } else {
            $lastNum = intval(substr($last->queue_number, 1));
            $number = $lastNum + 1;
        }
        return 'A' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
