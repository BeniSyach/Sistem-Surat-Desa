@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 space-y-4">
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

                                @if ($incomingLetter->final_letter_number)
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Nomor Surat Final</div>
                                        <div class="font-medium">{{ $incomingLetter->final_letter_number }}</div>
                                    </div>
                                @endif

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
                                            class="badge {{ $incomingLetter->confidentiality === 'biasa' ? 'badge-ghost' : ($incomingLetter->confidentiality === 'Segera' ? 'badge-warning' : 'badge-error') }}">
                                            {{ $incomingLetter->confidentiality }}
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

                        @if (auth()->user()->isUmumDesa() && ($incomingLetter->status === 'signed' || $incomingLetter->status === 'processed'))
                            <div>
                                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                    <i class="fas fa-certificate text-primary"></i>
                                    Verifikasi Surat
                                </h3>

                                <div class="grid grid-cols-2 gap-4">
                                    @if ($incomingLetter->qr_code)
                                        <div class="space-y-2">
                                            <h4 class="font-medium">QR Code Verifikasi</h4>
                                            <div class="flex flex-col items-center gap-2 bg-base-200 rounded-lg p-4">
                                                <img src="{{ asset('storage/' . $incomingLetter->qr_code) }}"
                                                    alt="QR Code" class="w-32 h-32">
                                                <a href="{{ asset('storage/' . $incomingLetter->qr_code) }}"
                                                    download="qr_code_{{ $incomingLetter->letter_number }}.png"
                                                    class="btn btn-sm btn-primary gap-2 w-full">
                                                    <i class="fas fa-download"></i>
                                                    Unduh QR Code
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($incomingLetter->kades && $incomingLetter->kades->signature)
                                        <div class="space-y-2">
                                            <h4 class="font-medium">Tanda Tangan Digital</h4>
                                            <div class="flex flex-col items-center gap-2 bg-base-200 rounded-lg p-4">
                                                <img src="{{ Storage::url($incomingLetter->kades->signature) }}"
                                                    alt="Tanda Tangan Kades" class="h-24 object-contain">
                                                <div class="text-center text-sm">
                                                    <p class="font-medium">{{ $incomingLetter->kades->name }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        Ditandatangani pada:
                                                        {{ $incomingLetter->kades_signed_at->format('d/m/Y H:i') }}
                                                    </p>
                                                </div>
                                                <a href="{{ Storage::url($incomingLetter->kades->signature) }}"
                                                    download="ttd_kades_{{ Str::slug($incomingLetter->kades->name) }}.png"
                                                    class="btn btn-sm btn-primary gap-2 w-full">
                                                    <i class="fas fa-download"></i>
                                                    Unduh Tanda Tangan
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="space-y-2">
                                            <h4 class="font-medium">Tanda Tangan Digital</h4>
                                            <div class="flex flex-col items-center gap-2 bg-base-200 rounded-lg p-4">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <span>Tanda tangan Kades belum tersedia. Silakan upload tanda tangan
                                                        terlebih dahulu.</span>
                                                </div>
                                                <a href="{{ route('users.signature', $incomingLetter->kades->id) }}"
                                                    class="btn btn-warning btn-sm gap-2 w-full">
                                                    <i class="fas fa-upload"></i>
                                                    Upload Tanda Tangan
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4 p-4 bg-base-200 rounded-lg">
                                    <p class="text-sm text-gray-500 mb-2">Dokumen ini dapat diverifikasi melalui QR Code
                                        atau dengan mengakses tautan berikut:</p>
                                    <div class="flex items-center gap-2">
                                        <input type="text"
                                            value="{{ route('incoming-letters.verify', $incomingLetter) }}"
                                            class="input input-bordered input-sm flex-grow" readonly>
                                        <a href="{{ route('incoming-letters.verify', $incomingLetter) }}"
                                            class="btn btn-sm btn-circle btn-ghost" target="_blank">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
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
                                    <div class="font-medium">{{ $incomingLetter->created_at->format('d/m/Y H:i') }}</div>
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
                            class="step {{ in_array($incomingLetter->status, ['received', 'pending_approval', 'approved', 'signed', 'processed', 'dispositioned']) ? 'step-primary' : '' }}">
                            <div class="flex flex-col items-start">
                                <span>Diterima</span>
                                @if ($incomingLetter->received_date)
                                    <span
                                        class="text-xs opacity-70">{{ $incomingLetter->received_date->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        </li>
                        <li
                            class="step {{ in_array($incomingLetter->status, ['pending_approval', 'approved', 'signed', 'processed', 'dispositioned']) ? 'step-primary' : '' }}">
                            <div class="flex flex-col items-start">
                                <span>Diajukan</span>
                                @if ($incomingLetter->submitted_at)
                                    <span
                                        class="text-xs opacity-70">{{ $incomingLetter->submitted_at->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                        </li>
                        <li
                            class="step {{ in_array($incomingLetter->status, ['approved', 'signed', 'processed', 'dispositioned']) ? 'step-primary' : '' }}">
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
                @if (auth()->user()->isSekdes() && $incomingLetter->status === 'received')
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
                            <span>Silakan tinjau surat ini.</span>
                        </div>
                        <form action="{{ route('incoming-letters.sekdes-approve', $incomingLetter) }}" method="POST"
                            class="space-y-4" id="sekdesForm">
                            @csrf
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Catatan</span>
                                    <span class="label-text-alt text-error">*Wajib diisi jika menolak</span>
                                </label>
                                <textarea name="approval_notes" class="textarea textarea-bordered" placeholder="Tambahkan catatan..."></textarea>
                            </div>

                            <!-- Form untuk Meneruskan -->
                            <div class="form-control forward-section">
                                <label class="label">
                                    <span class="label-text">Teruskan Kepada</span>
                                </label>
                                <select name="forward_to" class="select select-bordered w-full">
                                    <option value="">Pilih Penerima</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} -
                                            {{ $user->role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Hidden input untuk menyimpan ID pengirim asli -->
                            <input type="hidden" name="original_sender_id" value="{{ $incomingLetter->creator->id }}">

                            <!-- Form untuk Penolakan -->
                            <div class="form-control reject-section hidden">
                                <label class="label">
                                    <span class="label-text">Tindakan yang Disarankan</span>
                                </label>
                                <select name="rejection_action" class="select select-bordered w-full">
                                    <option value="revise">Perbaiki surat ini</option>
                                    <option value="new">Buat surat baru</option>
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" name="action" value="approve"
                                    class="btn btn-success flex-1 gap-2 approve-btn">
                                    <i class="fas fa-check"></i>
                                    Paraf & Teruskan
                                </button>
                                <button type="submit" name="action" value="reject"
                                    class="btn btn-error flex-1 gap-2 reject-btn">
                                    <i class="fas fa-times"></i>
                                    Tolak
                                </button>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                ['sekdesForm'].forEach(formId => {
                                    const form = document.getElementById(formId);
                                    if (!form) return;

                                    const approveBtn = form.querySelector('.approve-btn');
                                    const rejectBtn = form.querySelector('.reject-btn');
                                    const forwardSection = form.querySelector('.forward-section');
                                    const rejectSection = form.querySelector('.reject-section');
                                    const notes = form.querySelector('textarea[name="approval_notes"]');
                                    const forwardSelect = form.querySelector('select[name="forward_to"]');

                                    // Tampilkan form forward saat halaman dimuat
                                    if (forwardSection) {
                                        forwardSection.classList.remove('hidden');
                                    }

                                    approveBtn?.addEventListener('click', function(e) {
                                        e.preventDefault();

                                        if (!forwardSelect.value) {
                                            alert('Silakan pilih penerima surat terlebih dahulu!');
                                            forwardSelect.focus();
                                            return;
                                        }

                                        if (rejectSection) {
                                            rejectSection.classList.add('hidden');
                                        }

                                        // Set action URL untuk approve
                                        form.action =
                                            "{{ route('incoming-letters.sekdes-approve', $incomingLetter) }}";
                                        form.submit();
                                    });

                                    rejectBtn?.addEventListener('click', function(e) {
                                        e.preventDefault();

                                        if (!notes.value.trim()) {
                                            alert('Catatan wajib diisi saat menolak surat!');
                                            notes.focus();
                                            return;
                                        }

                                        if (forwardSection) {
                                            forwardSection.classList.add('hidden');
                                        }
                                        if (rejectSection) {
                                            rejectSection.classList.remove('hidden');
                                        }

                                        // Set forward_to ke pengirim asli untuk reject
                                        if (forwardSelect) {
                                            forwardSelect.value = form.querySelector('input[name="original_sender_id"]')
                                                .value;
                                        }

                                        // Set action URL untuk reject
                                        form.action = "{{ route('incoming-letters.sekdes-reject', $incomingLetter) }}";
                                        form.submit();
                                    });

                                    // Validasi form sebelum submit
                                    form.addEventListener('submit', function(e) {
                                        const isApproveAction = form.action.includes('sekdes-approve');

                                        if (isApproveAction && !forwardSelect.value) {
                                            e.preventDefault();
                                            alert('Silakan pilih penerima surat terlebih dahulu!');
                                            forwardSelect.focus();
                                            return;
                                        }

                                        if (!isApproveAction && !notes.value.trim()) {
                                            e.preventDefault();
                                            alert('Catatan wajib diisi saat menolak surat!');
                                            notes.focus();
                                            return;
                                        }
                                    });
                                });
                            });
                        </script>
                    </div>
                @endif

                @if (auth()->user()->isKades() && $incomingLetter->status === 'approved')
                    <div class="bg-base-100 rounded-xl shadow-md p-6 space-y-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <i class="fas fa-signature text-success"></i>
                            Tindakan Kades
                        </h3>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <span>Silakan tinjau surat ini.</span>
                        </div>
                        <form action="{{ route('incoming-letters.kades-sign', $incomingLetter) }}" method="POST"
                            class="space-y-4" id="kadesForm">
                            @csrf
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Catatan</span>
                                    <span class="label-text-alt text-error">*Wajib diisi jika menolak</span>
                                </label>
                                <textarea name="approval_notes" class="textarea textarea-bordered" placeholder="Tambahkan catatan..."></textarea>
                            </div>

                            <!-- Form untuk Meneruskan -->
                            <div class="form-control forward-section">
                                <label class="label">
                                    <span class="label-text">Teruskan Kepada</span>
                                </label>
                                <select name="forward_to" class="select select-bordered w-full">
                                    <option value="">Pilih Penerima</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} -
                                            {{ $user->role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Hidden input untuk menyimpan ID pengirim asli -->
                            <input type="hidden" name="original_sender_id" value="{{ $incomingLetter->creator->id }}">

                            <!-- Form untuk Penolakan -->
                            <div class="form-control reject-section hidden">
                                <label class="label">
                                    <span class="label-text">Tindakan yang Disarankan</span>
                                </label>
                                <select name="rejection_action" class="select select-bordered w-full">
                                    <option value="revise">Perbaiki surat ini</option>
                                    <option value="new">Buat surat baru</option>
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" name="action" value="approve"
                                    class="btn btn-success flex-1 gap-2 approve-btn">
                                    <i class="fas fa-signature"></i>
                                    Tandatangani & Teruskan
                                </button>
                                <button type="submit" name="action" value="reject"
                                    class="btn btn-error flex-1 gap-2 reject-btn">
                                    <i class="fas fa-times"></i>
                                    Tolak
                                </button>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                ['kadesForm'].forEach(formId => {
                                    const form = document.getElementById(formId);
                                    if (!form) return;

                                    const approveBtn = form.querySelector('.approve-btn');
                                    const rejectBtn = form.querySelector('.reject-btn');
                                    const forwardSection = form.querySelector('.forward-section');
                                    const rejectSection = form.querySelector('.reject-section');
                                    const notes = form.querySelector('textarea[name="approval_notes"]');
                                    const forwardSelect = form.querySelector('select[name="forward_to"]');

                                    // Tampilkan form forward saat halaman dimuat
                                    if (forwardSection) {
                                        forwardSection.classList.remove('hidden');
                                    }

                                    approveBtn?.addEventListener('click', function(e) {
                                        e.preventDefault();

                                        if (!forwardSelect.value) {
                                            alert('Silakan pilih penerima surat terlebih dahulu!');
                                            forwardSelect.focus();
                                            return;
                                        }

                                        if (rejectSection) {
                                            rejectSection.classList.add('hidden');
                                        }

                                        // Set action URL untuk approve
                                        form.action = "{{ route('incoming-letters.kades-sign', $incomingLetter) }}";
                                        form.submit();
                                    });

                                    rejectBtn?.addEventListener('click', function(e) {
                                        e.preventDefault();

                                        if (!notes.value.trim()) {
                                            alert('Catatan wajib diisi saat menolak surat!');
                                            notes.focus();
                                            return;
                                        }

                                        if (forwardSection) {
                                            forwardSection.classList.add('hidden');
                                        }
                                        if (rejectSection) {
                                            rejectSection.classList.remove('hidden');
                                        }

                                        // Set forward_to ke pengirim asli untuk reject
                                        if (forwardSelect) {
                                            forwardSelect.value = form.querySelector('input[name="original_sender_id"]')
                                                .value;
                                        }

                                        // Set action URL untuk reject
                                        form.action = "{{ route('incoming-letters.kades-reject', $incomingLetter) }}";
                                        form.submit();
                                    });

                                    // Validasi form sebelum submit
                                    form.addEventListener('submit', function(e) {
                                        const isApproveAction = form.action.includes('kades-sign');

                                        if (isApproveAction && !forwardSelect.value) {
                                            e.preventDefault();
                                            alert('Silakan pilih penerima surat terlebih dahulu!');
                                            forwardSelect.focus();
                                            return;
                                        }

                                        if (!isApproveAction && !notes.value.trim()) {
                                            e.preventDefault();
                                            alert('Catatan wajib diisi saat menolak surat!');
                                            notes.focus();
                                            return;
                                        }
                                    });
                                });
                            });
                        </script>
                    </div>
                @endif

                @if (auth()->user()->isUmumDesa() && $incomingLetter->status === 'signed')
                    <div class="bg-base-100 rounded-xl shadow-md p-6 space-y-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <i class="fas fa-file-alt text-success"></i>
                            Tindakan Umum - Pemrosesan Surat
                        </h3>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <span>Silakan proses surat ini dengan memberikan nomor surat final.</span>
                        </div>
                        <form action="{{ route('incoming-letters.process', $incomingLetter) }}" method="POST"
                            class="space-y-4">
                            @csrf
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Nomor Surat Final</span>
                                </label>
                                <input type="text" name="final_letter_number" class="input input-bordered"
                                    placeholder="Masukkan nomor surat final" required
                                    value="{{ old('final_letter_number', $incomingLetter->final_letter_number) }}">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Catatan Proses</span>
                                </label>
                                <textarea name="process_notes" class="textarea textarea-bordered" placeholder="Tambahkan catatan proses..." required>{{ old('process_notes', $incomingLetter->process_notes) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-full gap-2">
                                <i class="fas fa-check-circle"></i>
                                Proses Surat
                            </button>
                        </form>
                    </div>
                @endif

                @if (auth()->user()->isUmumDesa() && $incomingLetter->status === 'processed')
                    <div class="bg-base-100 rounded-xl shadow-md p-6 space-y-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <i class="fas fa-share text-warning"></i>
                            Tindakan Umum - Distribusi Surat
                        </h3>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <span>Silakan upload surat yang sudah lengkap dengan QR Code dan tanda tangan, lalu pilih
                                penerima untuk mendistribusikan surat.</span>
                        </div>
                        <form action="{{ route('incoming-letters.create-disposition', $incomingLetter) }}" method="POST"
                            class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Upload Surat Final</span>
                                    <span class="label-text-alt text-error">*Wajib</span>
                                </label>
                                <input type="file" name="attachment" class="file-input file-input-bordered w-full"
                                    accept=".pdf" required>
                                <label class="label">
                                    <span class="label-text-alt text-info">
                                        <i class="fas fa-info-circle"></i>
                                        Upload file surat yang sudah dilengkapi dengan QR Code dan tanda tangan
                                    </span>
                                </label>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Teruskan Kepada (Bisa Pilih Lebih Dari Satu)</span>
                                </label>
                                <select name="to_user_id[]" class="select select-bordered w-full" multiple required>
                                    @foreach ($users as $user)
                                        @if (!$user->isUmumDesa())
                                            <option value="{{ $user->id }}">{{ $user->name }} -
                                                {{ $user->role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle"></i>
                                    Tekan Ctrl (Windows) atau Command (Mac) untuk memilih lebih dari satu penerima
                                </p>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Catatan untuk Penerima</span>
                                </label>
                                <textarea name="notes" class="textarea textarea-bordered" placeholder="Tambahkan catatan untuk penerima..."
                                    required></textarea>
                            </div>

                            <button type="submit" class="btn btn-info w-full gap-2">
                                <i class="fas fa-share"></i>
                                Distribusikan Surat
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        @if ($incomingLetter->dispositions && $incomingLetter->dispositions->count() > 0)
            <div class="bg-base-100 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-exchange-alt text-info"></i>
                    Riwayat Disposisi
                </h3>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th class="bg-base-200">No</th>
                                <th class="bg-base-200">Dari</th>
                                <th class="bg-base-200">Kepada</th>
                                <th class="bg-base-200">Catatan</th>
                                <th class="bg-base-200">Tanggal</th>
                                <th class="bg-base-200">Status</th>
                                <th class="bg-base-200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incomingLetter->dispositions as $index => $disposition)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $disposition->fromUser->name }}</td>
                                    <td>{{ $disposition->toUser->name }}</td>
                                    <td>{{ $disposition->notes ?? '-' }}</td>
                                    <td>{{ $disposition->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if ($disposition->read_at)
                                            <span class="badge badge-success gap-1">
                                                <i class="fas fa-check-circle"></i>
                                                Dibaca pada {{ $disposition->read_at->format('d/m/Y H:i') }}
                                            </span>
                                        @else
                                            <span class="badge badge-warning gap-1">
                                                <i class="fas fa-clock"></i>
                                                Belum dibaca
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            @if (auth()->id() === $disposition->to_user_id && !$disposition->read_at)
                                                <form
                                                    action="{{ route('incoming-letters.dispositions.mark-as-read', $disposition) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm gap-1">
                                                        <i class="fas fa-check"></i>
                                                        Tandai Dibaca
                                                    </button>
                                                </form>
                                            @endif

                                            @if (auth()->id() === $disposition->from_user_id)
                                                <form
                                                    action="{{ route('incoming-letters.dispositions.delete', $disposition) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus disposisi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-error btn-sm gap-1">
                                                        <i class="fas fa-trash"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
