<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.5.0/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f3f4f6;
        }

        .verification-container {
            max-width: 800px;
            margin: 2rem auto;
        }
    </style>
</head>

<body>
    <div class="verification-container">
        <div class="navbar bg-base-100 shadow-md rounded-box mb-4">
            <div class="flex-1">
                <a class="btn btn-ghost normal-case text-xl">Sistem Surat Desa</a>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-4 text-center">Verifikasi Surat</h2>

                @if ($error)
                    <div class="alert alert-error mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $error }}</span>
                    </div>
                @elseif ($incomingLetter)
                    <div class="alert alert-success mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Surat ini terverifikasi dan sah.</span>
                    </div>

                    <div class="divider">INFORMASI SURAT</div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th class="w-1/3">Nomor Surat</th>
                                    <td>{{ $incomingLetter->final_letter_number ?? $incomingLetter->letter_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Surat</th>
                                    <td>{{ $incomingLetter->letter_date->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Perihal</th>
                                    <td>{{ $incomingLetter->subject }}</td>
                                </tr>
                                <tr>
                                    <th>Pengirim</th>
                                    <td>{{ $incomingLetter->sender }}</td>
                                </tr>
                                <tr>
                                    <th>Klasifikasi</th>
                                    <td>{{ $incomingLetter->classification->name ?? 'Tidak ada' }}</td>
                                </tr>
                                <tr>
                                    <th>Sifat</th>
                                    <td>
                                        @if ($incomingLetter->confidentiality === 'biasa')
                                            <span class="badge badge-info">Biasa</span>
                                        @elseif ($incomingLetter->confidentiality === 'rahasia')
                                            <span class="badge badge-warning">Rahasia</span>
                                        @elseif ($incomingLetter->confidentiality === 'umum')
                                            <span class="badge badge-success">Umum</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($incomingLetter->status === 'processed')
                                            <span class="badge badge-success">Diproses</span>
                                        @elseif ($incomingLetter->status === 'finish')
                                            <span class="badge badge-success">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Diproses Pada</th>
                                    <td>{{ $incomingLetter->processed_at ? $incomingLetter->processed_at->format('d F Y H:i') : 'Belum diproses' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if ($incomingLetter->qr_code)
                        <div class="flex justify-center mt-4">
                            <img src="{{ asset('storage/' . $incomingLetter->qr_code) }}" alt="QR Code"
                                class="w-32 h-32">
                        </div>
                    @endif

                    <div class="divider">KETERANGAN</div>
                    <p class="text-sm text-gray-600 text-center">
                        Dokumen ini dapat diverifikasi melalui QR Code atau dengan mengakses tautan berikut:<br>
                        <a href="{{ route('incoming-letters.verify', $incomingLetter) }}" class="link link-primary">
                            {{ route('incoming-letters.verify', $incomingLetter) }}
                        </a>
                    </p>
                @endif
            </div>
        </div>

        <footer class="footer footer-center p-4 bg-base-100 text-base-content mt-4 rounded-box">
            <div>
                <p>Copyright © {{ date('Y') }} - Sistem Surat Desa</p>
            </div>
        </footer>
    </div>
</body>

</html>
