<?php

namespace App\Services;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class PrintService
{
    protected $printer;

    public function __construct()
    {
        $type = env('PRINTER_CONNECTION_TYPE', 'windows');
        $path = env('PRINTER_PATH', 'POS-58');
        try {
            if ($type === 'windows') {
                $connector = new WindowsPrintConnector($path);
            } elseif ($type === 'network') {
                $connector = new NetworkPrintConnector(env('PRINTER_NETWORK_IP'), env('PRINTER_NETWORK_PORT'));
            } else {
                $connector = new FilePrintConnector($path);
            }
            $this->printer = new Printer($connector);
        } catch (\Exception $e) {
            Log::error('Printer connection failed: ' . $e->getMessage());
            $this->printer = null;
        }
    }

    public function printReceipt(Transaction $transaction)
    {
        if (!$this->printer) return false;
        try {
            $this->printer->initialize();
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text("Emily Coffe\n");
            $this->printer->text("Jl. Padang\n");
            $this->printer->text("Telp: 087716773689\n");
            $this->printer->text("==============================\n");
            $this->printer->setJustification(Printer::JUSTIFY_LEFT);
            $this->printer->text("Invoice: {$transaction->invoice_number}\n");
            $this->printer->text("Queue: {$transaction->queue_number}\n");
            $this->printer->text("Table: " . ($transaction->table->table_number ?? 'Take Away') . "\n");
            $this->printer->text("Cashier: {$transaction->cashier->name}\n");
            $this->printer->text("Date: {$transaction->transaction_date->format('d/m/Y H:i')}\n");
            $this->printer->text("----------------------------\n");
            foreach ($transaction->items as $item) {
                $this->printer->text($item->product->name . "\n");
                $this->printer->text("  {$item->quantity} x " . number_format($item->price) . " = " . number_format($item->subtotal) . "\n");
            }
            $this->printer->text("----------------------------\n");
            $this->printer->text("Total: " . number_format($transaction->total_amount) . "\n");
            $this->printer->text("Paid: " . number_format($transaction->paid_amount) . "\n");
            $this->printer->text("Change: " . number_format($transaction->change_amount) . "\n");
            $this->printer->text("Payment: " . ucfirst($transaction->payment_method) . "\n");
            $this->printer->text("==============================\n");
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text("Terima Kasih!\n");
            $this->printer->feed(2);
            $this->printer->cut();
            $this->printer->close();
            return true;
        } catch (\Exception $e) {
            Log::error('Print receipt error: ' . $e->getMessage());
            return false;
        }
    }

    public function printKOT(Transaction $transaction)
    {
        if (!$this->printer) return false;
        try {
            $this->printer->initialize();
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text("=== KITCHEN ORDER TICKET ===\n");
            $this->printer->setJustification(Printer::JUSTIFY_LEFT);
            $this->printer->text("Queue: {$transaction->queue_number}\n");
            $this->printer->text("Table: " . ($transaction->table->table_number ?? 'Take Away') . "\n");
            $this->printer->text("DateTime: " . now()->format('d/m/Y H:i') . "\n");
            $this->printer->text("----------------------------\n");
            foreach ($transaction->items as $item) {
                $this->printer->text($item->quantity . "x " . $item->product->name . "\n");
            }
            $this->printer->text("----------------------------\n");
            $this->printer->feed(2);
            $this->printer->cut();
            $this->printer->close();
            return true;
        } catch (\Exception $e) {
            Log::error('Print KOT error: ' . $e->getMessage());
            return false;
        }
    }
}
