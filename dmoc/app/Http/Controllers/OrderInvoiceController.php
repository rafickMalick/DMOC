<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class OrderInvoiceController extends Controller
{
    public function clientInvoice(int $orderId): Response
    {
        $order = Order::query()
            ->with(['user', 'items.product', 'zone', 'payments'])
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.invoice', compact('order'));

        return $pdf->download("invoice-order-{$order->id}.pdf");
    }

    public function adminInvoice(int $orderId): Response
    {
        $order = Order::query()
            ->with(['user', 'items.product', 'zone', 'payments'])
            ->findOrFail($orderId);

        $pdf = Pdf::loadView('pdf.invoice', compact('order'));

        return $pdf->download("invoice-order-{$order->id}.pdf");
    }
}
