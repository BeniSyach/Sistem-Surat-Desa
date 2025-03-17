@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-edit text-blue-500"></i> Edit Surat Masuk
            </h1>
            <div class="flex gap-2">
                <a href="{{ route('incoming-letters.show', $incomingLetter) }}"
                    class="btn btn-secondary flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-error mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <form action="{{ route('incoming-letters.update', $incomingLetter) }}" method="POST"
                enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label" for="letter_number">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-hashtag text-blue-500"></i> Nomor Surat
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" name="letter_number" id="letter_number"
                            class="input input-bordered @error('letter_number') input-error @enderror"
                            value="{{ old('letter_number', $incomingLetter->letter_number) }}" required>
                        @error('letter_number')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label" for="letter_date">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-calendar text-blue-500"></i> Tanggal Surat
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="date" name="letter_date" id="letter_date"
                            class="input input-bordered @error('letter_date') input-error @enderror"
                            value="{{ old('letter_date', $incomingLetter->letter_date->format('Y-m-d')) }}" required>
                        @error('letter_date')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label" for="received_date">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-inbox text-blue-500"></i> Tanggal Diterima
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="date" name="received_date" id="received_date"
                            class="input input-bordered @error('received_date') input-error @enderror"
                            value="{{ old('received_date', $incomingLetter->received_date->format('Y-m-d')) }}" required>
                        @error('received_date')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label" for="sender">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-user text-blue-500"></i> Pengirim
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" name="sender" id="sender"
                            class="input input-bordered @error('sender') input-error @enderror"
                            value="{{ old('sender', $incomingLetter->sender) }}" required>
                        @error('sender')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label" for="subject">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-envelope text-blue-500"></i> Perihal
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" name="subject" id="subject"
                            class="input input-bordered @error('subject') input-error @enderror"
                            value="{{ old('subject', $incomingLetter->subject) }}" required>
                        @error('subject')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label" for="classification_id">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-folder text-blue-500"></i> Klasifikasi Surat
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="classification_id" id="classification_id"
                            class="select select-bordered @error('classification_id') select-error @enderror" required>
                            <option value="">Pilih Klasifikasi</option>
                            @foreach ($classifications as $classification)
                                <option value="{{ $classification->id }}"
                                    {{ old('classification_id', $incomingLetter->classification_id) == $classification->id ? 'selected' : '' }}>
                                    {{ $classification->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('classification_id')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label" for="confidentiality">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-lock text-blue-500"></i> Sifat Surat
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="confidentiality" id="confidentiality"
                            class="select select-bordered @error('confidentiality') select-error @enderror" required>
                            <option value="biasa"
                                {{ old('confidentiality', $incomingLetter->confidentiality) == 'biasa' ? 'selected' : '' }}>
                                Biasa</option>
                            <option value="rahasia"
                                {{ old('confidentiality', $incomingLetter->confidentiality) == 'rahasia' ? 'selected' : '' }}>
                                Rahasia</option>
                            <option value="umum"
                                {{ old('confidentiality', $incomingLetter->confidentiality) == 'umum' ? 'selected' : '' }}>
                                Umum</option>
                        </select>
                        @error('confidentiality')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>

                <div class="form-control">
                    <label class="label" for="description">
                        <span class="label-text flex items-center gap-1">
                            <i class="fas fa-align-left text-blue-500"></i> Deskripsi
                            <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <textarea name="description" id="description"
                        class="textarea textarea-bordered h-32 @error('description') textarea-error @enderror" required>{{ old('description', $incomingLetter->description) }}</textarea>
                    @error('description')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label" for="notes">
                        <span class="label-text flex items-center gap-1">
                            <i class="fas fa-sticky-note text-blue-500"></i> Catatan
                        </span>
                    </label>
                    <textarea name="notes" id="notes"
                        class="textarea textarea-bordered h-24 @error('notes') textarea-error @enderror">{{ old('notes', $incomingLetter->notes) }}</textarea>
                    @error('notes')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label" for="attachment">
                        <span class="label-text flex items-center gap-1">
                            <i class="fas fa-paperclip text-blue-500"></i> File Surat (PDF, maks. 2MB)
                        </span>
                    </label>
                    <input type="file" name="attachment" id="attachment"
                        class="file-input file-input-bordered @error('attachment') file-input-error @enderror"
                        accept=".pdf">
                    @if ($incomingLetter->attachment)
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <p>File saat ini: <a href="{{ Storage::url($incomingLetter->attachment) }}" target="_blank"
                                    class="link link-primary">Lihat File</a></p>
                            <p class="text-xs text-gray-500">Upload file baru untuk mengganti file saat ini</p>
                        </div>
                    @endif
                    @error('attachment')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('incoming-letters.show', $incomingLetter) }}" class="btn btn-ghost">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
