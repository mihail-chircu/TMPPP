<?php

namespace App\Services\Export;

use App\Models\Order;

/**
 * Template Method Pattern — Concrete Class (JSON).
 *
 * Implements the JSON-specific steps of the export algorithm.
 */
class JsonOrderExport extends OrderExportTemplate
{
    private bool $isFirst = true;

    protected function getHeader(): string
    {
        $this->isFirst = true;

        return "[\n";
    }

    protected function formatOrder(Order $order): string
    {
        $prefix = $this->isFirst ? '' : ",\n";
        $this->isFirst = false;

        $data = [
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'shipping_city' => $order->shipping_city,
            'shipping_address' => $order->shipping_address,
            'total' => (float) $order->total,
            'subtotal' => (float) $order->subtotal,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'items_count' => $order->items->count(),
            'created_at' => $order->created_at->toIso8601String(),
        ];

        return $prefix . '  ' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    protected function getFooter(): string
    {
        return "\n]\n";
    }

    public function getContentType(): string
    {
        return 'application/json';
    }

    public function getFileExtension(): string
    {
        return 'json';
    }
}
