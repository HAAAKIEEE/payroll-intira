<x-layouts.app.sidebar>
    <flux:main class="p-6">
        <div class="max-w-6xl mx-auto space-y-6">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold flex items-center gap-3 text-zinc-900 dark:text-zinc-100">
                    Import Data Karyawan
                </h1>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                    Upload file Excel untuk import data karyawan secara batch
                </p>
            </div>

            <!-- Success -->
            @if(session('success'))
                <div class="p-4 rounded-lg bg-green-600 text-white">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error -->
            @if(session('error'))
                <div class="p-4 rounded-lg bg-red-600 text-white">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
            <div class="p-4 rounded-lg bg-red-100 border-l-4 border-red-500">
                <h3 class="font-semibold text-red-800">Terjadi kesalahan</h3>
                <ul class="text-sm text-red-700 mt-2">
                    @foreach($errors->all() as $error)
                    <li class="flex gap-2">
                        <span>•</span> {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Import Errors Table -->
            @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="p-4 rounded-lg bg-amber-50 border-l-4 border-amber-500">
                <h3 class="font-semibold text-amber-900 mb-3">
                    Ada {{ count(session('import_errors')) }} baris yang bermasalah
                </h3>

                <table class="w-full text-sm border border-amber-300 rounded-lg bg-white">
                    <thead class="bg-amber-100 font-semibold">
                        <tr>
                            <th class="p-2 border">Baris</th>
                            <th class="p-2 border">Alasan</th>
                            <th class="p-2 border">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('import_errors') as $err)
                        <tr>
                            <td class="border p-2 font-semibold">{{ $err['row'] }}</td>
                            <td class="border p-2 text-red-600">{{ $err['reason'] }}</td>
                            <td class="border p-2">
                                @if(isset($err['data']))
                                <details>
                                    <summary class="cursor-pointer text-blue-600 underline">Lihat Data</summary>
                                    <pre class="text-xs bg-gray-200 p-2 rounded mt-2">{{ json_encode($err['data'], JSON_PRETTY_PRINT) }}</pre>
                                </details>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Upload Section -->
            <div class="border rounded-lg p-6 shadow-sm dark:bg-zinc-900 dark:border-zinc-700">

                <form action="{{ route('master-data.import-employee-user.import') }}" 
                      method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf

                    <label class="font-semibold mb-3 block">Upload File Excel</label>

                    <div id="dropZone"
                         class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer">

                        <input type="file" name="file" id="fileInput"
                               accept=".xlsx,.xls" required class="hidden">

                        <p class="text-zinc-600 dark:text-zinc-300">
                            Klik / Drag file Excel ke sini
                        </p>
                        <p class="text-xs text-zinc-500">
                            Format: XLSX / XLS — Max: 10 MB
                        </p>

                        <!-- File preview -->
                        <div id="filePreview" class="hidden mt-4">
                            <div class="flex items-center gap-3 bg-zinc-100 p-3 rounded">
                                <span class="font-semibold" id="fileName"></span>
                                <span class="text-xs" id="fileSize"></span>
                                <button type="button" onclick="clearFile()" 
                                        class="text-red-600 ml-auto">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" id="submitBtn"
                                class="bg-blue-600 text-white px-5 py-3 rounded w-full">
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
        const importForm = document.getElementById('importForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const dropZone = document.getElementById('dropZone');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                displayFile(file);
            }
        });

        function displayFile(file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            filePreview.classList.remove('hidden');
        }

        function clearFile() {
            fileInput.value = '';
            filePreview.classList.add('hidden');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        importForm.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            btnText.innerHTML = 'Sedang Import...';
        });

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-950/20');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-950/20');
        }

        dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
            
    if (files.length > 0) {
        fileInput.files = files;
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }
}

// ▶ FIX: Gunakan click untuk trigger input
dropZone.addEventListener('click', () => fileInput.click());

    </script>
</x-layouts.app.sidebar>
