<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Klasifikasi Surat</h1>
            <div class="flex gap-2">
                <a href="{{ route('letter-classifications.edit', $letterClassification) }}"
                    class="btn btn-warning flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('letter-classifications.index') }}" class="btn btn-secondary flex items-center gap-2">
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
                            <i class="fas fa-hashtag text-sm"></i> Kode
                        </h3>
                        <p
                            class="mt-1 text-lg text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                            <span
                                class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-md font-medium">
                                {{ $letterClassification->code }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-tag text-sm"></i> Nama
                        </h3>
                        <p
                            class="mt-1 text-lg text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                            {{ $letterClassification->name }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-align-left text-sm"></i> Deskripsi
                        </h3>
                        <p class="mt-1 text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                            {{ $letterClassification->description ?: 'Tidak ada deskripsi' }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1 mb-3">
                            <i class="fas fa-envelope text-sm"></i> Surat Terkait
                        </h3>
                        @if ($letterClassification->incomingLetters->count() > 0 || $letterClassification->outgoingLetters->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-1">
                                        <i class="fas fa-envelope-open-text text-blue-500"></i> Surat Masuk
                                        <span
                                            class="ml-1 px-2 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-xs">
                                            {{ $letterClassification->incomingLetters->count() }}
                                        </span>
                                    </h4>
                                    <ul class="space-y-2">
                                        @foreach ($letterClassification->incomingLetters as $letter)
                                            <li class="flex items-start gap-2">
                                                <i class="fas fa-file-alt text-gray-400 mt-1"></i>
                                                <span class="text-gray-800 dark:text-white">
                                                    <span class="font-medium">{{ $letter->reference_number }}</span> -
                                                    {{ $letter->subject }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-1">
                                        <i class="fas fa-paper-plane text-green-500"></i> Surat Keluar
                                        <span
                                            class="ml-1 px-2 py-0.5 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full text-xs">
                                            {{ $letterClassification->outgoingLetters->count() }}
                                        </span>
                                    </h4>
                                    <ul class="space-y-2">
                                        @foreach ($letterClassification->outgoingLetters as $letter)
                                            <li class="flex items-start gap-2">
                                                <i class="fas fa-file-alt text-gray-400 mt-1"></i>
                                                <span class="text-gray-800 dark:text-white">
                                                    <span class="font-medium">{{ $letter->reference_number }}</span> -
                                                    {{ $letter->subject }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                                <i class="fas fa-info-circle text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500 dark:text-gray-400">Tidak ada surat yang menggunakan klasifikasi
                                    ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
