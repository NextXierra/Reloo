<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice: {{ $order->order_number }} - Reloo</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; color: #1e293b; margin: 0; padding: 32px; font-size: 13px; line-height: 1.5; }
        .invoice-box { max-width: 800px; margin: auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .header-table { width: 100%; margin-bottom: 24px; }
        .logo { font-size: 24px; font-weight: 900; color: #4f46e5; }
        .invoice-title { font-size: 20px; font-weight: 800; text-align: right; }
        .details-table { width: 100%; margin-bottom: 24px; border-collapse: collapse; }
        .details-table th, .details-table td { padding: 8px 12px; text-align: left; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .items-table th { background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 10px 12px; font-size: 11px; text-transform: uppercase; color: #64748b; }
        .items-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; }
        .totals-table { width: 300px; margin-left: auto; border-collapse: collapse; }
        .totals-table td { padding: 6px 12px; }
        .grand-total { font-size: 16px; font-weight: 900; color: #4f46e5; border-top: 2px solid #e2e8f0; }
        @media print {
            body { padding: 0; }
            .invoice-box { border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="no-print" style="margin-bottom: 20px; text-align: right;">
            <button onclick="window.print()" style="padding: 8px 16px; background: #4f46e5; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                Cetak Dokumen Invoice
            </button>
        </div>

        <table class="header-table">
            <tr>
                <td>
                    <div class="logo">RELOO</div>
                    <div style="font-size: 11px; color: #64748b;">Marketplace E-Commerce Terkurasi</div>
                    <div style="font-size: 11px; color: #64748b;">PT Reloo Marketplace Indonesia</div>
                </td>
                <td class="invoice-title">
                    INVOICE PESANAN
                    <div style="font-size: 12px; font-weight: normal; color: #64748b; font-family: monospace;">{{ $order->order_number }}</div>
                    <div style="font-size: 11px; font-weight: normal; color: #64748b;">Tanggal: {{ $order->created_at->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>

        @php
            $addr = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
        @endphp
        <table class="details-table">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <strong style="color: #64748b; font-size: 11px; text-transform: uppercase;">DITUJUKAN KEPADA:</strong><br>
                    <strong>{{ $addr['recipient_name'] ?? '-' }}</strong> ({{ $addr['phone_number'] ?? '-' }})<br>
                    {{ $addr['address_line'] ?? '-' }}<br>
                    {{ $addr['city_name'] ?? '-' }}, {{ $addr['province_name'] ?? '-' }} {{ $addr['postal_code'] ?? '-' }}
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <strong style="color: #64748b; font-size: 11px; text-transform: uppercase;">METODE PENGIRIMAN & PEMBAYARAN:</strong><br>
                    Kurir: <strong>{{ $order->shipment ? $order->shipment->courier_name : '-' }}</strong><br>
                    Resi: <strong>{{ $order->shipment && $order->shipment->tracking_number ? $order->shipment->tracking_number : 'Belum Terbit' }}</strong><br>
                    Metode Bayar: <strong>{{ strtoupper($order->payment ? $order->payment->payment_gateway : '-') }}</strong> (Status: {{ strtoupper($order->payment ? $order->payment->payment_status : '-') }})
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td><strong>{{ $item->product_name }}</strong></td>
                        <td style="text-align: right;">{{ $item->formatted_price }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;"><strong>{{ $item->formatted_subtotal }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal Barang:</td>
                <td style="text-align: right;">Rp {{ number_format($order->items_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Ongkos Kirim:</td>
                <td style="text-align: right;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td>Total Tagihan:</td>
                <td style="text-align: right;">{{ $order->formatted_grand_total }}</td>
            </tr>
        </table>

        <div style="margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 12px; font-size: 11px; color: #94a3b8; text-align: center;">
            Terima kasih telah berbelanja di Reloo Marketplace. Dokumen ini adalah bukti transaksi yang sah dan diterbitkan secara komputerisasi.
        </div>
    </div>
</body>
</html>
