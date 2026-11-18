<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $transaction->nomor_invoice }} - KopiPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier Prime', monospace;
            background-color: #f5f5f4;
            color: #1c1917;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .receipt-card {
            background: #ffffff;
            width: 320px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider {
            border-top: 1px dashed #78716c;
            margin: 12px 0;
        }
        .line-item {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 6px;
        }
        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-print {
            background-color: #d97706;
            color: #ffffff;
        }
        .btn-close {
            background-color: #e7e5e4;
            color: #44403c;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-card {
                width: 100%;
                box-shadow: none;
                padding: 0;
            }
            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        <div class="text-center">
            <h2 style="margin: 0 0 4px 0; font-size: 18px; font-weight: 700;">KEDAI KOPI POS</h2>
            <p style="margin: 0; font-size: 11px;">Jl. Kopi Harum No. 12, Jakarta</p>
            <p style="margin: 0; font-size: 11px;">Telp: (021) 8899-7766</p>
        </div>

        <div class="divider"></div>

        <div style="font-size: 11px; line-height: 1.5;">
            <div>No. Invoice : {{ $transaction->nomor_invoice }}</div>
            <div>Waktu       : {{ $transaction->created_at->format('d/m/Y H:i') }}</div>
            <div>Kasir       : {{ $transaction->user->name ?? 'Kasir' }}</div>
            <div>Metode      : {{ strtoupper($transaction->metode_pembayaran) }}</div>
        </div>

        <div class="divider"></div>

        <!-- Detail Item -->
        <div style="margin-bottom: 8px;">
            @foreach ($transaction->details as $item)
                <div style="margin-bottom: 6px;">
                    <div style="font-weight: 700; font-size: 12px;">{{ $item->nama_produk ?? ($item->product->nama ?? 'Menu Kedai Kopi') }}</div>
                    <div class="line-item" style="color: #44403c; font-size: 11px;">
                        <span>{{ $item->kuantitas }} × Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                        <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <!-- Total -->
        <div class="line-item" style="font-weight: 700; font-size: 14px;">
            <span>TOTAL</span>
            <span>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
        </div>

        <div class="divider"></div>

        <div class="text-center" style="font-size: 11px; margin-top: 16px; line-height: 1.5;">
            <p style="margin: 0;">Terima Kasih Atas Kunjungan Anda!</p>
            <p style="margin: 0; color: #78716c;">Nikmati secangkir kopi terbaik hari ini.</p>
        </div>
    </div>

    <!-- Tombol Cetak -->
    <div class="actions">
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Struk</button>
        <button onclick="window.close()" class="btn btn-close">Tutup</button>
    </div>

</body>
</html>
