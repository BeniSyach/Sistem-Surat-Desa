<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Pengguna</h1>
            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div
            class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-user text-sm"></i> Nama
                        </h3>
                        <p
                            class="mt-1 text-lg text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                            {{ $user->name }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-envelope text-sm"></i> Email
                        </h3>
                        <p
                            class="mt-1 text-lg text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                            {{ $user->email }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-user-tag text-sm"></i> Role
                        </h3>
                        <p
                            class="mt-1 text-lg text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                            {{ $user->role->name }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-toggle-on text-sm"></i> Status
                        </h3>
                        <p class="mt-1 bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                            @if ($user->is_active)
                                <span
                                    class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full text-sm">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full text-sm">
                                    <i class="fas fa-times-circle mr-1"></i> Nonaktif
                                </span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-building text-sm"></i> Desa
                        </h3>
                        <p
                            class="mt-1 text-lg text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                            {{ $user->village->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-sitemap text-sm"></i> Departemen
                        </h3>
                        <p
                            class="mt-1 text-lg text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                            {{ $user->department->name ?? '-' }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1 mb-3">
                            <i class="fas fa-clipboard-list text-sm"></i> Aktivitas Surat
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if ($user->role->name === 'Menandatangani Surat')
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-1">
                                        <i class="fas fa-signature text-blue-500"></i> Surat yang Ditandatangani
                                    </h4>
                                    <div class="overflow-x-auto">
                                        <x-responsive-table>
                                            <thead>
                                                <tr>
                                                    <x-table-header>No</x-table-header>
                                                    <x-table-header>Nomor Surat</x-table-header>
                                                    <x-table-header>Tanggal TTD</x-table-header>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($user->approvedOutgoingLettersAsKades as $letter)
                                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                                        <x-table-cell>{{ $loop->iteration }}</x-table-cell>
                                                        <x-table-cell>
                                                            <span
                                                                class="font-medium">{{ $letter->letter_number ?? 'Draft' }}</span>
                                                        </x-table-cell>
                                                        <x-table-cell>{{ $letter->kades_approved_at->format('d/m/Y H:i') }}</x-table-cell>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <x-table-cell colspan="3"
                                                            class="text-center text-gray-500 dark:text-gray-400">
                                                            Tidak ada surat yang ditandatangani
                                                        </x-table-cell>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </x-responsive-table>
                                    </div>
                                </div>
                            @endif

                            @if ($user->role->name === 'Memparaf Surat')
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-1">
                                        <i class="fas fa-check text-green-500"></i> Surat yang Diparaf
                                    </h4>
                                    <div class="overflow-x-auto">
                                        <x-responsive-table>
                                            <thead>
                                                <tr>
                                                    <x-table-header>No</x-table-header>
                                                    <x-table-header>Nomor Surat</x-table-header>
                                                    <x-table-header>Tanggal Paraf</x-table-header>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($user->approvedOutgoingLettersAsSekdes as $letter)
                                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                                        <x-table-cell>{{ $loop->iteration }}</x-table-cell>
                                                        <x-table-cell>
                                                            <span
                                                                class="font-medium">{{ $letter->letter_number ?? 'Draft' }}</span>
                                                        </x-table-cell>
                                                        <x-table-cell>{{ $letter->sekdes_approved_at->format('d/m/Y H:i') }}</x-table-cell>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <x-table-cell colspan="3"
                                                            class="text-center text-gray-500 dark:text-gray-400">
                                                            Tidak ada surat yang diparaf
                                                        </x-table-cell>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </x-responsive-table>
                                    </div>
                                </div>
                            @endif

                            @if ($user->role->name === 'Pembuat Surat')
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-1">
                                        <i class="fas fa-file-alt text-purple-500"></i> Surat yang Dibuat
                                    </h4>
                                    <div class="overflow-x-auto">
                                        <x-responsive-table>
                                            <thead>
                                                <tr>
                                                    <x-table-header>No</x-table-header>
                                                    <x-table-header>Nomor Surat</x-table-header>
                                                    <x-table-header>Tanggal Dibuat</x-table-header>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($user->outgoingLetters as $letter)
                                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                                        <x-table-cell>{{ $loop->iteration }}</x-table-cell>
                                                        <x-table-cell>
                                                            <span
                                                                class="font-medium">{{ $letter->letter_number ?? 'Draft' }}</span>
                                                        </x-table-cell>
                                                        <x-table-cell>{{ $letter->created_at->format('d/m/Y H:i') }}</x-table-cell>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <x-table-cell colspan="3"
                                                            class="text-center text-gray-500 dark:text-gray-400">
                                                            Tidak ada surat yang dibuat
                                                        </x-table-cell>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </x-responsive-table>
                                    </div>
                                </div>
                            @endif

                            @if ($user->role->name === 'Bagian Umum')
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-1">
                                        <i class="fas fa-cogs text-purple-500"></i> Surat yang Diproses
                                    </h4>
                                    <div class="overflow-x-auto">
                                        <x-responsive-table>
                                            <thead>
                                                <tr>
                                                    <x-table-header>No</x-table-header>
                                                    <x-table-header>Nomor Surat</x-table-header>
                                                    <x-table-header>Tanggal Proses</x-table-header>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($user->processedOutgoingLetters as $letter)
                                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                                        <x-table-cell>{{ $loop->iteration }}</x-table-cell>
                                                        <x-table-cell>
                                                            <span
                                                                class="font-medium">{{ $letter->letter_number ?? 'Draft' }}</span>
                                                        </x-table-cell>
                                                        <x-table-cell>{{ $letter->processed_at->format('d/m/Y H:i') }}</x-table-cell>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <x-table-cell colspan="3"
                                                            class="text-center text-gray-500 dark:text-gray-400">
                                                            Tidak ada surat yang diproses
                                                        </x-table-cell>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </x-responsive-table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
