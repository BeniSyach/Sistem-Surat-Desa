<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Persetujuan Surat Keluar (Kades)</h1>
        </div>

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
                <form action="{{ route('outgoing-letters.kades-approval') }}" method="GET" class="flex flex-wrap gap-4">
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
                                        'badge-warning' => $letter->status === 'pending_kades',
                                        'badge-success' => $letter->status === 'approved_kades',
                                        'badge-error' => $letter->status === 'rejected_kades',
                                    ])>
                                        @if ($letter->status === 'pending_kades')
                                            Menunggu Persetujuan
                                        @elseif($letter->status === 'approved_kades')
                                            Disetujui
                                        @elseif($letter->status === 'rejected_kades')
                                            Ditolak
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('outgoing-letters.show', $letter) }}"
                                            class="btn btn-info btn-sm">
                                            Detail
                                        </a>
                                        @if ($letter->status === 'pending_kades')
                                            <form action="{{ route('outgoing-letters.kades-approve', $letter) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui surat ini?')">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('outgoing-letters.kades-reject', $letter) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-error btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menolak surat ini?')">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada surat yang perlu disetujui.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $letters->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
