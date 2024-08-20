@extends('master')

@section('judul', 'Master TLD')

@section('konten')
<div class="row">
    <div class="col-lg-4" id="new">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="mdi mdi-plus me-1"></i> Tambah</h4>
                <a href="javascript:void(0);" onclick="toggleCard('#new')" class="btn btn-sm btn-danger"><i class="fas fa-fw fa-times"></i></a>
            </div>
            <div class="card-body">
                <form action="{{ url('master/tld') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">

                        @error('name')
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
                <h4 class="card-title m-0"><i class="fe-settings me-1"></i> Master TLD</h4>
                <div class="row">
                    <div class="col-lg">        
                        <a href="javascript:void(0);" onclick="toggleCard('#new')" class="btn btn-sm btn-info">
                            <i class="fas fa-fw fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('master/tld') }}">
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
                            <th>Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        @forelse ($tlds as $tld)
                        <tr>
                            <td>{{ (($tlds->currentPage() * $tlds->perpage()) - $tlds->perpage()) + $loop->iteration }}</td>
                            <td>{{ $tld->name }}</td>
                            <td>{{ \App\Helper::currency($tld->harga) }}</td>
                            <td class="align-middle text-center text-nowrap">
                                <a href="{{ url('master/tld/'.$tld->id.'/edit') }}" class="btn btn-success btn-sm mx-1"><i class="fa fa-fw fa-edit"></i></a>
                                <a href="{{ url('master/tld/'.$tld->id) }}" class="btn btn-danger btn-sm mx-1 btn-hapus" data-title="{{ $tld->name }}" data-deleted="{{ $tld->deleted }}"><i class="fa fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada tld.</td>
                        </tr>
                        @endforelse
                    </table>
                    {{ $tlds->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                    <span class="text-muted">Menampilkan {{ $tlds->firstItem()??0 }} sampai {{ $tlds->lastItem()??0 }} dari {{ $tlds->total() }} data.</span>
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