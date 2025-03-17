<x-app-layout>
    @section('title', 'Dashboard')

    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @if (Auth::user()->hasRole('Admin'))
                <div
                    class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengguna</p>
                                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                    {{ $data['total_users'] }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-green-500 font-medium"><i class="fas fa-arrow-up mr-1"></i>12%</span>
                            <span>dari bulan lalu</span>
                        </div>
                    </div>
                </div>

                <div
                    class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Desa</p>
                                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                    {{ $data['total_villages'] }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                <i class="fas fa-building text-indigo-600 dark:text-indigo-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-green-500 font-medium"><i
                                    class="fas fa-check-circle mr-1"></i>Terdaftar</span>
                        </div>
                    </div>
                </div>
            @endif

            <div
                class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-amber-500">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Surat Masuk</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                {{ $data['total_incoming_letters'] }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <i class="fas fa-inbox text-amber-600 dark:text-amber-400 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        <span class="text-green-500 font-medium"><i class="fas fa-arrow-up mr-1"></i>8%</span>
                        <span>dari bulan lalu</span>
                    </div>
                </div>
            </div>

            <div
                class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Surat Keluar</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                {{ $data['total_outgoing_letters'] }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <i class="fas fa-paper-plane text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        <span class="text-green-500 font-medium"><i class="fas fa-arrow-up mr-1"></i>5%</span>
                        <span>dari bulan lalu</span>
                    </div>
                </div>
            </div>

            <div
                class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-purple-500">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Disposisi Belum Dibaca</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                {{ $data['unread_dispositions_count'] }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <i class="fas fa-envelope text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        <span
                            class="text-{{ $data['unread_dispositions_count'] > 0 ? 'amber' : 'green' }}-500 font-medium">
                            <i
                                class="fas fa-{{ $data['unread_dispositions_count'] > 0 ? 'exclamation-triangle' : 'check-circle' }} mr-1"></i>
                            {{ $data['unread_dispositions_count'] > 0 ? 'Perlu perhatian' : 'Semua terbaca' }}
                        </span>
                    </div>
                </div>
            </div>

            @if (Auth::user()->hasRole('Kasi'))
                <div
                    class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-gray-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Draft Surat</p>
                                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                    {{ $data['draft_letters'] }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-file-alt text-gray-600 dark:text-gray-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-{{ $data['draft_letters'] > 0 ? 'amber' : 'green' }}-500 font-medium">
                                <i class="fas fa-{{ $data['draft_letters'] > 0 ? 'pen' : 'check-circle' }} mr-1"></i>
                                {{ $data['draft_letters'] > 0 ? 'Perlu diselesaikan' : 'Tidak ada draft' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div
                    class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-red-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Surat Ditolak</p>
                                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                    {{ $data['rejected_letters'] }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-{{ $data['rejected_letters'] > 0 ? 'red' : 'green' }}-500 font-medium">
                                <i
                                    class="fas fa-{{ $data['rejected_letters'] > 0 ? 'exclamation-circle' : 'check-circle' }} mr-1"></i>
                                {{ $data['rejected_letters'] > 0 ? 'Perlu revisi' : 'Tidak ada penolakan' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->hasRole('Sekdes'))
                <div
                    class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-orange-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Paraf</p>
                                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                    {{ $data['pending_approval'] }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                                <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-{{ $data['pending_approval'] > 0 ? 'amber' : 'green' }}-500 font-medium">
                                <i
                                    class="fas fa-{{ $data['pending_approval'] > 0 ? 'exclamation-circle' : 'check-circle' }} mr-1"></i>
                                {{ $data['pending_approval'] > 0 ? 'Perlu ditindaklanjuti' : 'Semua sudah diparaf' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->hasRole('Kades'))
                <div
                    class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu TTD</p>
                                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                    {{ $data['pending_approval'] }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <i class="fas fa-signature text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-{{ $data['pending_approval'] > 0 ? 'amber' : 'green' }}-500 font-medium">
                                <i
                                    class="fas fa-{{ $data['pending_approval'] > 0 ? 'exclamation-circle' : 'check-circle' }} mr-1"></i>
                                {{ $data['pending_approval'] > 0 ? 'Perlu ditandatangani' : 'Semua sudah ditandatangani' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->hasRole('Umum'))
                <div
                    class="card bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl overflow-hidden border-l-4 border-teal-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Nomor</p>
                                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                    {{ $data['pending_numbering'] }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center">
                                <i class="fas fa-hashtag text-teal-600 dark:text-teal-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <span
                                class="text-{{ $data['pending_numbering'] > 0 ? 'amber' : 'green' }}-500 font-medium">
                                <i
                                    class="fas fa-{{ $data['pending_numbering'] > 0 ? 'exclamation-circle' : 'check-circle' }} mr-1"></i>
                                {{ $data['pending_numbering'] > 0 ? 'Perlu penomoran' : 'Semua sudah diberi nomor' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if ($data['unread_dispositions']->count() > 0)
            <div class="mt-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Disposisi Belum Dibaca</h2>
                    <span
                        class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full text-sm font-medium">
                        {{ $data['unread_dispositions']->count() }} disposisi
                    </span>
                </div>
                <div
                    class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th>
                                        No
                                    </th>
                                    <th>
                                        Dari
                                    </th>
                                    <th>
                                        Perihal Surat
                                    </th>
                                    <th class="hidden md:table-cell">
                                        Catatan
                                    </th>
                                    <th class="hidden sm:table-cell">
                                        Tanggal
                                    </th>
                                    <th>
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['unread_dispositions'] as $index => $disposition)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $index + 1 }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                                    <span
                                                        class="text-blue-600 dark:text-blue-400 font-medium">{{ substr($disposition->fromUser->name, 0, 1) }}</span>
                                                </div>
                                                <span>{{ $disposition->fromUser->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ route('outgoing-letters.show', $disposition->outgoingLetter) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                                {{ Str::limit($disposition->outgoingLetter->subject, 40) }}
                                            </a>
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hidden md:table-cell">
                                            {{ $disposition->notes ? Str::limit($disposition->notes, 30) : '-' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 hidden sm:table-cell">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                                {{ $disposition->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                                {{ $disposition->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <div class="flex flex-col sm:flex-row gap-2">
                                                <a href="{{ route('outgoing-letters.show', $disposition->outgoingLetter) }}"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md hover:bg-blue-200 dark:hover:bg-blue-800/30 transition-colors duration-200">
                                                    <i class="fas fa-eye mr-1.5"></i>
                                                    <span>Lihat</span>
                                                </a>
                                                <form
                                                    action="{{ route('outgoing-letters.dispositions.mark-as-read', $disposition) }}"
                                                    method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center w-full px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-md hover:bg-green-200 dark:hover:bg-green-800/30 transition-colors duration-200">
                                                        <i class="fas fa-check mr-1.5"></i>
                                                        <span>Tandai Dibaca</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="mt-8">
                <div
                    class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex flex-col items-center justify-center py-6">
                        <div
                            class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-4">
                            <i class="fas fa-envelope-open text-purple-600 dark:text-purple-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-2">Tidak Ada Disposisi</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center">
                            Semua disposisi telah dibaca. Anda tidak memiliki disposisi yang perlu ditindaklanjuti saat
                            ini.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- CSS untuk scrollbar custom -->
    <style>
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.7);
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgba(75, 85, 99, 0.5);
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background-color: rgba(75, 85, 99, 0.7);
        }
    </style>
</x-app-layout>
