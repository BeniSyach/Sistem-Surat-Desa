<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tanda Tangan Kades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            margin: 5px 0;
        }

        .signature-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 20px auto;
            width: 300px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin: 15px auto;
        }

        .letter-info {
            margin-top: 40px;
            font-size: 14px;
        }

        .letter-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .letter-info table td {
            padding: 5px;
            vertical-align: top;
        }

        .letter-info table td:first-child {
            width: 150px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>TANDA TANGAN ELEKTRONIK KADES</h1>
            <p>Dokumen ini merupakan bukti tanda tangan elektronik yang sah</p>
        </div>

        <div class="signature-box">
            <p style="font-weight: bold; font-size: 16px;">
                {{ $incomingLetter->kades ? $incomingLetter->kades->name : 'Kepala Desa' }}</p>
            @if ($incomingLetter->kades && $incomingLetter->kades->signature)
                <div style="margin: 10px auto;">
                    <img src="{{ public_path('storage/' . $incomingLetter->kades->signature) }}"
                        alt="Tanda Tangan Kepala Desa" style="height: 60px; margin: 0 auto; display: block;">
                </div>
            @else
                <div class="signature-line"></div>
            @endif
            <p style="font-size: 12px;">Ditandatangani secara elektronik pada:</p>
            <p style="font-size: 12px; font-weight: bold;">
                {{ $incomingLetter->kades_signed_at ? $incomingLetter->kades_signed_at->format('d/m/Y H:i') : '-' }}</p>
        </div>

        <div class="letter-info">
            <h3>Informasi Surat</h3>
            <table>
                <tr>
                    <td>Nomor Surat</td>
                    <td>: {{ $incomingLetter->final_letter_number ?? $incomingLetter->letter_number }}</td>
                </tr>
                <tr>
                    <td>Tanggal Surat</td>
                    <td>: {{ $incomingLetter->letter_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>: {{ $incomingLetter->subject }}</td>
                </tr>
                <tr>
                    <td>Pengirim</td>
                    <td>: {{ $incomingLetter->sender }}</td>
                </tr>
                @if ($incomingLetter->kades_notes)
                    <tr>
                        <td>Catatan Kades</td>
                        <td>: {{ $incomingLetter->kades_notes }}</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</body>

</html>
