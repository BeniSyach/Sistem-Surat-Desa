<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Edit Surat Keluar</h1>
            <a href="{{ route('outgoing-letters.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('outgoing-letters.update', $outgoingLetter) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label for="letter_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="letter_date" id="letter_date"
                            class="input input-bordered w-full @error('letter_date') input-error @enderror"
                            value="{{ old('letter_date', $outgoingLetter->letter_date->format('Y-m-d')) }}" required>
                        @error('letter_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                            Perihal <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" id="subject"
                            class="input input-bordered w-full @error('subject') input-error @enderror"
                            value="{{ old('subject', $outgoingLetter->subject) }}" required>
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="classification_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Klasifikasi <span class="text-red-500">*</span>
                        </label>
                        <select name="classification_id" id="classification_id"
                            class="select select-bordered w-full @error('classification_id') select-error @enderror"
                            required>
                            <option value="">Pilih Klasifikasi</option>
                            @foreach ($classifications as $classification)
                                <option value="{{ $classification->id }}"
                                    {{ old('classification_id', $outgoingLetter->classification_id) == $classification->id ? 'selected' : '' }}>
                                    {{ $classification->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('classification_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="confidentiality" class="block text-sm font-medium text-gray-700 mb-1">
                            Sifat Surat <span class="text-red-500">*</span>
                        </label>
                        <select name="confidentiality" id="confidentiality"
                            class="select select-bordered w-full @error('confidentiality') select-error @enderror"
                            required>
                            <option value="biasa"
                                {{ old('confidentiality', $outgoingLetter->confidentiality) == 'biasa' ? 'selected' : '' }}>
                                Biasa</option>
                            <option value="rahasia"
                                {{ old('confidentiality', $outgoingLetter->confidentiality) == 'rahasia' ? 'selected' : '' }}>
                                Rahasia</option>
                            <option value="umum"
                                {{ old('confidentiality', $outgoingLetter->confidentiality) == 'umum' ? 'selected' : '' }}>
                                Umum</option>
                        </select>
                        @error('confidentiality')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Departemen <span class="text-red-500">*</span>
                        </label>
                        <select name="department_id" id="department_id"
                            class="select select-bordered w-full @error('department_id') select-error @enderror"
                            required>
                            <option value="">Pilih Departemen</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $outgoingLetter->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="signer_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Akan Ditandatangani Oleh <span class="text-red-500">*</span>
                        </label>
                        <select name="signer_id" id="signer_id"
                            class="select select-bordered w-full @error('signer_id') select-error @enderror" required>
                            <option value="">Pilih Penandatangan</option>
                            @foreach ($signers as $signer)
                                <option value="{{ $signer->id }}"
                                    {{ old('signer_id', $outgoingLetter->signer_id) == $signer->id ? 'selected' : '' }}>
                                    {{ $signer->name }} -
                                    {{ $signer->role ? $signer->role->name : 'Tidak ada role' }}
                                </option>
                            @endforeach
                        </select>
                        @error('signer_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                        Isi Surat <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" id="content" rows="6"
                        class="textarea textarea-bordered w-full @error('content') textarea-error @enderror" required>{{ old('content', $outgoingLetter->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">
                        Lampiran (PDF)
                    </label>
                    @if ($outgoingLetter->attachment)
                        <div class="mb-2">
                            <a href="{{ route('outgoing-letters.download-attachment', $outgoingLetter) }}"
                                class="text-blue-600 hover:underline">
                                <i class="fas fa-paperclip mr-1"></i> Lampiran saat ini
                            </a>
                            <p class="text-sm text-gray-500">Unggah file baru untuk mengganti lampiran saat ini</p>
                        </div>
                    @endif
                    <input type="file" name="attachment" id="attachment" accept=".pdf"
                        class="file-input file-input-bordered w-full @error('attachment') file-input-error @enderror">
                    <p class="text-sm text-gray-500 mt-1">Maksimal 2MB, format PDF</p>
                    @error('attachment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <a href="{{ route('outgoing-letters.show', $outgoingLetter) }}" class="btn btn-ghost">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
