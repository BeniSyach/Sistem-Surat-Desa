<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Pemrosesan Surat Keluar (Umum Desa)</h1>
        </div>

        @php
            $kades = App\Models\User::whereHas('role', function ($query) {
                $query->where('name', 'Menandatangani Surat');
            })
                ->where('village_id', auth()->user()->village_id)
                ->where('is_active', true)
                ->first();
        @endphp

        @if (!$kades || !$kades->signature)
            <div class="alert alert-warning mb-6">
                <div class="flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <label>
                        <span class="font-bold">Perhatian!</span> Kepala Desa belum memiliki tanda tangan digital.
                        <a href="{{ route('users.signature', $kades) }}" class="link link-primary">Klik di sini untuk
                            menambahkan tanda tangan Kepala Desa</a>.
                    </label>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b">
                <form action="{{ route('outgoing-letters.umum-processing') }}" method="GET"
                    class="flex flex-wrap gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tanggal Mulai</span>
                        </label>
                        <input type="date" name="start_date" class="input input-bordered"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tanggal Akhir</span>
                        </label>
                        <input type="date" name="end_date" class="input input-bordered"
                            value="{{ request('end_date') }}">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Cari</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="search" class="input input-bordered"
                                placeholder="Cari surat..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Surat</th>
                            <th>Perihal</th>
                            <th>Tanggal Surat</th>
                            <th>Desa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($letters as $letter)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $letter->letter_number }}</td>
                                <td>{{ $letter->subject }}</td>
                                <td>{{ $letter->letter_date->format('d/m/Y') }}</td>
                                <td>{{ $letter->village->name }}</td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'badge-warning' => $letter->status === 'pending_process',
                                        'badge-success' => $letter->status === 'processed',
                                    ])>
                                        @if ($letter->status === 'pending_process')
                                            Menunggu Proses
                                        @elseif($letter->status === 'processed')
                                            Selesai Diproses
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('outgoing-letters.show', $letter) }}"
                                            class="btn btn-info btn-sm">
                                            Detail
                                        </a>
                                        @if ($letter->status === 'pending_process')
                                            <button type="button" onclick="openProcessModal('{{ $letter->id }}')"
                                                class="btn btn-success btn-sm">
                                                Proses
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada surat yang perlu diproses.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $letters->links() }}
            </div>
        </div>

        <!-- Process Modal -->
        <div id="processModal" class="modal">
            <div class="modal-box relative">
                <button type="button" onclick="closeProcessModal()"
                    class="btn btn-sm btn-circle absolute right-2 top-2">✕</button>
                <h3 class="font-bold text-lg mb-6">Proses Surat Keluar</h3>
                <form id="processForm" method="POST" onsubmit="return handleSubmit(event)">
                    @csrf
                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text">Nomor Surat <span class="text-red-500">*</span></span>
                            <span class="label-text-alt text-gray-500">Contoh: 001/DS/2024</span>
                        </label>
                        <input type="text" name="letter_number" class="input input-bordered" required
                            pattern="[0-9]{3}/[A-Z]{2,}/[0-9]{4}" title="Format: 001/DS/2024" placeholder="001/DS/2024">
                        <label class="label">
                            <span class="label-text-alt text-gray-500">Format: [Nomor Urut]/[Kode Surat]/[Tahun]</span>
                        </label>
                    </div>
                    <div class="modal-action">
                        <button type="submit" id="submitBtn" class="btn btn-success gap-2">
                            <span>Proses Surat</span>
                            <span class="loading loading-spinner hidden"></span>
                        </button>
                        <button type="button" onclick="closeProcessModal()" class="btn">Batal</button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button onclick="closeProcessModal()">close</button>
            </form>
        </div>

        <script>
            function openProcessModal(letterId) {
                const modal = document.getElementById('processModal');
                const form = document.getElementById('processForm');
                form.action = `/umum/processing/${letterId}/process`;
                modal.classList.add('modal-open');

                // Focus nomor surat input
                setTimeout(() => {
                    form.querySelector('input[name="letter_number"]').focus();
                }, 100);
            }

            function closeProcessModal() {
                const modal = document.getElementById('processModal');
                const form = document.getElementById('processForm');
                modal.classList.remove('modal-open');
                form.reset();
            }

            function handleSubmit(event) {
                const submitBtn = document.getElementById('submitBtn');
                const spinner = submitBtn.querySelector('.loading');
                const text = submitBtn.querySelector('span:not(.loading)');

                // Show loading state
                spinner.classList.remove('hidden');
                text.textContent = 'Memproses...';
                submitBtn.disabled = true;

                return true;
            }

            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                const modal = document.getElementById('processModal');
                if (event.target.classList.contains('modal-backdrop')) {
                    closeProcessModal();
                }
            });

            // Handle escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeProcessModal();
                }
            });
        </script>
    </div>
</x-app-layout>
