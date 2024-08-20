@extends('master')

@section('judul', 'Master Template')

@section('konten')
<div class="row">
    <div class="col-lg-4" id="new">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="mdi mdi-plus me-1"></i> Tambah</h4>
                <a href="javascript:void(0);" onclick="toggleCard('#new')" class="btn btn-sm btn-danger"><i class="fas fa-fw fa-times"></i></a>
            </div>
            <div class="card-body">
                <form action="{{ url('master/template') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-control select2 @error('kategori') is-invalid @enderror" name="kategori" style="width: 100%">
                            <option value="">Pilih</option>
                            @php
                                $kategoris = DB::table('kategori')->where('deleted', 0)
                                    ->orderBy('name', 'ASC')->get();
                            @endphp
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" @selected(old('kategori') == $kategori->id)>{{ $kategori->name }}</option>
                            @endforeach
                        </select>

                        @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">

                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar" value="{{ old('gambar') }}">

                        @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp.</span>
                            </div>

                            <input type="number" class="form-control @error('harga') is-invalid @enderror" name="harga" value="{{ old('harga') }}" min="1">

                            @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="fe-settings me-1"></i> Master Template</h4>
                <div class="row">
                    <div class="col-lg">        
                        <a href="javascript:void(0);" onclick="toggleCard('#new')" class="btn btn-sm btn-info">
                            <i class="fas fa-fw fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('master/template') }}">
                    <div class="row">
                        <div class="col-md-7"></div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <select class="form-control select2" name="filterKategori" id="filterKategori" style="width: 100%">
                                    <option value="">Pilih</option>
                                    @php
                                        $kategoris = DB::table('kategori')->where('deleted', 0)
                                            ->orderBy('name', 'ASC')->get();
                                    @endphp
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" @selected(old('filterKategori', request()->input('filterKategori')) == $kategori->id)>{{ $kategori->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..." name="keyword" id="keyword" autocomplete="off" value="{{ request()->input('keyword') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <tr>
                            <th>No.</th>
                            <th>Kategori</th>
                            <th>Nama</th>
                            <th class="text-center">Gambar</th>
                            <th>Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        @forelse ($templates as $template)
                        <tr>
                            <td>{{ (($templates->currentPage() * $templates->perpage()) - $templates->perpage()) + $loop->iteration }}</td>
                            <td>{{ $template->kategori }}</td>
                            <td>{{ $template->name }}</td>
                            <td class="align-middle text-center text-nowrap">
                                <img src="{{ asset($template->gambar) }}" alt="" style="width: 50px; height: auto; cursor: pointer;" data-toggle="modal" data-target="#imageModal{{ $template->id }}">
                            </td>
                            <td>{{ \App\Helper::currency($template->harga) }}</td>
                            <td class="align-middle text-center text-nowrap">
                                <a href="{{ url('master/template/'.$template->id.'/edit') }}" class="btn btn-success btn-sm mx-1"><i class="fa fa-fw fa-edit"></i></a>
                                <a href="{{ url('master/template/'.$template->id) }}" class="btn btn-danger btn-sm mx-1 btn-hapus" data-title="{{ $template->name }}" data-deleted="{{ $template->deleted }}"><i class="fa fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                        
                        <div class="modal fade" id="imageModal{{ $template->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><b class="text-danger">Gambar : </b> {{ $template->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset($template->gambar) }}" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada template.</td>
                        </tr>
                        @endforelse
                    </table>
                    {{ $templates->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                    <span class="text-muted">Menampilkan {{ $templates->firstItem()??0 }} sampai {{ $templates->lastItem()??0 }} dari {{ $templates->total() }} data.</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        @if (!$errors->any())
        toggleCard('#new');
        @endif

        $('#filterKategori').on('change', function() {
            filterTable($(this).val());
        });
    });

    function filterTable(val) {
        if (document.location.href.indexOf('?') > -1) {
            var url = "{{ url('master/template') }}?filterKategori=" + val 
                + "&keyword=" + $('#keyword')
                .val();
        } else {
            var url = "{{ url('master/template') }}?filterKategori=" + val 
                + "&keyword=" + $('#keyword')
                .val();
        }
        document.location = url;
    }
</script>
@endsection