@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Draft Pekerjaan')

@section('content')
<section x-data="{ open: false, target: null }" class="p-10 bg-white shadow-lg rounded-4xl w-full">
    <h1 class="font-bold text-3xl text-gray-800 mb-6">Draft Pekerjaan</h1>

    @if(in_array(auth()->user()->role, ['akuntan', 'pengawas', 'admin']))
    <div class="mb-6">
      <x-button variant="primary" type="button" onclick="window.location='{{ route('draft-pekerjaan.create') }}'">
        Tambah Draft Pekerjaan
      </x-button>
    </div>
    @endif

    <div class="mb-4 w-full flex justify-end">

      <input name="search" type="text" id="search" placeholder="Cari Draft Pekerjaan..."
      onkeyup="ajaxSearch()"
      class="w-fit rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"/>
    </div>

    <div class=" bg-white shadow rounded-lg lg:max-w-[1056px] ">
        <div id="scrollAbleTable" class="overflow-x-auto ">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Select All</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code Draft</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pekerjaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen Pengawasan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen Perencanaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Laproran Teknis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Termin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pajak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Pekerjaan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataDraftPekerjaan" class="bg-white divide-y divide-gray-200">
                    @foreach($draftPekerjaan as $i => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox" class="w-full mx-auto selectAllRow form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    data-row="{{ $item->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <p>{{ $item->code_draft }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <p>{{ $item->nama_pekerjaan }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="dokumen_pengawasan[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->dokumen_pengawasan == 1)>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="dokumen_perencanaan[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->dokumen_perencanaan == 1)>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="laporan_teknis[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->laporan_teknis == 1)>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="termin[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->termin == 1)>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="pajak[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->pajak == 1)>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="status_pekerjaan[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->status_pekerjaan == 1)
                                    @if(auth()->user()->role !== 'pengawas') @disabled(true) @endif>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">
                                <a href="{{ route('draft-pekerjaan.show',['draft_pekerjaan' => $item->id]) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                                @if(in_array(auth()->user()->role, ['akuntan', 'pengawas']))
                                    <a href="{{ route('draft-pekerjaan.edit', $item->id) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                                    <button @click="open = true; target = '{{ $item->id }}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">
                                        Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <div class="mt-4 flex justify-end">
            {{ $draftPekerjaan->links() }}
        </div>

    </div>

    <!-- Modal Konfirmasi Hapus -->
    <template x-if="open">
        <div class="fixed inset-0 bg-gray-600/25  flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-xl font-semibold mb-4 text-red-600">Konfirmasi Hapus</h2>
                <p class="mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
                <div class="flex justify-end space-x-2">
                    <button @click="open = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <form x-bind:action="'/draft-pekerjaan/' + target" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</section>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

document.addEventListener("DOMContentLoaded", function () {
    let searchTimer;

    window.ajaxSearch = function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const keyword = document.getElementById('search').value;
            const url = "{{ route('draft-pekerjaan.search') }}";

            fetch(`${url}?keyword=${encodeURIComponent(keyword)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('dataDraftPekerjaan');
                tbody.innerHTML = "";

                if (data.length > 0) {
                    data.forEach((item, index) => {
                        const tr = document.createElement('tr');
                        tr.classList.add('hover:bg-gray-50');

                        let aksiButtons = `
                            <a href="/draft-pekerjaan/${item.id}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                        `;

                        @if(in_array(auth()->user()->role, ['akuntan', 'pengawas']))
                        aksiButtons += `
                            <a href="/draft-pekerjaan/${item.id}/edit" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                            <button onclick="confirmDelete(${item.id})" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">
                                Hapus
                            </button>
                        `;
                        @endif

                        tr.innerHTML = `
                            <td class="px-6 py-4 text-sm">
                                <input type="checkbox" class="selectAllRow form-checkbox h-5 text-primary border-gray-300 rounded" data-row="${item.id}">
                            </td>
                            <td class="px-6 py-4 text-sm">${index + 1}</td>
                            <td class="px-6 py-4 text-sm">${item.code_draft}</td>
                            <td class="px-6 py-4 text-sm">${item.nama_pekerjaan}</td>
                            <td class="px-6 py-4 text-sm">${item.instansi}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">${aksiButtons}</td>
                        `;

                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-sm text-gray-500 py-4">Data tidak ditemukan</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }, 500);
    };
});
</script>


<script>

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".rowCheckbox").forEach(function (checkbox) {
            checkbox.addEventListener("change", function () {
                const id = this.dataset.row;
                const name = this.name.replace('[]', ''); // ambil nama kolomnya
                const value = this.checked ? 1 : 0;

                fetch(`/draft-pekerjaan/update-checkbox/${id}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ field: name, value: value })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(`Berhasil update ${name} untuk ID ${id}`);
                    } else {
                        alert("Gagal mengupdate data!");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan!");
                });
            });
        });
    });
</script>


@endsection
