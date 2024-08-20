@extends('master')

@section('judul', 'Master Kategori')

@section('konten')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="fa fa-edit me-1"></i> Edit Kategori</h4>
                <a href="{{ url('master/kategori') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-fw fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ url('master/kategori/'.$kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $kategori->name) }}">

                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn w-100 btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection