@extends('master')

@section('judul', 'Detail Permohonan - '.$permohonan->kode)

@section('konten')
<div class="row">
    <div class="col-lg-8 offset-lg-2" style="display: block">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="mdi mdi-view-list me-1"></i> Permohonan</h4>
                <a href="{{ url('buat-permohonan') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-fw fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg">
                        <div class="mb-3">
                            <label class="form-label">Desa</label>
                            @php
                                $desa = \DB::table('villages')->where('id', $permohonan->desa)->first()->name
                            @endphp
                            <input type="text" class="form-control" value="{{ $desa }}" readonly>
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="mb-3">
                            <label class="form-label">Dibuat Oleh</label>
                            <input type="text" class="form-control" value="{{ $permohonan->created_by }} - {{ \Carbon\Carbon::parse($permohonan->created_at)->format('d-m-Y H:i:s') }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    @php
                        $pds = DB::table('permohonan_detail')->where('permohonan_detail.deleted', 0)->where('permohonan_kode', $permohonan->kode)
                        ->select(
                            'permohonan_detail.id AS id',
                            'item.name AS item',
                        )->leftjoin('item', 'item.id', '=', 'permohonan_detail.item_id')
                        ->orderBy('name', 'ASC')->get();
                    @endphp
                    @foreach ($pds as $pd)
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="item[]" id="item{{ $pd->id }}" value="{{ $pd->id }}" checked disabled>
                        <label class="custom-control-label" for="item{{ $pd->id }}">{{ $pd->item }}</label>
                    </div>
                    @endforeach
                </div>
                @if ($permohonan->catatan)
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <input type="text" class="form-control" value="{{ $permohonan->catatan }}" readonly>
                </div>
                @endif
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
                <button type="button" class="btn w-100 btn-{{ $btn_st }}">{{ $status }}</button>
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-4" id="new" style="display: block">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="mdi mdi-plus me-1"></i> Tambah Dokumen Pendukung</h4>
            </div>
            <div class="card-body">
                <form action="{{ url('buat-permohonan/detail/'.$permohonan->kode) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama File</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">

                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" value="{{ old('file') }}">

                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if ($permohonan->status != 2 && $permohonan->status != 3)
                    <button type="submit" class="btn w-100 btn-primary">Submit</button>
                    @endif
                </form>
            </div>
        </div>
    </div> --}}
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="fe-file me-1"></i> Dokumen Pendukung</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Harap Pilih terlebih dahulu!
                </div>
                <form method="GET" action="{{ url('buat-permohonan/detail/'.$permohonan->kode) }}">
                    <div class="row">
                        <div class="col-md-5"></div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <select class="form-control select2" name="filterItem" id="filterItem">
                                    <option value="">Pilih</option>
                                    @php
                                        $pds = DB::table('permohonan_detail')->where('permohonan_kode', $permohonan->kode)->where('permohonan_detail.deleted', 0)
                                            ->leftjoin('item', 'item.id', '=', 'permohonan_detail.item_id')
                                            ->orderBy('name', 'ASC')->get();
                                    @endphp
                                    @foreach ($pds as $pd)
                                        <option value="{{ $pd->item_id }}" @selected(old('filterPD', request()->input('filterItem')) == $pd->item_id)>{{ $pd->name }}</option>
                                    @endforeach
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
                            <th>Syarat</th>
                            <th class="text-center" width="20%">Aksi</th>
                        </tr>
                        @forelse ($dokumens as $dokumen)
                        <tr>
                            <td>{{ (($dokumens->currentPage() * $dokumens->perpage()) - $dokumens->perpage()) + $loop->iteration }}</td>
                            <td>{{ $dokumen->syarat }}</td>
                            {{-- <td class="align-middle text-center text-nowrap">
                                <a href="{{ url('storage/dokumen/' . $dokumen->file) }}" target="_blank">{{ $dokumen->syarat }}</a>
                            </td> --}}
                            <td class="align-middle text-center text-nowrap no-redirect">
                                @if ($dokumen->file != '')
                                <a href="{{ url('storage/dokumen/' . $dokumen->file) }}" class="btn btn-info btn-sm mx-1" target="_blank"><i class="fa fa-fw fa-eye"></i></a>
                                @endif

                                @if ($permohonan->status != 2 && $permohonan->status != 3)
                                <button type="button" class="btn btn-primary btn-sm mx-1" data-toggle="modal" data-target="#uploadModal" data-title="{{ $dokumen->syarat }}" data-id="{{ $dokumen->id }}"><i class="fa fa-fw fa-file"></i> Upload File</button>
                                @if ($dokumen->file != '')
                                <a href="{{ url('buat-permohonan/detail/'.$dokumen->id) }}" class="btn btn-danger btn-sm mx-1 btn-hapus" data-title="File {{ $dokumen->syarat }}" data-deleted="{{ $dokumen->deleted }}"><i class="fa fa-fw fa-trash"></i> Hapus File</a>   
                                @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada dokumen pendukung.</td>
                        </tr>
                        @endforelse
                    </table>
                    {{ $dokumens->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                    <span class="text-muted">Menampilkan {{ $dokumens->firstItem()??0 }} sampai {{ $dokumens->lastItem()??0 }} dari {{ $dokumens->total() }} data.</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="uploadModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Upload File</h4>
            </div>
            <form action="{{ url('buat-permohonan/detail/'.$permohonan->kode) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label class="form-label">File</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" value="{{ old('file') }}">

                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {        
        $('#filterItem').on('change', function() {
            filterTable($(this).val());
        });

        $('#uploadModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var title = button.data('title'); // Extract info from data-* attributes
            var id = button.data('id');

            // Update the modal's content
            var modal = $(this);
            modal.find('.modal-title').text('Upload File - ' + title);
            modal.find('#id').val(id);
        });
    });

    function filterTable(val) {
        if (document.location.href.indexOf('?') > -1) {
            var url = "{{ url('buat-permohonan/detail/'.$permohonan->kode) }}?filterItem=" + val 
                + "&keyword=" + $('#keyword')
                .val();
        } else {
            var url = "{{ url('buat-permohonan/detail/'.$permohonan->kode) }}?filterItem=" + val 
                + "&keyword=" + $('#keyword')
                .val();
        }
        document.location = url;
    }
</script>

@endsection