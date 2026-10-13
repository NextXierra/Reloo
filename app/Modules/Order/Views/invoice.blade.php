<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice: {{ $order->order_number }} · Reloo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/reloo-icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|instrument-serif:400|geist-mono:400,500,600" rel="stylesheet" />
    <style>
        :root {
            --color-ink: #111111;
            --color-rule: #EAEAEA;
            --color-subtle: #F7F6F3;
        }
        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--color-ink);
            background: #FFFFFF;
            margin: 0;
            padding: 40px;
            font-size: 12px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .invoice-box {
            max-width: 820px;
            margin: auto;
            padding: 36px;
            border: 1px solid var(--color-rule);
            border-radius: 6px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 28px;
            border-bottom: 1px solid var(--color-rule);
            padding-bottom: 20px;
        }
        .logo-wordmark {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -0.03em;
            color: #111111;
            display: inline-flex;
            align-items: center;
        }
        .dot-violet {
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background-color: #6828FF;
            margin: 0 2px 0 3px;
        }
        .dot-pink {
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background-color: #FA26A0;
        }
        .invoice-title {
            text-align: right;
            vertical-align: top;
        }
        .invoice-title h2 {
            margin: 0 0 4px 0;
            font-family: 'Instrument Serif', Georgia, serif;
            font-size: 24px;
            font-weight: normal;
            letter-spacing: -0.02em;
        }
        .mono {
            font-family: 'Geist Mono', Menlo, monospace;
            font-variant-numeric: tabular-nums;
        }
        .details-table {
            width: 100%;
            margin-bottom: 28px;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 12px 14px;
            vertical-align: top;
            border: 1px solid var(--color-rule);
            background: var(--color-subtle);
            border-radius: 4px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .items-table th {
            background: var(--color-subtle);
            border-top: 1px solid var(--color-rule);
            border-bottom: 1px solid var(--color-rule);
            padding: 10px 12px;
            font-family: 'Geist Mono', monospace;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #71717A;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid var(--color-rule);
        }
        .totals-table {
            width: 320px;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .totals-table td {
            padding: 6px 10px;
        }
        .grand-total {
            font-size: 15px;
            font-weight: 700;
            border-top: 1px solid var(--color-rule);
            border-bottom: 1px solid var(--color-rule);
            background: var(--color-subtle);
        }
        .print-btn {
            padding: 8px 16px;
            background: #111111;
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            transition: background 150ms;
        }
        .print-btn:hover {
            background: #27272A;
        }
        .verification-stamp {
            display: inline-block;
            border: 1px dashed #26542A;
            background: #EDF3EC;
            color: #26542A;
            padding: 6px 12px;
            border-radius: 4px;
            font-family: 'Geist Mono', monospace;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        @media print {
            body { padding: 0; }
            .invoice-box { border: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="no-print" style="margin-bottom: 24px; text-align: right;">
            <button onclick="window.print()" class="print-btn">
                Cetak Dokumen Invoice
            </button>
        </div>

        <table class="header-table">
            <tr>
                <td style="vertical-align: top;">
                    <div class="logo-wordmark">
                        REL<span class="dot-violet"></span><span class="dot-pink"></span>
                    </div>
                    <div style="font-size: 10px; font-family: 'Geist Mono', monospace; text-transform: uppercase; color: #71717A; margin-top: 4px;">
                        PASAR BARANG TERKURASI · 10-TITIK VERIFIKASI
                    </div>
                    <div style="font-size: 11px; color: #52525B; margin-top: 2px;">PT Reloo Marketplace Indonesia</div>
                </td>
                <td class="invoice-title">
                    <h2>INVOICE RESMI</h2>
                    <div class="mono" style="font-size: 12px; font-weight: 600;">{{ $order->order_number }}</div>
                    <div class="mono" style="font-size: 11px; color: #71717A; margin-top: 2px;">Tanggal: {{ $order->created_at->format('d/m/Y H:i') }} WIB</div>
                </td>
            </tr>
        </table>

        @php
            $addr = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
        @endphp
        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <div class="mono" style="font-size: 10px; text-transform: uppercase; color: #71717A; margin-bottom: 4px;">PENGIRIMAN DITUJUKAN KEPADA:</div>
                    <strong>{{ $addr['recipient_name'] ?? '-' }}</strong> <span class="mono" style="color: #71717A;">({{ $addr['phone_number'] ?? '-' }})</span><br>
                    <span style="color: #52525B;">
                        {{ $addr['address_line'] ?? '-' }}<br>
                        {{ $addr['city_name'] ?? '-' }}, {{ $addr['province_name'] ?? '-' }} {{ $addr['postal_code'] ?? '-' }}
                    </span>
                </td>
                <td style="width: 50%;">
                    <div class="mono" style="font-size: 10px; text-transform: uppercase; color: #71717A; margin-bottom: 4px;">LOGISTIK & STATUS PEMBAYARAN:</div>
                    Kurir: <strong>{{ $order->shipment ? $order->shipment->courier_name : '-' }}</strong> ({{ $order->shipment ? $order->shipment->courier_service : '-' }})<br>
                    Resi: <span class="mono"><strong>{{ $order->shipment && $order->shipment->tracking_number ? $order->shipment->tracking_number : 'Menunggu Input Resi' }}</strong></span><br>
                    Metode Bayar: <span class="mono">{{ strtoupper($order->payment ? $order->payment->payment_gateway : '-') }}</span> · Status: <span class="mono">{{ strtoupper($order->payment ? $order->payment->payment_status : '-') }}</span>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Rincian Barang</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            <div class="mono" style="font-size: 10px; color: #71717A;">Berat: {{ $item->weight_grams }}g</div>
                        </td>
                        <td class="mono" style="text-align: right;">{{ $item->formatted_price }}</td>
                        <td class="mono" style="text-align: center;">{{ $item->quantity }}</td>
                        <td class="mono" style="text-align: right; font-weight: 600;">{{ $item->formatted_subtotal }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td style="color: #52525B;">Subtotal Produk:</td>
                <td class="mono" style="text-align: right;">Rp {{ number_format($order->items_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="color: #52525B;">Biaya Pengiriman:</td>
                <td class="mono" style="text-align: right;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td>Total Pembayaran:</td>
                <td class="mono" style="text-align: right;">{{ $order->formatted_grand_total }}</td>
            </tr>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 36px; padding-top: 16px; border-top: 1px solid var(--color-rule);">
            <div>
                <span class="verification-stamp">
                    DOKUMEN RESMI TERVERIFIKASI
                </span>
                <p style="font-size: 10px; color: #71717A; margin-top: 6px; margin-bottom: 0;">
                    Dokumen ini adalah bukti transaksi sah yang diterbitkan oleh sistem komputerisasi Reloo.
                </p>
            </div>
            <div class="mono" style="font-size: 10px; color: #A1A1AA; text-align: right;">
                HASH-ID: {{ substr(md5($order->order_number . $order->created_at), 0, 16) }}
            </div>
        </div>
    </div>
</body>
</html>
