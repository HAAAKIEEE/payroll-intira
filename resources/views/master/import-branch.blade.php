<x-layouts.app.sidebar>

    <flux:main class="p-6">
        <div class="max-w-6xl mx-auto space-y-6">

            <!-- Header -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    <i class="fas fa-file-excel text-green-600"></i>
                    Import Master Data
                </h1>
                <p class="text-gray-600 dark:text-zinc-400 mt-2">
                    Upload file Excel untuk import data Cabang dan Karyawan sekaligus
                </p>
            </div>

            @if(session('import_stats') && isset(session('import_stats')['errors']))
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-amber-900">Detail Baris Yang Dilewati:</h4>
                <table class="w-full text-sm mt-3 border border-amber-300 rounded-lg bg-white">
                    <tr class="bg-amber-100 font-semibold">
                        <th class="p-2 border">Row</th>
                        <th class="p-2 border">Reason</th>
                        <th class="p-2 border">Data</th>
                    </tr>
                    @foreach(session('import_stats')['errors'] as $err)
                    <tr>
                        <td class="border p-2">{{ $err['row'] }}</td>
                        <td class="border p-2 text-red-600">{{ $err['reason'] }}</td>
                        <td class="border p-2 text-xs">{{ json_encode($err['data']) }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @endif

            <!-- Alerts -->
            @if(session('success'))
            <div class="bg-green-600 text-white p-4 rounded-lg mb-4">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="bg-red-600 text-white p-4 rounded-lg mb-4">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 p-4 border border-red-500 rounded-lg mb-4">
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Petunjuk Import -->
            <div class="bg-blue-50 dark:bg-blue-950/20 rounded-lg p-6 border border-blue-200">
                <h2 class="text-blue-900 font-semibold mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Petunjuk Import
                </h2>
                <ol class="list-decimal list-inside space-y-1 text-blue-800">
                    <li>Download template Excel terlebih dahulu</li>
                    <li>Sheet wajib: MASTER CABANG & MASTER KARYAWAN</li>
                    <li>Isi data sesuai format</li>
                    <li>Upload file dan klik Import</li>
                    <li>Tunggu sampai selesai</li>
                </ol>
            </div>

            <!-- Download Template -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                    <i class="fas fa-download text-blue-600"></i>
                    Download Template
                </h2>
                <a href="{{ route('master-data.download-template') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg">
                    <i class="fas fa-file-download"></i>
                    Download Template Excel
                </a>
            </div>

            <!-- Upload Form -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6">

                <form action="{{ route('master-data.import') }}"
                      method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf

                    <div id="dropZone"
                        class="border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition duration-200">

                        <input type="file" name="file" id="fileInput"
                               accept=".xlsx,.xls" required class="hidden">

                        <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 mb-3"></i>

                        <p class="text-gray-700 dark:text-zinc-300">
                            <span class="text-blue-600 font-semibold">Klik untuk upload</span> atau drag & drop
                        </p>

                        <p class="text-xs text-gray-500 mt-1">Max 10 MB</p>

                        <div id="filePreview" class="hidden mt-4">
                            <div class="bg-gray-100 dark:bg-zinc-800 p-3 rounded-lg flex items-center gap-3">
                                <i class="fas fa-file-excel text-green-600 text-3xl"></i>
                                <div>
                                    <p id="fileName" class="font-semibold text-zinc-900 dark:text-zinc-100"></p>
                                    <p id="fileSize" class="text-xs text-gray-500"></p>
                                </div>
                                <button type="button" class="text-red-500 text-sm ml-auto" onclick="clearFile()">Hapus</button>
                            </div>
                        </div>

                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" id="submitBtn"
                                class="bg-green-600 text-white px-5 py-3 rounded w-full flex justify-center gap-2">
                            <i class="fas fa-upload"></i>
                            <span id="btnText">Import Data</span>
                        </button>

                        <a href="{{ url()->previous() }}"
                           class="px-5 py-3 rounded border w-40 text-center">
                           Kembali
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </flux:main>

    <script>
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const dropZone = document.getElementById('dropZone');

        fileInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;
                fileSize.textContent = (file.size/1024/1024).toFixed(2) + ' MB';
                filePreview.classList.remove('hidden');
            }
        });

        function clearFile() {
            fileInput.value = '';
            filePreview.classList.add('hidden');
        }

        ['dragenter','dragover','dragleave','drop'].forEach(evt =>
            dropZone.addEventListener(evt, e => {
                e.preventDefault();
                dropZone.classList.toggle('border-blue-500', evt !== 'dragleave');
                dropZone.classList.toggle('bg-blue-50', evt !== 'dragleave');
            })
        );

        dropZone.addEventListener('drop', e => {
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change', { bubbles: true }));
        });

        dropZone.addEventListener('click', () => fileInput.click());
    </script>

</x-layouts.app.sidebar>
