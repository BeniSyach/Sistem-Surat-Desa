<x-app-layout>
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold">{{ isset($outgoingLetter) ? 'Edit Surat Keluar' : 'Buat Surat Keluar' }}
            </h2>
        </div>

        <div class="bg-base-100 rounded-xl shadow-md p-6">
            <form
                action="{{ isset($outgoingLetter) ? route('outgoing-letters.update', $outgoingLetter) : route('outgoing-letters.store') }}"
                method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @if (isset($outgoingLetter))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Klasifikasi Surat</span>
                        </label>
                        <select name="letter_classification_id" class="select select-bordered w-full" required>
                            <option value="">Pilih Klasifikasi</option>
                            @foreach ($letterClassifications as $classification)
                                <option value="{{ $classification->id }}"
                                    {{ old('letter_classification_id', $outgoingLetter->letter_classification_id ?? '') == $classification->id ? 'selected' : '' }}>
                                    {{ $classification->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('letter_classification_id')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tujuan</span>
                        </label>
                        <input type="text" name="destination" class="input input-bordered"
                            value="{{ old('destination', $outgoingLetter->destination ?? '') }}" required>
                        @error('destination')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Perihal</span>
                        </label>
                        <input type="text" name="subject" class="input input-bordered"
                            value="{{ old('subject', $outgoingLetter->subject ?? '') }}" required>
                        @error('subject')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">File Surat</span>
                        </label>
                        <input type="file" name="letter_file" class="file-input file-input-bordered w-full"
                            accept=".pdf,.doc,.docx" {{ isset($outgoingLetter) ? '' : 'required' }}>
                        @error('letter_file')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                        @if (isset($outgoingLetter) && $outgoingLetter->letter_file)
                            <label class="label">
                                <span class="label-text-alt">File saat ini: {{ $outgoingLetter->letter_file }}</span>
                            </label>
                        @endif
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Isi Surat</span>
                    </label>
                    <textarea name="content" class="textarea textarea-bordered h-32" required>{{ old('content', $outgoingLetter->content ?? '') }}</textarea>
                    @error('content')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('outgoing-letters.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
