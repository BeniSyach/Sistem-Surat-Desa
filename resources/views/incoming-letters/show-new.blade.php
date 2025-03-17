@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold flex items-center gap-2">
                <i class="fas fa-envelope-open-text text-primary"></i>
                Detail Surat Masuk
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('incoming-letters.index') }}" class="btn btn-ghost gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Informasi Surat -->
            <div class="lg:col-span-2">
                <div class="bg-base-100 rounded-xl shadow-md p-6">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle text-info"></i>
                                Informasi Surat
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Nomor Surat</div>
                                    <div class="font-medium">{{ $incomingLetter->letter_number }}</div>
                                </div>

                                <div class="space-y-1">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Pengirim</div>
                                    <div class="font-medium">{{ $incomingLetter->sender }}</div>
                                </div>

                                <div class="space-y-1">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal Surat</div>
                                    <div class="font-medium">{{ $incomingLetter->letter_date->format('d/m/Y') }}</div>
                                </div>

                                <div class="space-y-1">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal Diterima</div>
                                    <div class="font-medium">{{ $incomingLetter->received_date->format('d/m/Y') }}</div>
                                </div>

                                <div class="space-y-1">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Perihal</div>
                                    <div class="font-medium">{{ $incomingLetter->subject }}</div>
                                </div>

                                <div class="space-y-1">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Sifat Surat</div>
                                    <div class="font-medium">
                                        <span
                                            class="badge {{ $incomingLetter->nature === 'Biasa' ? 'badge-ghost' : ($incomingLetter->nature === 'Segera' ? 'badge-warning' : 'badge-error') }}">
                                            {{ $incomingLetter->nature }}
                                        </span>
                                    </div>
                                </div>

                                @if ($incomingLetter->classification)
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Klasifikasi</div>
                                        <div class="font-medium">{{ $incomingLetter->classification->name }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($incomingLetter->description)
                            <div>
                                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                    <i class="fas fa-align-left text-success"></i>
                                    Deskripsi
                                </h3>
                                <div class="prose max-w-none">
                                    {{ $incomingLetter->description }}
                                </div>
                            </div>
                        @endif

                        @if ($incomingLetter->attachment)
                            <div>
                                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                    <i class="fas fa-paperclip text-warning"></i>
                                    File Surat
                                </h3>
                                <a href="{{ route('incoming-letters.download-attachment', $incomingLetter) }}"
                                    class="btn btn-primary btn-sm gap-2">
                                    <i class="fas fa-download"></i>
                                    Unduh Lampiran
                                </a>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <i class="fas fa-history text-secondary"></i>
                                Informasi Tambahan
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Ditambahkan Oleh</div>
                                    <div class="font-medium">{{ $incomingLetter->creator->name }}</div>
                                </div>

                                <div class="space-y-1">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal Ditambahkan</div>
                                    <div class="font-medium">{{ $incomingLetter->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>

                                @if ($incomingLetter->approval_notes)
                                    <div class="col-span-2 space-y-1">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Catatan Persetujuan</div>
                                        <div class="font-medium">{{ $incomingLetter->approval_notes }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Tindakan -->
            <div class="lg:col-span-1 space-y-4">
                <!-- Status Card -->
                <div class="bg-base-100 rounded-xl shadow-md p-6">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-tasks text-primary"></i>
                        Status Surat
                    </h3>
                    <ul class="steps steps-vertical">
                        <li
                            class="step {{ in_array($incomingLetter->status, ['received', 'pending_approval', 'approved', 'dispositioned']) ? 'step-primary' : '' }}">
                            <div class="flex flex-col items-start">
                                <span>Diterima</span>
                                @if ($incomingLetter->received_date)
                                    <span
                                        class="text-xs opacity-70">{{ $incomingLetter->received_date->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        </li>
                        <li
                            class="step {{ in_array($incomingLetter->status, ['pending_approval', 'approved', 'dispositioned']) ? 'step-primary' : '' }}">
                            <div class="flex flex-col items-start">
                                <span>Diajukan</span>
                                @if ($incomingLetter->submitted_at)
                                    <span
                                        class="text-xs opacity-70">{{ $incomingLetter->submitted_at->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                        </li>
                        <li
                            class="step {{ in_array($incomingLetter->status, ['approved', 'dispositioned']) ? 'step-primary' : '' }}">
                            <div class="flex flex-col items-start">
                                <span>Disetujui</span>
                                @if ($incomingLetter->sekdes_approved_at)
                                    <span
                                        class="text-xs opacity-70">{{ $incomingLetter->sekdes_approved_at->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Action Cards -->
                @if (auth()->user()->isStaff() && $incomingLetter->status === 'received')
                    <div class="bg-base-100 rounded-xl shadow-md p-6 space-y-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <i class="fas fa-paper-plane text-info"></i>
                            Tindakan Staff
                        </h3>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <span>Silakan ajukan surat ini untuk disetujui oleh Sekretaris Desa.</span>
                        </div>
                        <form action="{{ route('incoming-letters.submit', $incomingLetter) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full gap-2">
                                <i class="fas fa-paper-plane"></i>
                                Ajukan Persetujuan
                            </button>
                        </form>
                    </div>
                @endif

                @if (auth()->user()->isSekdes() && $incomingLetter->status === 'pending_approval')
                    <div class="bg-base-100 rounded-xl shadow-md p-6 space-y-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <i class="fas fa-check-circle text-success"></i>
                            Tindakan Sekdes
                        </h3>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <span>Silakan tinjau dan setujui surat ini.</span>
                        </div>
                        <form action="{{ route('incoming-letters.approve', $incomingLetter) }}" method="POST"
                            class="space-y-4">
                            @csrf
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Catatan (Opsional)</span>
                                </label>
                                <textarea name="approval_notes" class="textarea textarea-bordered" placeholder="Tambahkan catatan..."></textarea>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" name="action" value="approve"
                                    class="btn btn-success flex-1 gap-2">
                                    <i class="fas fa-check"></i>
                                    Setujui
                                </button>
                                <button type="submit" name="action" value="reject"
                                    class="btn btn-error flex-1 gap-2">
                                    <i class="fas fa-times"></i>
                                    Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                @if (auth()->user()->isKades() && $incomingLetter->status === 'approved')
                    <div class="bg-base-100 rounded-xl shadow-md p-6 space-y-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <i class="fas fa-share text-warning"></i>
                            Tindakan Disposisi
                        </h3>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <span>Silakan pilih penerima disposisi surat ini.</span>
                        </div>
                        <form action="{{ route('incoming-letters.create-disposition', $incomingLetter) }}"
                            method="POST" class="space-y-4">
                            @csrf
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Penerima Disposisi</span>
                                </label>
                                <select name="recipient_id" class="select select-bordered w-full" required>
                                    <option value="">Pilih Penerima</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} -
                                            {{ $user->role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Catatan Disposisi</span>
                                </label>
                                <textarea name="notes" class="textarea textarea-bordered" placeholder="Tambahkan catatan disposisi..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-info w-full gap-2">
                                <i class="fas fa-share"></i>
                                Teruskan Surat
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
