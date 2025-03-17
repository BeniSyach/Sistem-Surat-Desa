@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-envelope-open-text text-blue-500"></i> Tambah Surat Masuk
            </h1>
            <a href="{{ route('incoming-letters.index') }}" class="btn btn-secondary flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div
            class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <form action="{{ route('incoming-letters.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="letter_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-hashtag text-blue-500"></i> Nomor Surat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="letter_number" id="letter_number"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('letter_number') input-error @enderror"
                                value="{{ old('letter_number') }}" required>
                            @error('letter_number')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="letter_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-calendar-alt text-blue-500"></i> Tanggal Surat <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="date" name="letter_date" id="letter_date"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('letter_date') input-error @enderror"
                                value="{{ old('letter_date') }}" required>
                            @error('letter_date')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="received_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-calendar-check text-blue-500"></i> Tanggal Diterima <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="date" name="received_date" id="received_date"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('received_date') input-error @enderror"
                                value="{{ old('received_date') }}" required>
                            @error('received_date')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="sender"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-user text-blue-500"></i> Pengirim <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sender" id="sender"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('sender') input-error @enderror"
                                value="{{ old('sender') }}" required>
                            @error('sender')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="sender_village_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-building text-blue-500"></i> Desa Pengirim <span
                                    class="text-red-500">*</span>
                            </label>
                            <select name="sender_village_id" id="sender_village_id"
                                class="select select-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('sender_village_id') select-error @enderror"
                                required>
                                <option value="">Pilih Instansi Pengirim</option>
                                @foreach ($villages as $village)
                                    <option value="{{ $village->id }}"
                                        {{ old('sender_village_id') == $village->id ? 'selected' : '' }}>
                                        {{ $village->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sender_village_id')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="receiver_user_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-user-check text-blue-500"></i> Penerima <span class="text-red-500">*</span>
                            </label>
                            <select name="receiver_user_id" id="receiver_user_id"
                                class="select select-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('receiver_user_id') select-error @enderror"
                                required>
                                <option value="">Pilih Penerima</option>
                                @foreach ($users as $user)
                                    @if ($user->village && $user->role)
                                        <option value="{{ $user->id }}"
                                            {{ old('receiver_user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->village->name }} - {{ $user->role->name }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('receiver_user_id')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="subject"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-tag text-blue-500"></i> Perihal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="subject" id="subject"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('subject') input-error @enderror"
                                value="{{ old('subject') }}" required>
                            @error('subject')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="classification_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-folder text-blue-500"></i> Klasifikasi Surat <span
                                    class="text-red-500">*</span>
                            </label>
                            <select name="classification_id" id="classification_id"
                                class="select select-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('classification_id') select-error @enderror"
                                required>
                                <option value="">Pilih Klasifikasi</option>
                                @foreach ($classifications as $classification)
                                    <option value="{{ $classification->id }}"
                                        {{ old('classification_id') == $classification->id ? 'selected' : '' }}>
                                        {{ $classification->code }} - {{ $classification->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classification_id')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="confidentiality"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-lock text-blue-500"></i> Sifat Surat <span class="text-red-500">*</span>
                            </label>
                            <select name="confidentiality" id="confidentiality"
                                class="select select-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('confidentiality') select-error @enderror"
                                required>
                                <option value="biasa" {{ old('confidentiality') == 'biasa' ? 'selected' : '' }}>Biasa
                                </option>
                                <option value="rahasia" {{ old('confidentiality') == 'rahasia' ? 'selected' : '' }}>
                                    Rahasia
                                </option>
                                <option value="umum" {{ old('confidentiality') == 'umum' ? 'selected' : '' }}>Umum
                                </option>
                            </select>
                            @error('confidentiality')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4 md:col-span-2">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-align-left text-blue-500"></i> Deskripsi <span
                                    class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="textarea textarea-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('description') textarea-error @enderror"
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="attachment"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-file-pdf text-blue-500"></i> File Surat (PDF) <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="file" name="attachment" id="attachment" accept=".pdf"
                                class="file-input file-input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('attachment') file-input-error @enderror"
                                required>
                            @error('attachment')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                <i class="fas fa-sticky-note text-blue-500"></i> Catatan
                            </label>
                            <textarea name="notes" id="notes"
                                class="textarea textarea-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('notes') textarea-error @enderror"
                                rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-6">
                        <a href="{{ route('incoming-letters.index') }}" class="btn btn-ghost flex items-center gap-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
