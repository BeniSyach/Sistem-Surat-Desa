<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-paper-plane text-blue-500"></i> Buat Surat Keluar
            </h1>
            <a href="{{ route('outgoing-letters.index') }}" class="btn btn-secondary flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
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
            <form action="{{ route('outgoing-letters.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label" for="letter_date">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-calendar text-blue-500"></i> Tanggal Surat
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="date" name="letter_date" id="letter_date"
                            class="input input-bordered @error('letter_date') input-error @enderror"
                            value="{{ old('letter_date', date('Y-m-d')) }}" required>
                        @error('letter_date')
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
                                    {{ old('classification_id') == $classification->id ? 'selected' : '' }}>
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
                        <label class="label" for="department_id">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-building text-blue-500"></i> Departemen
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="department_id" id="department_id"
                            class="select select-bordered @error('department_id') select-error @enderror" required>
                            <option value="">Pilih Departemen</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
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
                            <option value="">Pilih Sifat Surat</option>
                            <option value="biasa" {{ old('confidentiality') == 'biasa' ? 'selected' : '' }}>Biasa
                            </option>
                            <option value="rahasia" {{ old('confidentiality') == 'rahasia' ? 'selected' : '' }}>Rahasia
                            </option>
                            <option value="umum" {{ old('confidentiality') == 'umum' ? 'selected' : '' }}>Umum
                            </option>
                        </select>
                        @error('confidentiality')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label" for="recipient_id">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-user text-blue-500"></i> Penerima
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="recipient_id" id="recipient_id"
                            class="select select-bordered @error('recipient_id') select-error @enderror" required>
                            <option value="">Pilih Penerima</option>
                            @foreach ($recipients as $recipient)
                                <option value="{{ $recipient->id }}"
                                    {{ old('recipient_id') == $recipient->id ? 'selected' : '' }}>
                                    {{ $recipient->name }} - {{ $recipient->role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('recipient_id')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label" for="signer_id">
                            <span class="label-text flex items-center gap-1">
                                <i class="fas fa-signature text-blue-500"></i> Akan Ditandatangani Oleh
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="signer_id" id="signer_id"
                            class="select select-bordered @error('signer_id') select-error @enderror" required>
                            <option value="">Pilih Penandatangan</option>
                            @foreach ($signers as $signer)
                                <option value="{{ $signer->id }}"
                                    {{ old('signer_id') == $signer->id ? 'selected' : '' }}>
                                    {{ $signer->name }} - {{ $signer->role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('signer_id')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
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
                        value="{{ old('subject') }}" required>
                    @error('subject')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label" for="content">
                        <span class="label-text flex items-center gap-1">
                            <i class="fas fa-align-left text-blue-500"></i> Isi Surat
                            <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <textarea name="content" id="content"
                        class="textarea textarea-bordered h-32 @error('content') textarea-error @enderror" required>{{ old('content') }}</textarea>
                    @error('content')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label" for="attachment">
                        <span class="label-text flex items-center gap-1">
                            <i class="fas fa-paperclip text-blue-500"></i> Lampiran (PDF)
                        </span>
                    </label>
                    <input type="file" name="attachment" id="attachment"
                        class="file-input file-input-bordered @error('attachment') file-input-error @enderror"
                        accept=".pdf">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <i class="fas fa-info-circle"></i> Maksimal 2MB, format PDF
                    </p>
                    @error('attachment')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('outgoing-letters.index') }}" class="btn btn-ghost">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
