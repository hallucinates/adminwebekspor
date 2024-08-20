@extends('master')

@section('judul', 'Dasbor')

@section('konten')
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <div class="clearfix">
                <div class="float-left">
                    <h2>Aplikasi {{ \App\Helper::pengaturan('nama') }}</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="float-left mt-3">
                        <p><b class="text-danger">tayo</b></p>
                    </div>

                </div><!-- end col -->
            </div>
            <!-- end row -->
        </div>

    </div>

</div>
@endsection