@extends('master')

@section('judul', 'Verifikasi')

@section('konten')
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="fe-check me-1"></i> Verifikasi</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('buat-permohonan') }}">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <select class="form-control select2" name="filterStatus" id="filterStatus">
                                    <option value="">Semua</option>
                                    <option value="0" @selected(old('filterStatus', request()->input('filterStatus')) == '0')>Belum Diperiksa</option>
                                    <option value="1" @selected(old('filterStatus', request()->input('filterStatus')) == '1')>Pending</option>
                                    <option value="2" @selected(old('filterStatus', request()->input('filterStatus')) == '2')>Ditolak</option>
                                    <option value="3" @selected(old('filterStatus', request()->input('filterStatus')) == '3')>Diterima</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..." name="keyword" id="keyword" autocomplete="off" value="{{ request()->input('keyword') }}">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle small-table">
                        <tr>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>Desa</th>
                            <th class="text-center">Status</th>
                            <th>Dibuat Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        @forelse ($permohonans as $permohonan)
                        @php
                            if ($permohonan->status == 0) {
                                $btn_st = 'primary';
                                $status = 'Belum Diperiksa';
                            } else if ($permohonan->status == 1) {
                                $btn_st = 'warning';
                                $status = 'Pending';
                            } else if ($permohonan->status == 2) {
                                $btn_st = 'danger';
                                $status = 'Ditolak';
                            } else if ($permohonan->status == 3) {
                                $btn_st = 'success';
                                $status = 'Diterima';
                            }
                        @endphp
                        <tr class="main-row">
                            <td>{{ (($permohonans->currentPage() * $permohonans->perpage()) - $permohonans->perpage()) + $loop->iteration }}</td>
                            <td><a href="{{ url('verifikasi/detail/'.$permohonan->kode) }}">{{ $permohonan->kode }}</a></td>
                            <td>{{ $permohonan->desa }}</td>
                            <td class="align-middle text-center text-nowrap">
                                <span class="btn btn-{{ $btn_st }} btn-sm">{{ $status }}</span>
                            </td>
                            <td>{{ $permohonan->created_by }}</td>
                            <td class="align-middle text-center text-nowrap no-redirect">
                                <a href="{{ url('buat-permohonan/'.$permohonan->kode) }}" class="btn btn-danger btn-sm mx-1 btn-hapus" data-title="{{ $permohonan->kode }}" data-deleted="{{ $permohonan->deleted }}"><i class="fa fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr class="hidden-row" style="display: none;">
                            <td colspan="6">
                                <!-- Informasi tambahan atau kolom tersembunyi -->
                                <p class="mb-0">
                                    <strong>Catatan:</strong> {{ $permohonan->catatan }}<br>
                                    <strong>Tgl Dibuat:</strong> {{ \Carbon\Carbon::parse($permohonan->created_at)->format('d-m-Y H:i:s') }}
                                </p>
                                <!-- Anda bisa tambahkan konten lain di sini -->
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada verifikasi.</td>
                        </tr>
                        @endforelse
                    </table>
                    {{ $permohonans->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                    <span class="text-muted">Menampilkan {{ $permohonans->firstItem()??0 }} sampai {{ $permohonans->lastItem()??0 }} dari {{ $permohonans->total() }} data.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.main-row').click(function(){
            $(this).next('.hidden-row').toggle();
        });

        $('#filterStatus').on('change', function() {
            filterTable($(this).val());
        });
    });

    function filterTable(val) {
        if (document.location.href.indexOf('?') > -1) {
            var url = "{{ url('verifikasi') }}?filterStatus=" + val 
                + "&keyword=" + $('#keyword')
                .val();
        } else {
            var url = "{{ url('verifikasi') }}?filterStatus=" + val 
                + "&keyword=" + $('#keyword')
                .val();
        }
        document.location = url;
    }
</script>
@endsection