@extends('master')

@section('judul', 'Master Kategori')

@section('konten')
<div class="row">
    <div class="col-lg-4" id="new">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="mdi mdi-plus me-1"></i> Tambah</h4>
                <a href="javascript:void(0);" onclick="toggleCard('#new')" class="btn btn-sm btn-danger"><i class="fas fa-fw fa-times"></i></a>
            </div>
            <div class="card-body">
                <form action="{{ url('master/kategori') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">

                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn w-100 btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="fe-settings me-1"></i> Master Kategori</h4>
                <div class="row">
                    <div class="col-lg">        
                        <a href="javascript:void(0);" onclick="toggleCard('#new')" class="btn btn-sm btn-info">
                            <i class="fas fa-fw fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('master/kategori') }}">
                    <div class="row">
                        <div class="col-md"></div>
                        <div class="col-md">
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
                            <th>Nama</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        @forelse ($kategoris as $kategori)
                        <tr>
                            <td>{{ (($kategoris->currentPage() * $kategoris->perpage()) - $kategoris->perpage()) + $loop->iteration }}</td>
                            <td>{{ $kategori->name }}</td>
                            <td class="align-middle text-center text-nowrap">
                                <a href="{{ url('master/kategori/'.$kategori->id.'/edit') }}" class="btn btn-success btn-sm mx-1"><i class="fa fa-fw fa-edit"></i></a>
                                <a href="{{ url('master/kategori/'.$kategori->id) }}" class="btn btn-danger btn-sm mx-1 btn-hapus" data-title="{{ $kategori->name }}" data-deleted="{{ $kategori->deleted }}"><i class="fa fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada kategori.</td>
                        </tr>
                        @endforelse
                    </table>
                    {{ $kategoris->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                    <span class="text-muted">Menampilkan {{ $kategoris->firstItem()??0 }} sampai {{ $kategoris->lastItem()??0 }} dari {{ $kategoris->total() }} data.</span>
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
    });
</script>
@endsection