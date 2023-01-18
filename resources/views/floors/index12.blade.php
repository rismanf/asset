@extends('layouts.admin')
@section('locationtree', 'active')
@section('floor', 'active')

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
                        <h1>{{ __('Floor Management') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('Floor Management') }}</li>
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
                    <h3 class="card-title">{{ __('Site List') }}</h3>

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
                    @can('floor-create')
                        <p><a class="btn btn-success" href="{{ route('floor.create') }}"> Create New Floor</a></p>
                    @endcan
                        <form action="{{ route('floor.index') }}" method="get">
                            <div class="row">
                                <div class="col-xs-12 col-sm-2 col-md-2">
                                    <select class="form-control" name="sites" data-placeholder="sites"
                                    style="width: 100%;" >
                                    <option value="">All Site</option>
                                    @foreach ($site as $id=>$name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                </div>
                                <div class="col-xs-12 col-sm-2 col-md-2">
                                    <input type="text" class="form-control" name="search" placeholder="Search..."
                                        value="{{ old('search') }}">
                                </div>
                                <div class="col-xs-12 col-sm-2 col-md-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    <table class="table yajra-dt">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Floor Name</th>
                                <th>Site</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($floor as $item)
                                <tr>
                                    <td>{{ ($floor->currentPage() - 1) * $floor->perPage() + $loop->index + 1 }}</td>
                                    <td>{{ $item->floor_name }}</td>
                                    <td>{{ $item->site->site_name }}</td>
                                    <td width="280px">
                                        <a href="{{ route('floor.edit', $item->id) }}"
                                            class="edit btn btn-primary">Edit</a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['floor.destroy', $item->id], 'style' => 'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No Record Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example"><br />
                        <ul class="pagination pagination-sm m-0 float-right">
                            Total:{{ $floor->total() }}
                        </ul>
                    </nav>
                    {{ $floor->links() }}
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
    {{-- <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script> --}}
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
