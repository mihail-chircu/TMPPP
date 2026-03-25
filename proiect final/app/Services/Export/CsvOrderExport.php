<?php

namespace App\Services\Export;

use App\Models\Order;

/**
 * Template Method Pattern — Concrete Class (CSV).
 *
 * Implements the CSV-specific steps of the export algorithm.
 */
class CsvOrderExport extends OrderExportTemplate
{
    protected function getHeader(): string
    {
        return "Numar,Client,Email,Telefon,Oras,Total (MDL),Status,Metoda plata,Data\n";
    }

    protected function formatOrder(Order $order): string
    {
        return implode(',', [
            $order->order_number,
            '"' . str_replace('"', '""', $order->customer_name) . '"',
            $order->customer_email,
            $order->customer_phone ?? '',
            '"' . str_replace('"', '""', $order->shipping_city) . '"',
            $order->total,
            $order->status,
            $order->payment_method,
            $order->created_at->format('Y-m-d H:i'),
        ]) . "\n";
    }

    protected function getFooter(): string
    {
        return '';
    }

    public function getContentType(): string
    {
        return 'text/csv';
    }

    public function getFileExtension(): string
    {
        return 'csv';
    }
}
