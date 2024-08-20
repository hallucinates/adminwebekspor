@extends('master')

@section('judul', 'Laporan Rekap Per Item')

@section('konten')
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="fe-activity me-1"></i> Laporan Rekap Per Item</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('buat-permohonan') }}">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="mb-3">
                                <input class="form-control" type="text" name="tgl" id="tgl">
                            </div>
                        </div>
                        <div class="col-md-4">
                            
                        </div>
                        {{-- <div class="col-md-3">
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..." name="keyword" id="keyword" autocomplete="off" value="{{ request()->input('keyword') }}">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-search"></i></button>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle small-table">
                        <tr>
                            <th>No.</th>
                            <th>Item</th>
                            <th class="text-center">Jumlah</th>
                        </tr>
                        @php
                            $total = 0;
                        @endphp
                        @forelse ($items as $item)
                        <tr class="main-row">
                            <td>{{ (($items->currentPage() * $items->perpage()) - $items->perpage()) + $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="align-middle text-center text-nowrap">
                                {{ $item->jumlah }}
                            </td>
                        </tr>
                        <tr class="hidden-row" style="display: none;">
                            <td colspan="3">
                                <!-- Informasi tambahan atau kolom tersembunyi -->
                                <p class="mb-0">
                                    
                                </p>
                                <!-- Anda bisa tambahkan konten lain di sini -->
                            </td>
                        </tr>
                        @php
                            $total += $item->jumlah; // Tambahkan jumlah ke total
                        @endphp
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada laporan rekap per item.</td>
                        </tr>
                        @endforelse
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-center">{{ $total }}</td>
                        </tr>
                    </table>
                    {{ $items->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                    <span class="text-muted">Menampilkan {{ $items->firstItem()??0 }} sampai {{ $items->lastItem()??0 }} dari {{ $items->total() }} data.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const tgl = urlParams.get('tgl');
    const tgl2 = urlParams.get('tgl2');

    // Tanggal awal dan akhir bulan saat ini
    const today = new Date();
    const startDate = tgl ? moment(tgl, 'YYYY-MM-DD') : moment(new Date(today.getFullYear(), today.getMonth(), 1));
    const endDate = tgl2 ? moment(tgl2, 'YYYY-MM-DD') : moment(new Date(today.getFullYear(), today.getMonth() + 1, 0));

    $("#tgl").daterangepicker({
        buttonClasses:["btn","btn-sm"],
        applyClass:"btn-success",
        cancelClass:"btn-light",

        startDate: startDate,
        endDate: endDate
    }, function(start, end) {
        // Trigger saat klik Apply
        const tgl = start.format('YYYY-MM-DD'); // Format tgl1 sesuai kebutuhan
        const tgl2 = end.format('YYYY-MM-DD');   // Format tgl2 sesuai kebutuhan
        
        window.location.href = "{{ url('laporan/rekap-per-item') }}?tgl=" + tgl + "&tgl2=" +tgl2;
    });

    $(document).ready(function(){
        $('.main-row').click(function(){
            $(this).next('.hidden-row').toggle();
        });
    });
</script>
@endsection