<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Data Instansi</h1>
            <a href="{{ route('villages.index') }}" class="btn btn-secondary flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div
            class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <form action="{{ route('villages.update', $village) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-building mr-1"></i> Nama Instansi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('name') input-error @enderror"
                                value="{{ old('name', $village->name) }}" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="village_head"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-user mr-1"></i> Kepala Instansi
                            </label>
                            <input type="text" name="village_head" id="village_head"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('village_head') input-error @enderror"
                                value="{{ old('village_head', $village->village_head) }}">
                            @error('village_head')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 md:col-span-2">
                            <label for="address"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-map-marker-alt mr-1"></i> Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" id="address" rows="3"
                                class="textarea textarea-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('address') textarea-error @enderror"
                                required>{{ old('address', $village->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-phone mr-1"></i> Telepon
                            </label>
                            <input type="text" name="phone" id="phone"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('phone') input-error @enderror"
                                value="{{ old('phone', $village->phone) }}">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-envelope mr-1"></i> Email
                            </label>
                            <input type="email" name="email" id="email"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email') input-error @enderror"
                                value="{{ old('email', $village->email) }}">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 md:col-span-2">
                            <label for="logo"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-image mr-1"></i> Logo
                            </label>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <div id="preview-container"
                                        class="{{ $village->logo ? '' : 'hidden' }} w-full h-full">
                                        <img id="preview-image"
                                            src="{{ $village->logo ? Storage::url($village->logo) : '#' }}"
                                            alt="Logo Desa" class="w-full h-full object-cover rounded-lg">
                                    </div>
                                    <div id="placeholder" class="{{ $village->logo ? 'hidden' : '' }} text-center p-2">
                                        <i class="fas fa-upload text-gray-400 text-2xl mb-1"></i>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Pilih gambar</p>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo" id="logo"
                                        class="file-input file-input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('logo') file-input-error @enderror"
                                        accept="image/*">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Format: JPEG, PNG, JPG.
                                        Ukuran maksimal: 2MB.</p>
                                    @if ($village->logo)
                                        <div class="flex items-center mt-2">
                                            <input type="checkbox" name="remove_logo" id="remove_logo"
                                                class="checkbox checkbox-sm mr-2">
                                            <label for="remove_logo"
                                                class="text-sm text-gray-600 dark:text-gray-400">Hapus logo saat
                                                ini</label>
                                        </div>
                                    @endif
                                    @error('logo')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('logo');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const placeholder = document.getElementById('placeholder');
            const removeLogoCheckbox = document.getElementById('remove_logo');

            logoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        if (removeLogoCheckbox) {
                            removeLogoCheckbox.checked = false;
                        }
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });

            if (removeLogoCheckbox) {
                removeLogoCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        previewContainer.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        logoInput.value = '';
                    }
                });
            }
        });
    </script>
</x-app-layout>
