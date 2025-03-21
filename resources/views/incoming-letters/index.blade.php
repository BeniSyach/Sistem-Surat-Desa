@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    @section('title', 'Surat Masuk')

    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Surat Masuk</h1>
            {{-- <a href="{{ route('incoming-letters.create') }}" class="btn btn-primary flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Surat
            </a> --}}
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-6 rounded-xl">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error mb-6 rounded-xl">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div
            class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <form action="{{ route('incoming-letters.index') }}" method="GET" class="flex flex-wrap gap-4">
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
                    <x-table-header>Pengirim</x-table-header>
                    <x-table-header>Perihal</x-table-header>
                    <x-table-header class="hidden md:table-cell">Tanggal Surat</x-table-header>
                    <x-table-header class="hidden md:table-cell">Tanggal Diterima</x-table-header>
                    <x-table-header class="hidden sm:table-cell">Sifat</x-table-header>
                    <x-table-header>Status</x-table-header>
                    <x-table-header>Aksi</x-table-header>
                </tr>
            </thead>
            <tbody>
                @forelse($incomingLetters as $incomingLetter)
                    <tr>
                        <x-table-cell class="font-medium">{{ $loop->iteration }}</x-table-cell>
                        <x-table-cell>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $incomingLetter->letter_number }}
                            </div>
                        </x-table-cell>
                        <x-table-cell>{{ $incomingLetter->sender }}</x-table-cell>
                        <x-table-cell>
                            @if (Str::startsWith($incomingLetter->subject, '[PERLU PERBAIKAN]'))
                                <div class="flex items-center">
                                    <span
                                        class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md text-xs font-medium mr-2">
                                        Perlu Perbaikan
                                    </span>
                                    <span
                                        class="text-sm">{{ Str::replace('[PERLU PERBAIKAN] ', '', $incomingLetter->subject) }}</span>
                                </div>
                            @else
                                <span class="text-sm">{{ $incomingLetter->subject }}</span>
                            @endif
                        </x-table-cell>
                        <x-table-cell class="hidden md:table-cell">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                {{ $incomingLetter->letter_date->format('d/m/Y') }}
                            </div>
                        </x-table-cell>
                        <x-table-cell class="hidden md:table-cell">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-gray-400 mr-2"></i>
                                {{ $incomingLetter->received_date->format('d/m/Y') }}
                            </div>
                        </x-table-cell>
                        <x-table-cell class="hidden sm:table-cell">
                            @if ($incomingLetter->confidentiality == 'biasa')
                                <span
                                    class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md text-xs font-medium">
                                    Biasa
                                </span>
                            @elseif($incomingLetter->confidentiality == 'rahasia')
                                <span
                                    class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md text-xs font-medium">
                                    Rahasia
                                </span>
                            @elseif($incomingLetter->confidentiality == 'umum')
                                <span
                                    class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-md text-xs font-medium">
                                    Umum
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md text-xs font-medium">
                                    Biasa
                                </span>
                            @endif
                        </x-table-cell>
                        <x-table-cell>
                            @switch($incomingLetter->status)
                                @case('received')
                                    <span
                                        class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md text-xs font-medium">
                                        Diterima
                                    </span>
                                @break

                                @case('pending_approval')
                                    <span
                                        class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md text-xs font-medium">
                                        Menunggu Persetujuan
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

                                @case('dispositioned')
                                    <span
                                        class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-md text-xs font-medium">
                                        Didisposisikan
                                    </span>
                                @break

                                @default
                                    <span
                                        class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-md text-xs font-medium">
                                        {{ $incomingLetter->status }}
                                    </span>
                            @endswitch
                        </x-table-cell>
                        <x-table-cell>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('incoming-letters.show', $incomingLetter) }}"
                                    class="inline-flex items-center px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md hover:bg-blue-200 dark:hover:bg-blue-800/30 transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                @if (
                                    !in_array($incomingLetter->status, ['pending_approval', 'approved', 'dispositioned']) &&
                                        (auth()->id() === $incomingLetter->created_by || auth()->id() === $incomingLetter->receiver_user_id))
                                    <a href="{{ route('incoming-letters.edit', $incomingLetter) }}"
                                        class="inline-flex items-center px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md hover:bg-amber-200 dark:hover:bg-amber-800/30 transition-colors duration-200">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    @if (auth()->id() === $incomingLetter->created_by)
                                        <form action="{{ route('incoming-letters.destroy', $incomingLetter) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md hover:bg-red-200 dark:hover:bg-red-800/30 transition-colors duration-200">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </x-table-cell>
                    </tr>
                    @empty
                        <tr>
                            <x-table-cell colspan="9" class="text-center py-4">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <div
                                        class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                                        <i class="fas fa-envelope-open text-gray-400 dark:text-gray-500 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-2">Tidak ada data</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-center">
                                        Belum ada data surat masuk yang tersedia.
                                    </p>
                                </div>
                            </x-table-cell>
                        </tr>
                    @endforelse
                </tbody>
            </x-responsive-table>

            <div class="mt-4">
                {{ $incomingLetters->links() }}
            </div>
        </div>
    </x-app-layout>
