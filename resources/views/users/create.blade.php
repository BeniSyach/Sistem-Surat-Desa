<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Pengguna</h1>
            <a href="{{ route('users.index') }}" class="btn btn-secondary flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div
            class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-user mr-1"></i> Nama <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('name') input-error @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-envelope mr-1"></i> Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email') input-error @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-lock mr-1"></i> Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('password') input-error @enderror"
                                required>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-check-circle mr-1"></i> Konfirmasi Password <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="input input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="role_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-user-tag mr-1"></i> Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role_id" id="role_id"
                                class="select select-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('role_id') select-error @enderror"
                                required>
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="village_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-building mr-1"></i> Instansi <span class="text-red-500">*</span>
                            </label>
                            <select name="village_id" id="village_id"
                                class="select select-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('village_id') select-error @enderror"
                                required>
                                <option value="">Pilih Instansi</option>
                                @foreach ($villages as $village)
                                    <option value="{{ $village->id }}"
                                        {{ old('village_id') == $village->id ? 'selected' : '' }}>
                                        {{ $village->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('village_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="department_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-sitemap mr-1"></i> Departemen
                            </label>
                            <select name="department_id" id="department_id"
                                class="select select-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('department_id') select-error @enderror">
                                <option value="">Pilih Departemen</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input type="checkbox" name="is_active" id="is_active"
                                    class="checkbox checkbox-primary @error('is_active') checkbox-error @enderror"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-toggle-on mr-1"></i> Aktif
                                </span>
                            </label>
                            @error('is_active')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="btn btn-primary flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
