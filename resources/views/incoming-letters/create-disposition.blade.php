@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Disposisi Surat</h1>
            <a href="{{ route('incoming-letters.show', $incomingLetter) }}" class="btn btn-ghost">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Surat -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Informasi Surat</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600 text-sm">Nomor Surat</p>
                            <p class="font-medium">{{ $incomingLetter->letter_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Pengirim</p>
                            <p class="font-medium">{{ $incomingLetter->sender }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Tanggal Surat</p>
                            <p class="font-medium">{{ $incomingLetter->letter_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Perihal</p>
                            <p class="font-medium">{{ $incomingLetter->subject }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Sifat</p>
                            <p>
                                @if ($incomingLetter->confidentiality == 'biasa')
                                    <span class="badge badge-info">Biasa</span>
                                @elseif($incomingLetter->confidentiality == 'rahasia')
                                    <span class="badge badge-error">Rahasia</span>
                                @elseif($incomingLetter->confidentiality == 'umum')
                                    <span class="badge badge-success">Umum</span>
                                @else
                                    <span class="badge badge-info">Biasa</span>
                                @endif
                            </p>
                        </div>
                        @if ($incomingLetter->attachment)
                            <div>
                                <a href="{{ route('incoming-letters.download-attachment', $incomingLetter) }}"
                                    class="btn btn-primary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Unduh Lampiran
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Disposisi -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Form Disposisi</h2>

                    <div class="alert alert-info mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="stroke-current shrink-0 w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Disposisi akan membuat surat masuk baru untuk penerima yang dipilih.</span>
                    </div>

                    <form action="{{ route('incoming-letters.store-disposition', $incomingLetter) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">Tujuan Disposisi</span>
                                </label>
                                <select name="to_user_id" class="select select-bordered w-full" required>
                                    <option value="">Pilih Penerima Disposisi</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->role->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('to_user_id')
                                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">Catatan Disposisi</span>
                                </label>
                                <textarea name="notes" class="textarea textarea-bordered h-32"
                                    placeholder="Masukkan catatan atau instruksi untuk penerima disposisi" required></textarea>
                                @error('notes')
                                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">Lampiran (Opsional)</span>
                                </label>
                                <input type="file" name="attachment" class="file-input file-input-bordered w-full"
                                    accept=".pdf" />
                                <p class="text-sm text-gray-500 mt-1">
                                    Jika tidak dilampirkan, lampiran surat asli akan digunakan.
                                </p>
                                @error('attachment')
                                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                    Kirim Disposisi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
