<x-app-layout>
    @section('title', 'Surat Keluar')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Surat Keluar</h1>
            <a href="{{ route('outgoing-letters.create') }}" class="btn btn-primary flex items-center gap-2">
                <i class="fas fa-plus"></i> Tulis Surat Baru
            </a>
        </div>

        <div
            class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium mb-2 text-gray-800 dark:text-white">Filter Surat</h2>
                <form action="{{ route('outgoing-letters.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-gray-700 dark:text-gray-300">Status</span>
                        </label>
                        <select name="status"
                            class="select select-bordered bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sekdes_review" {{ request('status') == 'sekdes_review' ? 'selected' : '' }}>
                                Review Sekretaris Desa</option>
                            <option value="kades_review" {{ request('status') == 'kades_review' ? 'selected' : '' }}>
                                Review Kepala Desa</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                            <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Diproses
                            </option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-gray-700 dark:text-gray-300">Tanggal Mulai</span>
                        </label>
                        <input type="date" name="start_date"
                            class="input input-bordered bg-white dark:bg-gray-700 text-gray-800 dark:text-white"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-gray-700 dark:text-gray-300">Tanggal Akhir</span>
                        </label>
                        <input type="date" name="end_date"
                            class="input input-bordered bg-white dark:bg-gray-700 text-gray-800 dark:text-white"
                            value="{{ request('end_date') }}">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-gray-700 dark:text-gray-300">Cari</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="search"
                                class="input input-bordered bg-white dark:bg-gray-700 text-gray-800 dark:text-white"
                                placeholder="Cari surat..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <x-responsive-table>
            <thead>
                <tr>
                    <x-table-header class="w-12">No</x-table-header>
                    <x-table-header>Nomor Surat</x-table-header>
                    <x-table-header>Perihal</x-table-header>
                    <x-table-header class="hidden sm:table-cell">Pembuat</x-table-header>
                    <x-table-header>Status</x-table-header>
                    <x-table-header class="hidden md:table-cell">Sifat</x-table-header>
                    <x-table-header class="hidden md:table-cell">Tanggal Dibuat</x-table-header>
                    <x-table-header>Aksi</x-table-header>
                </tr>
            </thead>
            <tbody>
                @forelse($letters as $letter)
                    <tr>
                        <x-table-cell class="font-medium">{{ $loop->iteration }}</x-table-cell>
                        <x-table-cell>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $letter->letter_number ?? 'Draft' }}
                            </div>
                        </x-table-cell>
                        <x-table-cell>
                            <span class="text-sm">{{ $letter->subject }}</span>
                        </x-table-cell>
                        <x-table-cell class="hidden sm:table-cell">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-2">
                                    <span
                                        class="text-blue-600 dark:text-blue-400 font-medium">{{ substr($letter->creator->name, 0, 1) }}</span>
                                </div>
                                <span>{{ $letter->creator->name }}</span>
                            </div>
                        </x-table-cell>
                        <x-table-cell>
                            @switch($letter->status)
                                @case('draft')
                                    <span
                                        class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-md text-xs font-medium">
                                        Draft
                                    </span>
                                @break

                                @case('sekdes_review')
                                    <span
                                        class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md text-xs font-medium">
                                        Review Sekretaris Desa
                                    </span>
                                @break

                                @case('kades_review')
                                    <span
                                        class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md text-xs font-medium">
                                        Review Kepala Desa
                                    </span>
                                @break

                                @case('approved')
                                    <span
                                        class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-md text-xs font-medium">
                                        Disetujui
                                    </span>
                                @break

                                @case('rejected')
                                    <span
                                        class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md text-xs font-medium">
                                        Ditolak
                                    </span>
                                @break

                                @case('processed')
                                    <span
                                        class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-md text-xs font-medium">
                                        Diproses
                                    </span>
                                @break

                                @default
                                    <span
                                        class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-md text-xs font-medium">
                                        {{ $letter->status }}
                                    </span>
                            @endswitch
                        </x-table-cell>
                        <x-table-cell class="hidden md:table-cell">
                            @if ($letter->confidentiality == 'biasa')
                                <span
                                    class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md text-xs font-medium">
                                    Biasa
                                </span>
                            @elseif($letter->confidentiality == 'rahasia')
                                <span
                                    class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md text-xs font-medium">
                                    Rahasia
                                </span>
                            @elseif($letter->confidentiality == 'umum')
                                <span
                                    class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-md text-xs font-medium">
                                    Umum
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md text-xs font-medium capitalize">
                                    {{ $letter->confidentiality }}
                                </span>
                            @endif
                        </x-table-cell>
                        <x-table-cell class="hidden md:table-cell">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                {{ $letter->created_at->format('d/m/Y') }}
                            </div>
                        </x-table-cell>
                        <x-table-cell>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('outgoing-letters.show', $letter) }}"
                                    class="inline-flex items-center px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md hover:bg-blue-200 dark:hover:bg-blue-800/30 transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                @if ($letter->status == 'draft' && $letter->creator_id == auth()->id())
                                    <a href="{{ route('outgoing-letters.edit', $letter) }}"
                                        class="inline-flex items-center px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md hover:bg-amber-200 dark:hover:bg-amber-800/30 transition-colors duration-200">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('outgoing-letters.destroy', $letter) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md hover:bg-red-200 dark:hover:bg-red-800/30 transition-colors duration-200">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </x-table-cell>
                    </tr>
                    @empty
                        <tr>
                            <x-table-cell colspan="8" class="text-center py-4">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <div
                                        class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                                        <i class="fas fa-envelope-open text-gray-400 dark:text-gray-500 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-2">Tidak ada data</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-center">
                                        Belum ada data surat keluar yang tersedia.
                                    </p>
                                </div>
                            </x-table-cell>
                        </tr>
                    @endforelse
                </tbody>
            </x-responsive-table>

            <div class="mt-4">
                {{ $letters->links() }}
            </div>
        </div>
    </x-app-layout>
