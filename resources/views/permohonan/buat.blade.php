@extends('master')

@section('judul', 'Buat Permohonan')

@section('konten')
<div class="row">
    <div class="col-lg-6 offset-lg-3" id="new" style="display: block">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="mdi mdi-plus me-1"></i> Buat</h4>
                <a href="javascript:void(0);" onclick="toggleCard('#new')" class="btn btn-sm btn-danger"><i class="fas fa-fw fa-times"></i></a>
            </div>
            <div class="card-body">
                <form action="{{ url('buat-permohonan') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Desa</label>
                        <select class="form-control select2" name="desa">
                            <option value="">Pilih</option>
                            @php
                                $desas = DB::table('villages')->where('district_id', 3209181)->orderBy('name', 'ASC')->get();
                            @endphp
                            @foreach ($desas as $desa)
                                <option value="{{ $desa->id }}" @selected(old('desa') == $desa->id)>{{ $desa->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        @php
                            $items = DB::table('item')->where('deleted', 0)->orderBy('name', 'ASC')->get();
                        @endphp
                        @foreach ($items as $item)
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="item[]" id="item{{ $item->id }}" value="{{ $item->id }}" @if(is_array(old('item')) && in_array($item->id, old('item'))) checked @endif>
                            <label class="custom-control-label" for="item{{ $item->id }}">{{ $item->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn w-100 btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="fe-edit me-1"></i> Riwayat Permohonan</h4>
                <div class="row">
                    <div class="col-lg">        
                        <a href="javascript:void(0);" onclick="toggleCard('#new')" class="btn btn-sm btn-info">
                            <i class="fas fa-fw fa-plus"></i> Buat
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('buat-permohonan') }}">
                    <div class="row">
                        <div class="col-md"></div>
                        <div class="col-md-3 ml-auto">
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
                            {{-- <th>Catatan</th> --}}
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
                            <td><a href="{{ url('buat-permohonan/detail/'.$permohonan->kode) }}">{{ $permohonan->kode }}</a></td>
                            <td>{{ $permohonan->desa }}</td>
                            <td class="align-middle text-center text-nowrap">
                                <span class="btn btn-{{ $btn_st }} btn-sm">{{ $status }}</span>
                            </td>
                            {{-- <td>{{ $permohonan->catatan }}</td> --}}
                            <td class="align-middle text-center text-nowrap no-redirect">
                                <a href="{{ url('buat-permohonan/'.$permohonan->kode) }}" class="btn btn-danger btn-sm mx-1 btn-hapus" data-title="{{ $permohonan->kode }}" data-deleted="{{ $permohonan->deleted }}"><i class="fa fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr class="hidden-row" style="display: none;">
                            <td colspan="5">
                                <!-- Informasi tambahan atau kolom tersembunyi -->
                                <p class="mb-0">
                                    <strong>Catatan:</strong> {{ $permohonan->catatan }}<br>
                                    <strong>Dibuat Oleh:</strong> {{ $permohonan->created_by }} - {{ \Carbon\Carbon::parse($permohonan->created_at)->format('d-m-Y H:i:s') }}
                                </p>
                                <!-- Anda bisa tambahkan konten lain di sini -->
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada permohonan.</td>
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
    });
</script>
@endsection