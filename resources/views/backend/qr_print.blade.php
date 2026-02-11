<!DOCTYPE html>
<html>
<head>
    <title>Print QR Meja {{ $meja->nomor_meja }}</title>
    <style>
        body { 
            text-align: center; 
            font-family: 'Courier New', Courier, monospace; 
            padding-top: 50px; 
            background-color: #f4f4f4;
        }
        .qr-card { 
            background: white;
            border: 2px dashed #000; 
            display: inline-block; 
            padding: 40px; 
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .nomor { font-size: 50px; font-weight: bold; margin: 10px 0; }
        .logo { font-size: 24px; font-weight: bold; color: #333; text-transform: uppercase; }
        p { color: #666; margin-top: 15px; }
    </style>
</head>
<body onload="window.print()">
    <div class="qr-card">
        <div class="logo">Cafe Ayana</div>
        <hr>
        <div class="nomor">MEJA {{ $meja->nomor_meja }}</div>
        
        <div style="margin: 20px 0;">
            {!! QrCode::size(250)->margin(2)->generate($url) !!}
        </div>

        <p>Silahkan Scan untuk Menu & Pemesanan</p>
    </div>
</body>
</html>