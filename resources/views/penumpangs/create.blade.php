<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penumpang</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Tambah Penumpang</h2>
        <form action="{{ route('penumpangs.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="nama" id="nama" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Arkatama" value="{{ old('nama') }}" required>
                @error('nama')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="usia" class="block text-sm font-medium text-gray-700">Usia</label>
                <input type="number" name="usia" id="usia" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="25" value="{{ old('usia') }}" required>
                @error('usia')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="tahun_lahir" class="block text-sm font-medium text-gray-700">Tahun Lahir (Optional)</label>
                <input type="number" name="tahun_lahir" id="tahun_lahir" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="1998" value="{{ old('tahun_lahir') }}">
                @error('tahun_lahir')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="kota" class="block text-sm font-medium text-gray-700">Kota</label>
                <input type="text" name="kota" id="kota" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Malang" value="{{ old('kota') }}" required>
                @error('kota')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="id_travel" class="block text-sm font-medium text-gray-700">Pilih Travel</label>
                <select name="id_travel" id="id_travel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    @foreach($travels as $travel)
                        <option value="{{ $travel->id }}" {{ old('id_travel') == $travel->id ? 'selected' : '' }}>
                            {{ $travel->tanggal_keberangkatan }} - Kuota tersisa: {{ $travel->sisa_kuota }}
                        </option>
                    @endforeach
                </select>
                @error('id_travel')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>