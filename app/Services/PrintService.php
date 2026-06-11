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

    // 1. Cetak untuk KONSUMEN (lengkap harga, total, kembalian, diskon, pajak, service)
    public function printReceiptCustomer(Transaction $transaction)
    {
        if (!$this->printer) return false;
        try {
            $this->printer->initialize();
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text(strtoupper(store_setting('store_name', 'NAMA TOKO')) . "\n");
            $this->printer->text(store_setting('store_address', 'Alamat belum diatur') . "\n");
            $this->printer->text("Telp: " . store_setting('store_phone', '-') . "\n");
            $this->printer->text("==============================\n");
            $this->printer->setJustification(Printer::JUSTIFY_LEFT);
            $this->printer->text("Invoice: {$transaction->invoice_number}\n");
            $this->printer->text("Queue: {$transaction->queue_number}\n");
            $this->printer->text("Table: " . ($transaction->table->table_number ?? 'Take Away') . "\n");
            $this->printer->text("Cashier: {$transaction->cashier->name}\n");
            $this->printer->text("Date: {$transaction->transaction_date->format('d/m/Y H:i')}\n");
            $this->printer->text("------------------------------\n");

            // Looping Item
            foreach ($transaction->items as $item) {
                $this->printer->text($item->product->name . "\n");

                // ✅ Menampilkan Catatan di Struk Konsumen
                if (!empty($item->notes)) {
                    $this->printer->text("  * Catatan: {$item->notes}\n");
                }

                $this->printer->text("  {$item->quantity} x " . number_format($item->price) . " = " . number_format($item->subtotal) . "\n");
            }
            $this->printer->text("------------------------------\n");

            // ✅ Menampilkan Subtotal & Diskon secara detail
            if ($transaction->discount_amount > 0) {
                $subtotalAwal = $transaction->total_amount + $transaction->discount_amount;
                $this->printer->text("Subtotal : " . number_format($subtotalAwal) . "\n");

                $labelDiskon = $transaction->discount_type === 'percentage' ? "{$transaction->discount_value}%" : "Nominal";
                $this->printer->text("Diskon ({$labelDiskon}): -" . number_format($transaction->discount_amount) . "\n");
            }

            if ($transaction->tax_amount > 0) {
                $this->printer->text("Pajak    : " . number_format($transaction->tax_amount) . "\n");
            }
            if ($transaction->service_amount > 0) {
                $this->printer->text("Service  : " . number_format($transaction->service_amount) . "\n");
            }

            $this->printer->text("==============================\n");
            $this->printer->text("TOTAL    : " . number_format($transaction->total_amount) . "\n");
            $this->printer->text("TUNAI    : " . number_format($transaction->paid_amount) . "\n");
            $this->printer->text("KEMBALI  : " . number_format($transaction->change_amount) . "\n");
            $this->printer->text("METODE   : " . strtoupper($transaction->payment_method) . "\n");
            $this->printer->text("==============================\n");
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text("Terima Kasih!\n");
            $this->printer->feed(2);
            $this->printer->cut();
            $this->printer->close();
            return true;
        } catch (\Exception $e) {
            Log::error('Print customer receipt error: ' . $e->getMessage());
            return false;
        }
    }

    // 2. Cetak untuk CHECKER (tanpa harga, hanya nama item & quantity)
    public function printChecker(Transaction $transaction)
    {
        if (!$this->printer) return false;
        try {
            $this->printer->initialize();
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text("=== CHECKER COPY ===\n");
            $this->printer->setJustification(Printer::JUSTIFY_LEFT);
            $this->printer->text("Queue: {$transaction->queue_number}\n");
            $this->printer->text("Table: " . ($transaction->table->table_number ?? 'Take Away') . "\n");
            $this->printer->text("Date: " . now()->format('d/m/Y H:i') . "\n");
            $this->printer->text("------------------------------\n");

            // Looping Item
            foreach ($transaction->items as $item) {
                $this->printer->text($item->quantity . "x " . $item->product->name . "\n");

                // ✅ Menampilkan Catatan di Struk Checker
                if (!empty($item->notes)) {
                    $this->printer->text("  * Catatan: {$item->notes}\n");
                }
            }

            $this->printer->text("------------------------------\n");
            $this->printer->text("Server: " . $transaction->cashier->name . "\n");
            $this->printer->feed(2);
            $this->printer->cut();
            $this->printer->close();
            return true;
        } catch (\Exception $e) {
            Log::error('Print checker error: ' . $e->getMessage());
            return false;
        }
    }

    // 3. Cetak untuk DAPUR (hanya item makanan, tanpa harga)
    public function printKitchen(Transaction $transaction)
    {
        if (!$this->printer) return false;
        // Filter item dengan kategori makanan (misal category_id = 1)
        $foodItems = $transaction->items->filter(function ($item) {
            return $item->product->category_id === 1; // Sesuaikan ID kategori makanan
        });
        if ($foodItems->isEmpty()) {
            Log::info('No food items, kitchen print skipped.');
            return true; // tidak perlu cetak
        }
        try {
            $this->printer->initialize();
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text("=== KITCHEN ORDER ===\n");
            $this->printer->setJustification(Printer::JUSTIFY_LEFT);
            $this->printer->text("Queue: {$transaction->queue_number}\n");
            $this->printer->text("Table: " . ($transaction->table->table_number ?? 'Take Away') . "\n");
            $this->printer->text("DateTime: " . now()->format('d/m/Y H:i') . "\n");
            $this->printer->text("------------------------------\n");

            // Looping Item Makanan
            foreach ($foodItems as $item) {
                $this->printer->text($item->quantity . "x " . $item->product->name . "\n");

                // ✅ Menampilkan Catatan di Struk Dapur
                if (!empty($item->notes)) {
                    $this->printer->text("  * Catatan: {$item->notes}\n");
                }
            }

            $this->printer->text("------------------------------\n");
            $this->printer->feed(2);
            $this->printer->cut();
            $this->printer->close();
            return true;
        } catch (\Exception $e) {
            Log::error('Print kitchen error: ' . $e->getMessage());
            return false;
        }
    }

    // Opsional: method close jika printer tidak digunakan lagi
    public function close()
    {
        if ($this->printer) {
            $this->printer->close();
        }
    }
}
