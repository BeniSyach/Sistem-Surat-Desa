<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Departemen</h1>
            <div class="flex space-x-2">
                <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div
            class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i> Informasi Departemen
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1">
                                    <i class="fas fa-sitemap text-sm"></i> Nama Departemen
                                </label>
                                <p
                                    class="mt-1 text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                    {{ $department->name }}</p>
                            </div>

                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1">
                                    <i class="fas fa-align-left text-sm"></i> Deskripsi
                                </label>
                                <p
                                    class="mt-1 text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                    {{ $department->description ?? '-' }}</p>
                            </div>

                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1">
                                    <i class="fas fa-building text-sm"></i> Desa
                                </label>
                                <p
                                    class="mt-1 text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                    {{ $department->village->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2 mb-4">
                            <i class="fas fa-users text-blue-500"></i> Pengguna
                        </h2>
                        @if ($department->users->count() > 0)
                            <div class="overflow-x-auto">
                                <x-responsive-table>
                                    <thead>
                                        <tr>
                                            <x-table-header>No</x-table-header>
                                            <x-table-header>Nama</x-table-header>
                                            <x-table-header>Email</x-table-header>
                                            <x-table-header>Role</x-table-header>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($department->users as $user)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <x-table-cell>{{ $loop->iteration }}</x-table-cell>
                                                <x-table-cell>
                                                    <span class="font-medium">{{ $user->name }}</span>
                                                </x-table-cell>
                                                <x-table-cell>{{ $user->email }}</x-table-cell>
                                                <x-table-cell>
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-xs">
                                                        {{ $user->role->name }}
                                                    </span>
                                                </x-table-cell>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </x-responsive-table>
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                                <i class="fas fa-users text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500 dark:text-gray-400">Tidak ada pengguna dalam departemen ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
