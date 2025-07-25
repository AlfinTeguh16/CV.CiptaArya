@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Arus Kas')

@section('content')
<section x-data="{ open: false, target: null }" class="p-10 bg-white shadow-lg rounded-4xl w-full">
    <h1 class="font-bold text-3xl text-gray-800 mb-6">Data Arus Kas</h1>

    @if(auth()->user()->role === 'akuntan')
    <div class="mb-6">
        <x-button variant="primary" type="button" onclick="window.location='{{ route('arus-kas.create') }}'">
            Tambah Data Arus Kas
        </x-button>
    </div>
    @endif

    <div class="mb-4 w-full flex justify-end">
        <input name="search" type="text" id="search" placeholder="Cari Arus Kas..."
               onkeyup="ajaxSearch()"
               class="w-fit rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" />
    </div>

    <div class="bg-white shadow rounded-lg lg:max-w-[1056px]">
        <div id="scrollAbleTable" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataArusKas" class="bg-white divide-y divide-gray-200">
                    @if($arusKas->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center text-sm text-gray-500 py-4">Data Arus Kas tidak ditemukan.</td>
                        </tr>
                    @else
                        @foreach($arusKas as $i => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->tanggal->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->keterangan ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ $item->jenis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ $item->kategori }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($item->jumlah, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">
                                <a href="{{ route('arus-kas.show', $item) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                                @if(auth()->user()->role === 'akuntan')
                                    <a href="{{ route('arus-kas.edit', $item) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                                    <button @click="open = true; target = '{{ $item->id }}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">
                                        Hapus
                                    </button>
                                @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-end">
            {{ $arusKas->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <template x-if="open">
        <div class="fixed inset-0 bg-gray-600/25 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-xl font-semibold mb-4 text-red-600">Konfirmasi Hapus</h2>
                <p class="mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
                <div class="flex justify-end space-x-2">
                    <button @click="open = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <form x-bind:action="'/arus-kas/' + target" method="POST">
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
  document.addEventListener("DOMContentLoaded", function () {
      let searchTimer;

      window.ajaxSearch = function () {
          clearTimeout(searchTimer);
          searchTimer = setTimeout(() => {
              const keyword = document.getElementById('search').value;
              const url = "{{ route('arus-kas.search') }}";
              const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

              fetch(`${url}?keyword=${encodeURIComponent(keyword)}`, {
                  headers: {
                      'Accept': 'application/json',
                      'X-Requested-With': 'XMLHttpRequest',
                      'X-CSRF-TOKEN': csrfToken
                  }
              })
              .then(response => response.json())
              .then(data => {
                  const tbody = document.getElementById('dataArusKas');
                  tbody.innerHTML = "";

                  if (data.length > 0) {
                      data.forEach((item, index) => {
                          const tr = document.createElement('tr');
                          tr.classList.add('hover:bg-gray-50');

                          let aksiButtons = `
                              <a href="{{ route('arus-kas.show', $arusKas) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                              <a href="/arus-kas/${item.id}/edit" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                              <button onclick="openDeleteModal(${item.id})" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">
                                  Hapus
                              </button>
                          `;

                          tr.innerHTML = `
                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${index + 1}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm">${formatDate(item.tanggal)}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm">${item.keterangan ?? '-'}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">${item.jenis}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">${item.kategori}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm">Rp ${formatRupiah(item.jumlah)}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">${aksiButtons}</td>
                          `;

                          tbody.appendChild(tr);
                      });
                  } else {
                      tbody.innerHTML = `<tr><td colspan="7" class="text-center text-sm text-gray-500 py-4">Data tidak ditemukan</td></tr>`;
                  }
              })
              .catch(error => console.error('Error:', error));
          }, 500);
      };

      function formatDate(dateStr) {
          const date = new Date(dateStr);
          return date.toLocaleDateString('id-ID');
      }

      function formatRupiah(value) {
          return parseFloat(value).toLocaleString('id-ID', {
              minimumFractionDigits: 2,
              maximumFractionDigits: 2
          });
      }
  });
</script>


@endsection
