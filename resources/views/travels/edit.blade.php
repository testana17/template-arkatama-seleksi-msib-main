<!-- resources/views/travels/edit.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Edit Travel</h2>
        <form action="{{ route('travels.update', $travel->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Travel</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $travel->nama) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('nama')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal_keberangkatan" class="block text-sm font-medium text-gray-700">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal_keberangkatan" id="tanggal_keberangkatan" value="{{ old('tanggal_keberangkatan', $travel->tanggal_keberangkatan ? $travel->tanggal_keberangkatan->format('Y-m-d') : now()->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('tanggal_keberangkatan')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="kuota" class="block text-sm font-medium text-gray-700">Kuota Maksimum</label>
                    <input type="number" name="kuota" id="kuota" value="{{ old('kuota', $travel->kuota) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('kuota')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
