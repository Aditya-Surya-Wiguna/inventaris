<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Barcode Barang</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }
        .item {
            width: 30%;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
            margin: 8px;
            box-sizing: border-box;
        }
        img {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }
        .kode {
            margin-top: 6px;
            font-weight: bold;
            font-size: 13px;
            color: #333;
        }
    </style>
</head>
<body>

@php
    use Milon\Barcode\Facades\DNS2D;
    use Carbon\Carbon;
@endphp

    <h3>Daftar Barcode Barang</h3>

    <div class="barcode-container">
        @foreach($barang as $b)
            <div class="item">
                {{-- Generate QR menggunakan milon/barcode --}}
                <img 
                    src="data:image/png;base64,{{ \Milon\Barcode\DNS2D::getBarcodePNG(route('barang.show', $b->id_barang), 'QRCODE') }}" 
                    alt="QR Code"
                >
                <div class="kode">
                    {{ $b->kode_barang }} -
                    {{ $b->tanggal_masuk ? Carbon::parse($b->tanggal_masuk)->format('Y') : 'N/A' }}
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>
