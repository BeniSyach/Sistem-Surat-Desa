<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Kelola Tanda Tangan - {{ $user->name }}</h1>
            <a href="{{ route('users.index') }}" class="btn btn-secondary flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div
            class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                @if (session('success'))
                    <div class="alert alert-success mb-6 flex items-center gap-2">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error mb-6 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Current Signature -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-signature text-blue-500"></i> Tanda Tangan Saat Ini
                        </h3>
                        <div
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center min-h-[200px]">
                            @if ($user->signature)
                                <img src="{{ Storage::url($user->signature) }}" alt="Tanda Tangan {{ $user->name }}"
                                    class="max-w-full max-h-[180px] object-contain">
                            @else
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-signature text-4xl mb-2 opacity-30"></i>
                                    <p>Belum ada tanda tangan yang diunggah</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Signature -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-upload text-green-500"></i> Unggah Tanda Tangan
                        </h3>
                        <div
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <form action="{{ route('users.signature.upload', $user) }}" method="POST"
                                enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-1">
                                        <i class="fas fa-image text-sm"></i> Pilih file gambar (PNG/JPG, max 2MB)
                                    </label>
                                    <input type="file" name="signature" accept="image/png,image/jpeg" required
                                        class="file-input file-input-bordered w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i> Gunakan gambar dengan latar belakang
                                        transparan untuk hasil terbaik
                                    </p>
                                </div>
                                <button type="submit" class="btn btn-primary flex items-center gap-2">
                                    <i class="fas fa-upload"></i> Unggah Tanda Tangan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Draw Signature -->
                <div class="mt-8 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-pen-fancy text-purple-500"></i> Buat Tanda Tangan
                    </h3>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i> Gunakan mouse atau touch screen untuk membuat tanda
                                tangan di area di bawah ini
                            </p>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                                <canvas id="signatureCanvas" class="w-full" width="600" height="200"></canvas>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <button type="button" id="clearButton" class="btn btn-outline flex items-center gap-2">
                                <i class="fas fa-eraser"></i> Hapus
                            </button>
                            <button type="button" id="saveButton" class="btn btn-primary flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Tanda Tangan
                            </button>
                        </div>
                    </div>

                    <form id="signatureForm" action="{{ route('users.signature.draw', $user) }}" method="POST"
                        class="hidden">
                        @csrf
                        <input type="hidden" name="signature" id="signatureData">
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('signatureCanvas');
                const ctx = canvas.getContext('2d');
                let isDrawing = false;
                let lastX = 0;
                let lastY = 0;

                // Set canvas background to white
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Drawing settings
                ctx.strokeStyle = document.documentElement.classList.contains('dark') ? 'white' : 'black';
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';

                function draw(e) {
                    if (!isDrawing) return;

                    const rect = canvas.getBoundingClientRect();
                    const x = (e.clientX - rect.left) * (canvas.width / rect.width);
                    const y = (e.clientY - rect.top) * (canvas.height / rect.height);

                    ctx.beginPath();
                    ctx.moveTo(lastX, lastY);
                    ctx.lineTo(x, y);
                    ctx.stroke();

                    [lastX, lastY] = [x, y];
                }

                function startDrawing(e) {
                    isDrawing = true;
                    const rect = canvas.getBoundingClientRect();
                    lastX = (e.clientX - rect.left) * (canvas.width / rect.width);
                    lastY = (e.clientY - rect.top) * (canvas.height / rect.height);
                }

                // Mouse events
                canvas.addEventListener('mousedown', startDrawing);
                canvas.addEventListener('mousemove', draw);
                canvas.addEventListener('mouseup', () => isDrawing = false);
                canvas.addEventListener('mouseout', () => isDrawing = false);

                // Touch events for mobile
                canvas.addEventListener('touchstart', (e) => {
                    e.preventDefault();
                    const touch = e.touches[0];
                    const mouseEvent = new MouseEvent('mousedown', {
                        clientX: touch.clientX,
                        clientY: touch.clientY
                    });
                    canvas.dispatchEvent(mouseEvent);
                });

                canvas.addEventListener('touchmove', (e) => {
                    e.preventDefault();
                    const touch = e.touches[0];
                    const mouseEvent = new MouseEvent('mousemove', {
                        clientX: touch.clientX,
                        clientY: touch.clientY
                    });
                    canvas.dispatchEvent(mouseEvent);
                });

                canvas.addEventListener('touchend', () => {
                    const mouseEvent = new MouseEvent('mouseup');
                    canvas.dispatchEvent(mouseEvent);
                });

                // Clear canvas
                document.getElementById('clearButton').addEventListener('click', () => {
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.strokeStyle = document.documentElement.classList.contains('dark') ? 'white' : 'black';
                });

                // Save signature
                document.getElementById('saveButton').addEventListener('click', () => {
                    const signatureData = canvas.toDataURL('image/png');
                    document.getElementById('signatureData').value = signatureData;
                    document.getElementById('signatureForm').submit();
                });

                // Handle dark mode toggle
                const darkModeObserver = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.attributeName === 'class') {
                            ctx.strokeStyle = document.documentElement.classList.contains('dark') ?
                                'white' : 'black';
                        }
                    });
                });

                darkModeObserver.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            });
        </script>
    @endpush
</x-app-layout>
