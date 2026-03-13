<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Export\CsvOrderExport;
use App\Services\Export\JsonOrderExport;
use App\Services\OrderNotificationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function __construct(
        private OrderNotificationService $notificationService,
    ) {}
    public function index(Request $request): View
    {
        $query = Order::with('items');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function (Builder $q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        $newStatus = $validated['status'];

        // State Pattern: validate transition before applying
        if (! $order->state()->canTransitionTo($newStatus)) {
            return back()->with('error', __(
                'Cannot change status from ":from" to ":to".',
                ['from' => $order->state()->getLabel(), 'to' => $newStatus]
            ));
        }

        $order->transitionTo($newStatus);

        // Abstract Factory: notify customer about status change
        $this->notificationService->notifyStatusUpdate($order, $newStatus);

        return back()->with('success', __('Order status updated successfully.'));
    }

    /**
     * Template Method Pattern: export orders in different formats.
     * The export algorithm (header → items → footer) is defined once
     * in OrderExportTemplate; subclasses customize each step.
     */
    public function export(string $format): Response
    {
        $exporter = match ($format) {
            'csv' => new CsvOrderExport(),
            'json' => new JsonOrderExport(),
            default => abort(404, 'Unsupported export format'),
        };

        $orders = Order::with('items')->latest()->get();
        $content = $exporter->export($orders);

        $filename = 'comenzi-kinder-' . now()->format('Y-m-d') . '.' . $exporter->getFileExtension();

        return response($content)
            ->header('Content-Type', $exporter->getContentType())
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
