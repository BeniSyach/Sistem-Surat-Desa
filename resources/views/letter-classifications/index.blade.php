<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Klasifikasi Surat</h1>
            <a href="{{ route('letter-classifications.create') }}" class="btn btn-primary flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Klasifikasi
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-error mb-4 rounded-xl">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <x-responsive-table>
            <thead>
                <tr>
                    <x-table-header class="w-12">No</x-table-header>
                    <x-table-header>Kode</x-table-header>
                    <x-table-header>Nama</x-table-header>
                    <x-table-header class="hidden md:table-cell">Deskripsi</x-table-header>
                    <x-table-header>Aksi</x-table-header>
                </tr>
            </thead>
            <tbody>
                @forelse ($letterClassifications as $classification)
                    <tr>
                        <x-table-cell class="font-medium">{{ $loop->iteration }}</x-table-cell>
                        <x-table-cell>
                            <span
                                class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md text-xs font-medium">
                                {{ $classification->code }}
                            </span>
                        </x-table-cell>
                        <x-table-cell>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $classification->name }}
                            </div>
                        </x-table-cell>
                        <x-table-cell
                            class="hidden md:table-cell">{{ $classification->description ?? '-' }}</x-table-cell>
                        <x-table-cell>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('letter-classifications.show', $classification) }}"
                                    class="inline-flex items-center px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md hover:bg-blue-200 dark:hover:bg-blue-800/30 transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                <a href="{{ route('letter-classifications.edit', $classification) }}"
                                    class="inline-flex items-center px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md hover:bg-amber-200 dark:hover:bg-amber-800/30 transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('letter-classifications.destroy', $classification) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md hover:bg-red-200 dark:hover:bg-red-800/30 transition-colors duration-200"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </x-table-cell>
                    </tr>
                @empty
                    <tr>
                        <x-table-cell colspan="5" class="text-center py-4">
                            <div class="flex flex-col items-center justify-center py-6">
                                <div
                                    class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                                    <i class="fas fa-folder-open text-gray-400 dark:text-gray-500 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-2">Tidak ada data</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-center">
                                    Belum ada data klasifikasi surat yang tersedia.
                                </p>
                            </div>
                        </x-table-cell>
                    </tr>
                @endforelse
            </tbody>
        </x-responsive-table>

        <div class="mt-4">
            {{ $letterClassifications->links() }}
        </div>
    </div>
</x-app-layout>
