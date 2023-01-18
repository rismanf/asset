@extends('layouts.admin')
@section('settingtree', 'menu-open')
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
                        <li class="breadcrumb-item active">{{ __('Edit User') }}</li>
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
                <h3 class="card-title">{{ __('Edit User') }}</h3>

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
                @can('user-edit')
                
                {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-label">Name</label>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            {!! Form::text('username', null, array('placeholder' => 'Username','class' => 'form-control')) !!}
                        </div>
                      
                        <div class="form-group">
                            <label for="role" class="form-label">Role</label>
                            {!! Form::select('roles', $roles,$userRole, array('class' => 'select2 form-control')) !!}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Site:</strong>
                                @if ($errors->has('sites'))
                                    <span class="text-danger">{{ $errors->first('sites') }}</span>
                                @endif
                                <br />
                                <div class="row">
                                    @foreach ($sites as $id => $name)
                                    <div class="col-xs-12 col-sm-3 col-md-3">
                                        <label>{{ Form::checkbox('sites[]', $id, in_array($id, $usersite) ? true : false, ['class' => 'name']) }}
                                            {{ $name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role" class="form-label">customers</label>
                            <select class="select2" name="customers" data-placeholder="Role" style="width: 100%;">
                                <option value="0">NeutraDC</option>
                                {{-- @foreach ($customers as $item)
                                    <option value="0">{{ $item }}</option>
                                @endforeach --}}
                            </select>
                            @if ($errors->has('customers'))
                                <span class="text-danger">{{ $errors->first('customers') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update user</button>
                        {!! Form::close() !!}
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
