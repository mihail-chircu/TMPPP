<?php

namespace App\Services\Export;

use App\Models\Order;
use Illuminate\Support\Collection;

/**
 * Template Method Pattern — Abstract Class.
 *
 * Defines the skeleton of the export algorithm in export().
 * Subclasses override getHeader(), formatOrder(), and getFooter()
 * to produce different output formats (CSV, JSON, etc.).
 */
abstract class OrderExportTemplate
{
    /**
     * Template Method: defines the export algorithm structure.
     * Subclasses must NOT override this method.
     */
    final public function export(Collection $orders): string
    {
        $output = $this->getHeader();

        foreach ($orders as $order) {
            $output .= $this->formatOrder($order);
        }

        $output .= $this->getFooter();

        return $output;
    }

    /**
     * Produce the file header (e.g., CSV column names, JSON opening bracket).
     */
    abstract protected function getHeader(): string;

    /**
     * Format a single order record.
     */
    abstract protected function formatOrder(Order $order): string;

    /**
     * Produce the file footer (e.g., closing bracket for JSON).
     */
    abstract protected function getFooter(): string;

    /**
     * MIME type for the HTTP response.
     */
    abstract public function getContentType(): string;

    /**
     * File extension for the download.
     */
    abstract public function getFileExtension(): string;
}
