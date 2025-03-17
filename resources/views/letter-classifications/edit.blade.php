<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Klasifikasi Surat</h1>
            <a href="{{ route('letter-classifications.index') }}" class="btn btn-secondary flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div
            class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <form action="{{ route('letter-classifications.update', $letterClassification) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="code"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-hashtag mr-1"></i> Kode <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('code') input-error @enderror"
                                value="{{ old('code', $letterClassification->code) }}" required>
                            @error('code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-tag mr-1"></i> Nama <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('name') input-error @enderror"
                                value="{{ old('name', $letterClassification->name) }}" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 md:col-span-2">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-align-left mr-1"></i> Deskripsi
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="textarea textarea-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('description') textarea-error @enderror">{{ old('description', $letterClassification->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="btn btn-primary flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
