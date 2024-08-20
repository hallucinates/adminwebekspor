<!DOCTYPE html>
<html lang="id-ID">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">

	<title>@yield('judul') — {{ \App\Helper::pengaturan('nama') }}</title>

    <link rel="shortcut icon" href="{{ \App\Helper::pengaturan('logo') }}">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ \App\Helper::pengaturan('deskripsi') }}">
    <meta name="keyword" content="{{ \App\Helper::pengaturan('kata-kunci') }}">
    <meta name="author" content="oxdnr">
    <meta name="theme-color" content="">
    <meta name="robots" content="index, follow">
    <meta name="device" content="desktop">
    <meta name="coverage" content="Worldwide">
    <meta name="apple-mobile-web-app-title" content="{{ \App\Helper::pengaturan('judul') }} — {{ \App\Helper::pengaturan('nama') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta property="og:type" content="website">
	<meta property="og:url" content="{{ url()->full() }}">
	<meta property="og:title" content="{{ \App\Helper::pengaturan('judul') }} — {{ \App\Helper::pengaturan('nama') }}">
	<meta property="og:site_name" content="{{ \App\Helper::pengaturan('judul') }} — {{ \App\Helper::pengaturan('nama') }}">
	<meta property="og:description" content="{{ \App\Helper::pengaturan('deskripsi') }}">
	<meta property="og:image" content="{{ \App\Helper::pengaturan('logo') }}">
	<meta property="og:image:alt" content="{{ \App\Helper::pengaturan('logo-alt') }}">

    <link href="{{ url('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/libs/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />

    <link href="{{ url('assets') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/css/app.min.css" rel="stylesheet" type="text/css" />

    <link href="{{ url('assets') }}/HoldOn.min.css" rel="stylesheet">

    <style type="text/css">	
        .small-table {
            font-size: 0.875rem; /* Ukuran font yang lebih kecil */
        }

        .small-table th, .small-table td {
            padding: 0.5rem; /* Kurangi padding */
        }

        .small-table .btn-sm {
            padding: 0.2rem 0.4rem; /* Ukuran tombol yang lebih kecil */
            font-size: 0.75rem; /* Ukuran font yang lebih kecil untuk tombol */
        }

        .small-table .text-center {
            text-align: center;
        }

        .small-table .text-nowrap {
            white-space: nowrap;
        }
        
        tr[data-url] {
            cursor: pointer;
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 

    <script src="{{ url('assets') }}/libs/moment/moment.min.js"></script>
    <script src="{{ url('assets') }}/libs/bootstrap-daterangepicker/daterangepicker.js"></script>
</head>
<body>
    <!-- Navigation Bar-->
    <header id="topnav">
        <!-- Topbar Start -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-right mb-0">

                    <li class="dropdown notification-list">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            {{-- <img src="assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle"> --}}
                            <i class="fa fa-user"></i>
                            <span class="ml-1">{{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i> </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Selamat Datang!</h6>
                            </div>

                            <!-- item-->
                            {{-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>Profile</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-settings"></i>
                                <span>Settings</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-lock"></i>
                                <span>Lock Screen</span>
                            </a> --}}

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="{{ url('logout') }}" class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>

                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="{{ url('/') }}" class="logo text-center">
                        <span class="logo-lg">
                            <img src="{{ url('assets') }}/images/logo-light.png" alt="" height="16">
                            {{-- <span class="logo-lg-text-light">{{ \App\Helper::pengaturan('nama') }}</span> --}}
                        </span>
                        <span class="logo-sm">
                            {{-- <span class="logo-sm-text-dark">{{ \App\Helper::pengaturan('nama') }}</span> --}}
                            <img src="{{ url('assets') }}/images/logo-sm.png" alt="" height="24">
                        </span>
                    </a>
                </div>

            </div> <!-- end container-fluid-->
        </div>
        <!-- end Topbar -->

        <div class="topbar-menu">
            <div class="container-fluid">
                <div id="navigation">
                    <!-- Navigation Menu-->
                    <ul class="navigation-menu">

                        <li class="has-submenu">
                            <a href="{{ url('/dasbor') }}"><i class="fe-airplay"></i>Dasbor</a>
                        </li>

                        <li class="has-submenu">
                            <a href="#"><i class="fe-edit"></i>File
                            </a>
                            <ul class="submenu">
                                <li class="has-submenu">
                                    <a href="#">Populer <div class="arrow-down"></div></a>
                                    <ul class="submenu">
                                        <li><a href="{{ url('populer/template') }}">Template</a></li>
                                        <li><a href="{{ url('populer/tld') }}">TLD</a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu">
                                    <a href="#">Promo <div class="arrow-down"></div></a>
                                    <ul class="submenu">
                                        <li><a href="{{ url('promo/tld') }}">TLD</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="has-submenu">
                            <a href="#"><i class="fe-activity"></i>Laporan
                            </a>
                            <ul class="submenu">
                                {{-- <li><a href="{{ url('laporan/rekap-per-item') }}">Rekap Per Item</a></li> --}}
                                {{-- <li><a href="{{ url('laporan/rekap-permohonan') }}">Rekap Permohonan</a></li> --}}
                            </ul>
                        </li>

                        <li class="has-submenu">
                            <a href="#"><i class="fe-settings"></i>Tools
                            </a>
                            <ul class="submenu">
                                <li class="has-submenu">
                                    <a href="#">Master <div class="arrow-down"></div></a>
                                    <ul class="submenu">
                                        <li><a href="{{ url('master/kategori') }}">Kategori</a></li>
                                        <li><a href="{{ url('master/template') }}">Template</a></li>
                                        <li><a href="{{ url('master/tld') }}">TLD</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    </ul>
                    <!-- End navigation menu -->

                    <div class="clearfix"></div>
                </div>
                <!-- end #navigation -->
            </div>
            <!-- end container -->
        </div>
        <!-- end navbar-custom -->
    </header>

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="wrapper">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ \App\Helper::pengaturan('nama') }}</a></li>
                                {{-- <li class="breadcrumb-item active"></li> --}}
                            </ol>
                        </div>
                        <h4 class="page-title">@yield('judul')</h4>
                    </div>
                </div>
            </div>     
            <!-- end page title -->
            
            @yield('konten')
                
        </div> <!-- end container -->
    </div>
    <!-- end wrapper -->

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center">
                    &copy; <script>document.write(new Date().getFullYear());</script> {{ \App\Helper::pengaturan('nama') }}
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->

    <!-- Vendor js -->
    <script src="{{ url('assets') }}/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="{{ url('assets') }}/js/app.min.js"></script>

    <script src="{{ url('assets') }}/libs/select2/select2.min.js"></script>
    <script src="{{ url('assets') }}/libs/chart-js/Chart.bundle.min.js"></script>
    <script src="{{ url('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>

    <script src="{{ url('assets') }}/HoldOn.min.js"></script>

    <script>
        $(document).ready(function() {
            HoldOn.close();
        })

        $(".select2").select2(), $(".select2-limiting").select2({
            maximumSelectionLength: 2
        });

        $('.table').on('click', '.btn-hapus', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let title = $(this).data('title');
            let deleted = $(this).data('deleted');

            let textMessage = "Data yang akan dihapus: " + title;
            let btnText = 'Ya, Hapus!';

            if (deleted == 1) {
                textMessage = "Data yang akan dikembalikan: " + title;
                btnText = 'Ya, Kembalikan!';
            }

            if (deleted == 'permanen') {
                textMessage = "Data yang akan dihapus permanen: " + title;
                btnText = 'Ya, Hapus Permanen!';
            }

            Swal.fire({
                title: 'Anda yakin?',
                text: textMessage,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1A237E',
                cancelButtonColor: '#B71C1C',
                confirmButtonText: btnText,
                cancelButtonText: 'Batal',
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        data: {
                            _token: $("meta[name='csrf-token']").attr('content'),
                        },
                    })
                        .done(function (res) {
                            var tipe = 'success';
                            if (res.tipe) {
                                var tipe = res.tipe;
                            }

                            var title = 'Gotcha!';
                            if (tipe == 'error') {
                                var title = 'Gagal!';
                            }

                            Swal.fire({
                                title: title,
                                html: res.pesan,
                                type: tipe,
                                showCancelButton: false,
                                showConfirmButton: true,
                            }).then(function() {
                                window.location.reload();
                            });
                        })
                        .fail(function (err) {
                            const data = err.responseJSON;

                            Swal.fire({
                                title: 'Terjadi Kesalahan!',
                                html: data.pesan,
                                type: 'error',
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 3000,
                            })
                        });
                }
            });
        });

        $('.table').on('click', '.btn-sa', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let action = $(this).data('action');            
            let title = $(this).data('title');            

            let textMessage = "Data yang akan di" + action + ": " + title;
            let btnText = 'Ya!';

            Swal.fire({
                title: 'Anda yakin?',
                text: textMessage,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1A237E',
                cancelButtonColor: '#B71C1C',
                confirmButtonText: btnText,
                cancelButtonText: 'Batal',
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            _token: $("meta[name='csrf-token']").attr('content'),
                        },
                    })
                        .done(function (res) {
                            Swal.fire({
                                title: 'Gotcha!',
                                html: res.pesan,
                                type: 'success',
                                showCancelButton: false,
                                showConfirmButton: true,
                            }).then(function() {
                                window.location.reload();
                            });
                        })
                        .fail(function (err) {
                            const data = err.responseJSON;

                            Swal.fire({
                                title: 'Terjadi Kesalahan!',
                                html: data.pesan,
                                type: 'error',
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 3000,
                            })
                        });
                }
            });
        });

        HoldOn.open({
            theme: 'sk-circle',
            message: 'Sedang memuat...',
            textColor: 'white'
        });

        $(document).ajaxStart(function() {
            HoldOn.open({
                theme: 'sk-circle',
                message: 'Sedang memuat...',
                textColor: 'white'
            });
        })

        $(document).ajaxStop(function() {
            HoldOn.close();
        })

        $(document).ajaxError(function(e, xhr) {
            if (xhr.status == 403) return swallNotif('error', 'Anda tidak memiliki akses!')
            if (xhr.status == 404) return swallNotif('error', 'Halaman tidak ditemukan!')
            return swallNotif('error', 'Gagal memproses data, kesalahan tidak terduga.')
        })

        const rupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(number);
        }

        $(document).on('keydown', ':input:not(textarea)', function(event) {
            return event.key != 'Enter';
        });

        $('form').on('submit', function() {
            $('button[type=submit]').attr('disabled', 'true')
        });

        function toggleCard(target) {
            $(target).toggle('fast', 'swing');
        }

        function togglePassword(btn, input) {
            $(btn).find("i").toggleClass("fa-eye fa-eye-slash");
            var input = $(input);
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        }

        function show_modal(title, target, size = 'modal-lg') {
            $('#show-modal #modal-dialog').removeClass()
            $('#show-modal #modal-dialog').addClass('modal-dialog ' + size)
            $('#show-modal').modal('hide');
            $('#show-modal-title').html(title);
            $('#show-modal-btn-refresh').attr('onclick', 'show_modal(`' + title + '`, `' + target + '`)')
            $.get(target, function(result) {
                $('#show-modal-body').html(result);
                $('#show-modal').modal('show');
            }).fail(function(e) {
                // alert("Something went wrong.");
            });
        }

        var clipboard = new ClipboardJS('.btn-copy')

        var clipboard_modal = new ClipboardJS('.btn-copy-modal', {
            container: document.getElementById('penuliskode-modal')
        })

        clipboard.on('success', function(e) {
            toastNotif("success", "Disalin: " + e.text)
        });

        clipboard.on('error', function(e) {
            toastNotif("error", "Terjadi kesalahan!")
        });

        clipboard_modal.on('success', function(e) {
            toastNotif("success", "Disalin: " + e.text)
        });

        clipboard_modal.on('error', function(e) {
            toastNotif("error", "Terjadi kesalahan!")
        });

        function toastNotif(icon, text) {
            if (icon == "success") {
                var div = $('#toastWrapper div[id^="successToast"]:last');
                var num = parseInt(div.prop("id").match(/\d+/g), 10) + 1;
                var clone = div.clone().prop('id', 'successToast' + num);
            } else {
                var div = $('#toastWrapper div[id^="errorToast"]:last');
                var num = parseInt(div.prop("id").match(/\d+/g), 10) + 1;
                var clone = div.clone().prop('id', 'errorToast' + num);
            }
            clone.appendTo("#toastWrapper").find(".toast-body").html(text);
            var toast = new bootstrap.Toast(clone)
            toast.show()
        }

        function swallNotif(icon, text) {
            if (icon == "success") {
                Swal.fire({
                    title: "Gotcha!",
                    text: text,
                    type: "success",
                    confirmButtonText: '<i class="far fa-grin-hearts me-2"></i> Okay',
                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                    buttonsStyling: false,
                })
            } else {
                Swal.fire({
                    title: "Whoops!",
                    text: text,
                    type: "error",
                    confirmButtonText: '<i class="far fa-frown-open me-2"></i> Okay',
                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                    buttonsStyling: false,
                })
            }
        }
    </script>
</body>
</html>
