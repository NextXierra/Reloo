<?php

namespace App\Modules\Payment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\Services\QrislyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected QrislyService $qrislyService
    ) {}

    public function pay(string $orderNumber): View|RedirectResponse
    {
        $order = Order::with(['items.product', 'shipment', 'payment', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Access check
        if (Auth::check() && Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Akses ke transaksi pesanan ini ditolak.');
        }

        $qrisData = null;
        if ($order->payment && $order->payment->payment_gateway === 'qrisly') {
            if (!empty($order->payment->gateway_payload)) {
                $qrisData = json_decode($order->payment->gateway_payload, true);
            }

            if (empty($qrisData) || empty($qrisData['qris_string'])) {
                $qrisData = $this->qrislyService->generateQris($order->grand_total, $order->order_number);
                $order->payment->update([
                    'transaction_id' => $qrisData['history_id'],
                    'gateway_payload' => json_encode($qrisData),
                ]);
            }
        }

        return view('payment::pay', compact('order', 'qrisData'));
    }

    public function checkStatus(string $orderNumber): JsonResponse
    {
        $order = Order::with('payment')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->status === 'paid' || ($order->payment && $order->payment->payment_status === 'paid')) {
            return response()->json([
                'status' => 'paid',
                'payment_status' => 'paid',
                'message' => 'Pembayaran berhasil diterima.',
            ]);
        }

        // Query Qrisly API if transaction_id exists
        if ($order->payment && !empty($order->payment->transaction_id)) {
            $apiStatus = $this->qrislyService->checkStatus($order->payment->transaction_id);
            $payStatus = $apiStatus['data']['payment_status'] ?? ($apiStatus['data']['status'] ?? null);

            if ($payStatus === 'paid' || $payStatus === 'success') {
                $order->update(['status' => 'paid']);
                $order->payment->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);

                return response()->json([
                    'status' => 'paid',
                    'payment_status' => 'paid',
                    'message' => 'Pembayaran berhasil diterima.',
                ]);
            }
        }

        return response()->json([
            'status' => 'pending',
            'payment_status' => $order->payment ? $order->payment->payment_status : 'pending',
            'message' => 'Menunggu pembayaran.',
        ]);
    }

    public function uploadProof(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::with('payment')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if (Auth::check() && Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'payment_proof.required' => 'File bukti transfer wajib dipilih.',
            'payment_proof.image' => 'File harus berupa gambar (JPG, PNG, atau WEBP).',
            'payment_proof.max' => 'Ukuran maksimal file bukti transfer adalah 2MB.',
        ]);

        $path = $request->file('payment_proof')->store('proofs', 'public');

        if ($order->payment) {
            $order->payment->update([
                'payment_proof' => $path,
                'payment_status' => 'waiting_verification',
            ]);
        }

        return back()->with('success', 'Bukti transfer berhasil diunggah. Admin toko akan segera memverifikasi pembayaran Anda.');
    }

    public function mockSuccess(string $orderNumber): RedirectResponse
    {
        $order = Order::with(['payment', 'items'])->where('order_number', $orderNumber)->firstOrFail();

        $order->update(['status' => 'paid']);

        if ($order->payment) {
            $order->payment->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'transaction_id' => 'QRISLY-MOCK-' . time(),
            ]);
        }

        return redirect()->route('order.show', $order->order_number)->with('success', 'Pembayaran QRIS berhasil diselesaikan. Pesanan Anda sedang disiapkan.');
    }
}
