<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-3xl mx-auto px-4">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold mb-2">Verifikasi Surat Keluar</h1>
                        <div class="inline-flex items-center justify-center gap-2 bg-white/20 rounded-full px-4 py-1">
                            <i class="fas fa-hashtag"></i>
                            <span>{{ $outgoingLetter->letter_number }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Main Content -->
                    <div class="grid gap-6 mb-8">
                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm text-gray-500 dark:text-gray-400">Perihal</label>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $outgoingLetter->subject }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500 dark:text-gray-400">Tanggal Surat</label>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $outgoingLetter->letter_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm text-gray-500 dark:text-gray-400">Dibuat oleh</label>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $outgoingLetter->creator->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500 dark:text-gray-400">Klasifikasi</label>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $outgoingLetter->classification->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Timeline -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <h3
                                class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-900 dark:text-white">
                                <i class="fas fa-clock text-blue-500"></i> Riwayat Persetujuan
                            </h3>
                            <div class="space-y-4">
                                <!-- Sekdes -->
                                <div class="flex items-start gap-4">
                                    <div class="flex-none">
                                        <div
                                            class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-file-signature"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white">Paraf Sekdes</h4>
                                        @if ($outgoingLetter->sekdes_approved_at)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Diparaf oleh {{ $outgoingLetter->sekdes->name }}
                                                <br>
                                                <span
                                                    class="text-xs">{{ $outgoingLetter->sekdes_approved_at->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum diparaf</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Kades -->
                                <div class="flex items-start gap-4">
                                    <div class="flex-none">
                                        <div
                                            class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-signature"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white">TTD Kades</h4>
                                        @if ($outgoingLetter->kades_approved_at)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Ditandatangani oleh {{ $outgoingLetter->kades->name }}
                                                <br>
                                                <span
                                                    class="text-xs">{{ $outgoingLetter->kades_approved_at->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum
                                                ditandatangani</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Processed -->
                                <div class="flex items-start gap-4">
                                    <div class="flex-none">
                                        <div
                                            class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white">Diproses</h4>
                                        @if ($outgoingLetter->processed_at)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Diproses oleh {{ $outgoingLetter->processor->name }}
                                                <br>
                                                <span
                                                    class="text-xs">{{ $outgoingLetter->processed_at->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum diproses
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code and Signature -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($outgoingLetter->qr_code)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">QR Code
                                        Verifikasi</h3>
                                    <img src="data:image/png;base64,{{ base64_encode($outgoingLetter->qr_code) }}"
                                        alt="QR Code" class="mx-auto max-w-[150px]">
                                </div>
                            @endif

                            @if ($outgoingLetter->kades && $outgoingLetter->kades->signature)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Tanda Tangan
                                        Digital</h3>
                                    <img src="{{ Storage::url($outgoingLetter->kades->signature) }}"
                                        alt="Tanda Tangan Kades" class="mx-auto h-24 object-contain mb-2">
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $outgoingLetter->kades->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kepala Instansi</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-center">
                        <a href="{{ url()->previous() }}" class="btn btn-ghost flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
