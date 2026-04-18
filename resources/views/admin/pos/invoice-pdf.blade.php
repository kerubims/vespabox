<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota {{ $transaction->booking ? $transaction->booking->kode_booking : 'INV-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 10px; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 10px; 
            color: #000; 
            margin: 0;
            padding: 0;
            line-height: 1.2;
            background: #fff;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        
        .header { margin-bottom: 8px; border-bottom: 1px dashed #000; padding-bottom: 6px; }
        .logo { max-width: 45px; margin-bottom: 4px; filter: grayscale(100%); }
        .shop-name { font-size: 12px; font-weight: bold; margin: 2px 0; }
        .shop-desc { font-size: 9px; margin: 0; }
        
        .info-table { width: 100%; margin-bottom: 8px; font-size: 9px; }
        .info-table td { vertical-align: top; padding: 1px 0; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; font-size: 9px; }
        .items-table th { border-bottom: 1px dashed #000; border-top: 1px dashed #000; padding: 3px 0; text-align: left; }
        .items-table td { padding: 4px 0; vertical-align: top; }
        .items-table .item-name { display: block; margin-bottom: 2px; }
        
        .totals-table { width: 100%; font-size: 9px; margin-top: 4px; }
        .totals-table td { padding: 2px 0; }
        .grand-total { font-weight: bold; font-size: 10px; border-top: 1px dashed #000; }
        
        .footer { text-align: center; margin-top: 10px; font-size: 8px; border-top: 1px dashed #000; padding-top: 6px; }
    </style>
</head>
<body>
    <div class="header text-center">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
        @else
            <div class="shop-name">VESPABOX</div>
        @endif        
        <p class="shop-desc">Jl. Bromo IIA No.43, Oro-oro Dowo, Kec. Klojen</p>
        <p class="shop-desc">Kota Malang, Jawa Timur 65119</p>
        <p class="shop-desc">Telp: 081233345588</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="35%">No. Nota</td>
            <td width="5%">:</td>
            <td>INV-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>:</td>
            <td>{{ $transaction->user->nama ?? 'Admin' }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>:</td>
            <td>
                @if($transaction->booking)
                    {{ $transaction->booking->user->name ?? $transaction->booking->user->nama ?? '-' }}<br>
                    ({{ $transaction->booking->plat_nomor }})
                @else
                    {{ $transaction->customer_name ?: 'Walk-in' }}
                @endif
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
            <tr>
                <td>
                    <span class="item-name">{{ $item->item_name }}</span>
                    {{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}
                </td>
                <td class="text-right"><br>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td width="55%" class="text-right">Subtotal :</td>
            <td class="text-right">{{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-right">Pajak(11%) :</td>
            <td class="text-right">{{ number_format($transaction->tax_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand-total">
            <td class="text-right">TOTAL :</td>
            <td class="text-right">Rp{{ number_format($transaction->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-right">Status :</td>
            <td class="text-right">{{ strtoupper($transaction->status_pembayaran) }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>
</body>
</html>
