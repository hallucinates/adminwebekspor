@extends('master')

@section('judul', 'Master Template')

@section('konten')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="fa fa-edit me-1"></i> Edit Template</h4>
                <a href="{{ url('master/template') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-fw fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ url('master/template/'.$template->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-control select2 @error('kategori') is-invalid @enderror" name="kategori" style="width: 100%">
                            <option value="">Pilih</option>
                            @php
                                $kategoris = DB::table('kategori')->where('deleted', 0)
                                    ->orderBy('name', 'ASC')->get();
                            @endphp
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" @selected(old('kategori', $template->kategori_id) == $kategori->id)>{{ $kategori->name }}</option>
                            @endforeach
                        </select>

                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $template->name) }}">

                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar" value="{{ old('gambar') }}">
                        <small class="text-danger">* Jika tidak dirubah abaikan saja</small>

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

                            <input type="number" class="form-control @error('harga') is-invalid @enderror" name="harga" value="{{ old('harga', $template->harga) }}" min="1">

                            @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn w-100 btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="fa fa-edit me-1"></i> Preview</h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    <br>
                    <img src="{{ asset($template->gambar) }}" width="100%">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection