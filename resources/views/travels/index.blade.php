<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-8">
        <h2 class="text-3xl font-bold mb-6 text-gray-900">Daftar Travel</h2>

        <!-- Button Wrapper -->
        <div class="mb-6 flex justify-end gap-4">
            <a href="{{ route('travels.create') }}" class="inline-block bg-blue-700 text-white py-3 px-6 rounded-lg shadow-lg hover:bg-blue-800 transition duration-300">
                Tambah Travel
            </a>
            <a href="{{ route('penumpangs.index') }}" class="inline-block bg-green-700 text-white py-3 px-6 rounded-lg shadow-lg hover:bg-green-800 transition duration-300">
                Daftar Penumpang
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-700 text-white p-4 rounded-lg mb-6 shadow-md">
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-4 px-6 border-b border-gray-400 text-left text-sm font-medium">No</th>
                        <th class="py-4 px-6 border-b border-gray-400 text-left text-sm font-medium">Nama</th>
                        <th class="py-4 px-6 border-b border-gray-400 text-left text-sm font-medium">Tanggal Keberangkatan</th>
                        <th class="py-4 px-6 border-b border-gray-400 text-left text-sm font-medium">Kuota</th>
                        <th class="py-4 px-6 border-b border-gray-400 text-left text-sm font-medium">Sisa Kuota</th>
                        <th class="py-4 px-6 border-b border-gray-400 text-left text-sm font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($travels as $index => $travel)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 border-b border-gray-200 text-sm">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 border-b border-gray-200 text-sm">{{ $travel->nama }}</td>
                            <td class="py-4 px-6 border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($travel->tanggal_keberangkatan)->format('d M Y') }}</td>
                            <td class="py-4 px-6 border-b border-gray-200 text-sm">{{ $travel->kuota }}</td>
                            <td class="py-4 px-6 border-b border-gray-200 text-sm">{{ $travel->sisa_kuota }}</td>
                            <td class="py-4 px-6 border-b border-gray-200 text-sm flex space-x-4">
                                <a href="{{ route('travels.edit', $travel->id) }}" class="text-blue-700 hover:text-blue-800 transition duration-300">Edit</a>
                                <form action="{{ route('travels.destroy', $travel->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
