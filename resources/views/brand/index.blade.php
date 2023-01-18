@extends('layouts.admin')
@section('utilititree', 'menu-open')
@section('brand', 'active')

@section('style')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- dataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('Brand Management') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('Brand Management') }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Brand List') }}</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"
                            title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @can('brand-create')
                        <p><a class="btn btn-success" href="{{ route('brand.create') }}"> Create New Brand</a></p>
                    @endcan
                    <table class="table yajra-dt">
                        <thead>
                            <tr>
                                <th>Brand Name</th>
                                <th>Code</th>
                                <th>Last Date</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">

                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('javascript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            var table = $('.yajra-dt').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('brand.index') }}",
                columns: [{
                        data: 'brand_name',
                        name: 'brand_name'
                    },
                    {
                        data: 'brand_code',
                        name: 'brand_code'
                    },
                    {
                        data: 'updated_at',
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'timestamp'
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });
            
            $('body').on('click', '.deletebtn', function() {
                var site_id = $(this).data("id");
                if (confirm("Are you sure want to delete?")) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('brand.store') }}" + '/' + site_id,
                        success: function(data) {
                            alertsuccess(data.status, data.msg)
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error', data);
                        }
                    })
                }

            });

            $('body').on('click', '.restorebtn', function() {
                var id=$(this).data("id");
                if (confirm("Are you sure want to restore data?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('brand.restore') }}",
                        data: {
                            "id": id,
                        },
                        success: function(data) {
                            alertsuccess(data.status, data.msg)
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error', data);
                        }
                    })
                }
            });

            $('body').on('click', '.forcedeletebtn', function() {
                var id=$(this).data("id");
                if (confirm("Are you sure want to permanently delete?")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('brand.forcedelete') }}",
                        data: {
                            "id": id,
                        },
                        success: function(data) {
                            alertsuccess(data.status, data.msg)
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error', data);
                        }
                    })
                }
            });
        });
    </script>

    <!--start alert-->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}" defer></script>
    <script>
        function alertsuccess(type, msg) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                type: type,
                title: msg
            })
        };
    </script>
    @if (session('success'))
        <script>
            $(function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    type: 'success',
                    title: "{{ session('success') }}"
                })
            });
        </script>
    @endcan
    @if (session('error'))
        <!-- sweetalert2 -->
        <script>
            $(function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    type: 'error',
                    title: "{{ session('error') }}"
                })
            });
        </script>
    @endcan
    <!--end alert-->
@endsection
