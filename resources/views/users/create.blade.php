@extends('layouts.admin')
@section('user', 'active')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage User</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Manage User</a></li>
                            <li class="breadcrumb-item active">{{ __('Add New User') }}</li>
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
                    <h3 class="card-title">{{ __('Add New User') }}</h3>

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
                    @can('user-create')
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input value="{{ old('name') }}" type="text" class="form-control" name="name"
                                    placeholder="Name" required>

                                @if ($errors->has('name'))
                                    <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input value="{{ old('email') }}" type="email" class="form-control" name="email"
                                    placeholder="Email address" required>
                                @if ($errors->has('email'))
                                    <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="username" class="form-label">Username</label>
                                <input value="{{ old('username') }}" type="text" class="form-control" name="username"
                                    placeholder="Username" required>
                                @if ($errors->has('username'))
                                    <span class="text-danger text-left">{{ $errors->first('username') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input value="{{ old('password') }}" type="password" class="form-control" name="password"
                                    placeholder="Password" required>
                                @if ($errors->has('password'))
                                    <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input value="{{ old('confirm_password') }}" type="password" class="form-control" name="confirm_password" required>
                                @if ($errors->has('confirm_password'))
                                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="role" class="form-label">Role</label>
                                <select class="select2" name="roles" data-placeholder="Role" style="width: 100%;">
                                    @foreach ($roles as $item)
                                        <option>{{ $item }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('roles'))
                                    <span class="text-danger">{{ $errors->first('roles') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="sites" class="form-label">Site</label>
                                <select class="select2" multiple="multiple" name="sites[]" data-placeholder="sites"
                                    style="width: 100%;">
                                    @foreach ($sites as $id=>$name)
                                        <option value="{{ $name }}">{{ $id }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('sites'))
                                    <span class="text-danger">{{ $errors->first('sites') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="role" class="form-label">customers</label>
                                <select class="select2" name="customers" data-placeholder="Role" style="width: 100%;">
                                    <option value="0">NeutraDC</option>
                                    @foreach ($customers as $item)
                                        <option value="0">{{ $item }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('customers'))
                                    <span class="text-danger">{{ $errors->first('customers') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Save user</button>
                        </form>
                    @endcan
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
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            
        });
    </script>
@endsection
