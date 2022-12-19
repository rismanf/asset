@extends('layouts.admin')
@section('webtreeview', 'menu-open')
@section('websolution', 'active')

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
                        <h1>{{ __('Web Solution Management') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('Web Solution Management') }}</li>
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
                    <h3 class="card-title">{{ __('Web Solution List') }}</h3>

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
                    <p><a class="btn btn-success" href="{{ route('websolution.create') }}"> Create New websolution</a></p>
                    <table class="table yajra-dt">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>title</th>
                                <th>img</th>
                                <th>desc</th>
                                <th>publish</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($solution->count() == 0)
                                <tr>
                                    <td colspan="5">No data to display.</td>
                                </tr>
                            @endif
                            @foreach ($solution as $item)
                                <tr>
                                    <td>{{ ($solution->currentPage() - 1) * $solution->perPage() + $loop->index + 1 }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->img }}</td>
                                    <td>{!! substr($item->text,0,100).'...' !!}</td>
                                    <td>{{ $item->publish_date }}</td>
                                    <td width="280px">
                                        <a class="btn btn-primary" href="{{ route('websolution.edit', $item->id) }}">Edit</a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['websolution.destroy', $item->id], 'style' => 'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example"><br />
                        <ul class="pagination pagination-sm m-0 float-right">
                            Total:{{ $solution->total() }}
                        </ul>
                    </nav>
                    {{ $solution->links() }}
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

    <!--start alert-->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}" defer></script>
    <script>
        // function alertsuccess(type, msg) {
        //     const Toast = Swal.mixin({
        //         toast: true,
        //         position: 'top-end',
        //         showConfirmButton: false,
        //         timer: 3000
        //     });
        //     Toast.fire({
        //         type: type,
        //         title: msg
        //     })
        // };
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
