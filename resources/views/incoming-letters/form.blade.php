<x-app-layout>
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold">{{ isset($incomingLetter) ? 'Edit Surat Masuk' : 'Tambah Surat Masuk' }}
            </h2>
        </div>

        <div class="bg-base-100 rounded-xl shadow-md p-6">
            <form
                action="{{ isset($incomingLetter) ? route('incoming-letters.update', $incomingLetter) : route('incoming-letters.store') }}"
                method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @if (isset($incomingLetter))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Nomor Surat</span>
                        </label>
                        <input type="text" name="letter_number" class="input input-bordered"
                            value="{{ old('letter_number', $incomingLetter->letter_number ?? '') }}" required>
                        @error('letter_number')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Pengirim</span>
                        </label>
                        <input type="text" name="sender" class="input input-bordered"
                            value="{{ old('sender', $incomingLetter->sender ?? '') }}" required>
                        @error('sender')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tanggal Surat</span>
                        </label>
                        <input type="date" name="letter_date" class="input input-bordered"
                            value="{{ old('letter_date', isset($incomingLetter) ? $incomingLetter->letter_date->format('Y-m-d') : '') }}"
                            required>
                        @error('letter_date')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tanggal Diterima</span>
                        </label>
                        <input type="date" name="received_date" class="input input-bordered"
                            value="{{ old('received_date', isset($incomingLetter) ? $incomingLetter->received_date->format('Y-m-d') : '') }}"
                            required>
                        @error('received_date')
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
                            value="{{ old('subject', $incomingLetter->subject ?? '') }}" required>
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
                            accept=".pdf,.doc,.docx" {{ isset($incomingLetter) ? '' : 'required' }}>
                        @error('letter_file')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                        @if (isset($incomingLetter) && $incomingLetter->letter_file)
                            <label class="label">
                                <span class="label-text-alt">File saat ini: {{ $incomingLetter->letter_file }}</span>
                            </label>
                        @endif
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Keterangan</span>
                    </label>
                    <textarea name="description" class="textarea textarea-bordered h-32">{{ old('description', $incomingLetter->description ?? '') }}</textarea>
                    @error('description')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('incoming-letters.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
