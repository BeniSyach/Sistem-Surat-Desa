<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-envelope-open-text text-blue-500"></i> Detail Surat Keluar
            </h1>
            <div class="flex gap-2">
                @if ($letter->status === 'draft' && auth()->user()->isKasi())
                    <a href="{{ route('outgoing-letters.edit', $letter) }}"
                        class="btn btn-warning flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('outgoing-letters.submit', $letter) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin mengirim surat ini untuk diparaf?')">
                        @csrf
                        <button type="submit" class="btn btn-primary flex items-center gap-2">
                            <i class="fas fa-paper-plane"></i> Kirim untuk Diparaf
                        </button>
                    </form>
                @elseif(($letter->status === 'rejected_sekdes' || $letter->status === 'rejected_kades') && auth()->user()->isKasi())
                    <a href="{{ route('outgoing-letters.edit', $letter) }}"
                        class="btn btn-warning flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('outgoing-letters.submit', $letter) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin mengirim ulang surat ini untuk diparaf?')">
                        @csrf
                        <button type="submit" class="btn btn-primary flex items-center gap-2">
                            <i class="fas fa-paper-plane"></i> Kirim Ulang
                        </button>
                    </form>
                @endif
                <a href="{{ route('outgoing-letters.index') }}" class="btn btn-ghost flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>



        @if (session('error'))
            <div class="alert alert-error mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($letter->status === 'rejected_sekdes' || $letter->status === 'rejected_kades')
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-bold">Surat Ditolak</h3>
                    <div class="text-sm">
                        @if ($letter->status === 'rejected_sekdes')
                            Surat ini ditolak oleh Sekdes.
                        @else
                            Surat ini ditolak oleh Kades.
                        @endif
                        <p class="mt-1">Alasan: {{ $letter->rejection_reason }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ $letter->subject }}
                            </h2>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $letter->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $letter->creator->name }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-folder"></i>
                                    <span>{{ $letter->classification->name }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-building"></i>
                                    <span>{{ $letter->department->name }}</span>
                                </div>
                            </div>
                        </div>

                        @if ($letter->letter_number)
                            <div class="alert alert-info mb-6">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Nomor Surat: {{ $letter->letter_number }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="prose dark:prose-invert max-w-none mb-6">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
                                    <i class="fas fa-align-left text-blue-500"></i> Isi Surat
                                </h3>
                                <div class="whitespace-pre-wrap">{{ $letter->content }}</div>
                            </div>
                        </div>

                        @if ($letter->attachment)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File Lampiran
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ basename($letter->attachment) }}</p>
                                </div>
                                <a href="{{ route('outgoing-letters.download-attachment', $letter) }}"
                                    class="btn btn-primary btn-sm flex items-center gap-2">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        @endif

                        @if ($letter->isProcessed())
                            <div class="border rounded p-4 mb-6">
                                <h3 class="font-bold mb-2">Verifikasi</h3>
                                <a href="{{ route('outgoing-letters.verify', ['outgoingLetter' => $letter]) }}"
                                    class="btn btn-sm btn-outline" target="_blank">
                                    <i class="fas fa-qrcode mr-2"></i>
                                    Lihat Halaman Verifikasi
                                </a>
                                @if ($letter->qr_code)
                                    <div class="mt-4">
                                        <img src="data:image/png;base64,{{ base64_encode($letter->qr_code) }}"
                                            alt="QR Code" class="mx-auto">
                                    </div>
                                @endif

                                @if ($letter->kades && $letter->kades->signature)
                                    <div class="mt-4 text-center">
                                        <p class="text-sm text-gray-600 mb-2">Ditandatangani oleh:</p>
                                        <img src="{{ Storage::url($letter->kades->signature) }}"
                                            alt="Tanda Tangan Kades" class="mx-auto h-24 object-contain mb-1">
                                        <p class="font-bold">{{ $letter->kades->name }}</p>
                                        <p class="text-sm text-gray-600">Kepala Instansi</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($letter->notes)
                            <div class="border rounded p-4">
                                <h3 class="font-bold mb-2">Catatan</h3>
                                <p class="whitespace-pre-wrap">{{ $letter->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Status Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i> Status Surat
                        </h3>
                        <ul class="steps steps-vertical">
                            <li
                                class="step {{ in_array($letter->status, ['draft', 'pending_sekdes', 'pending_kades', 'approved', 'processed']) ? 'step-primary' : '' }}">
                                <div class="flex flex-col items-start">
                                    <span class="font-medium">Draft</span>
                                    @if ($letter->created_at)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $letter->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </li>
                            <li
                                class="step {{ in_array($letter->status, ['pending_sekdes', 'pending_kades', 'approved', 'processed']) ? 'step-primary' : '' }}">
                                <div class="flex flex-col items-start">
                                    <span class="font-medium">Paraf Sekdes</span>
                                    @if ($letter->sekdes_approved_at)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $letter->sekdes_approved_at->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </li>
                            <li
                                class="step {{ in_array($letter->status, ['pending_kades', 'approved', 'processed']) ? 'step-primary' : '' }}">
                                <div class="flex flex-col items-start">
                                    <span class="font-medium">TTD Kades</span>
                                    @if ($letter->kades_approved_at)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $letter->kades_approved_at->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </li>
                            <li class="step {{ in_array($letter->status, ['processed']) ? 'step-primary' : '' }}">
                                <div class="flex flex-col items-start">
                                    <span class="font-medium">Diproses</span>
                                    @if ($letter->processed_at)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $letter->processed_at->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Recipient Info -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <i class="fas fa-user text-blue-500"></i> Informasi Penerima
                        </h3>
                        <div class="space-y-4">
                            @if ($letter->recipient)
                                <div>
                                    <label class="text-sm text-gray-500 dark:text-gray-400">Nama</label>
                                    <p class="font-medium">{{ $letter->recipient->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500 dark:text-gray-400">Jabatan</label>
                                    <p class="font-medium">{{ $letter->recipient->role->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500 dark:text-gray-400">Desa</label>
                                    <p class="font-medium">{{ $letter->recipient->village->name }}</p>
                                </div>
                            @else
                                <div class="text-gray-500 dark:text-gray-400 italic">
                                    Informasi penerima tidak tersedia
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Signer Info -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <i class="fas fa-signature text-blue-500"></i> Informasi Penandatangan
                        </h3>
                        <div class="space-y-4">
                            @if ($letter->signer)
                                <div>
                                    <label class="text-sm text-gray-500 dark:text-gray-400">Nama</label>
                                    <p class="font-medium">{{ $letter->signer->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500 dark:text-gray-400">Jabatan</label>
                                    <p class="font-medium">{{ $letter->signer->role->name }}</p>
                                </div>
                            @else
                                <div class="text-gray-500 dark:text-gray-400 italic">
                                    Informasi penandatangan tidak tersedia
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
