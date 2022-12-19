@extends('layouts.admin')
@section('request', 'active')

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
                        <h1>{{ __('Request') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('request.index') }}">{{ __('Request') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Add New Request') }}</li>
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
                    <h3 class="card-title">{{ __('Add New Request') }}</h3>
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
                    @can('customer-create')
                        {!! Form::open(['route' => 'customer.store', 'method' => 'POST']) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Company Name:</strong>
                                    {!! Form::text('company_name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                                    @if ($errors->has('company_name'))
                                        <span class="text-danger">{{ $errors->first('company_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="sites" class="form-label">Location:</label>
                                    <select class="select2" name="sites[]" data-placeholder="sites" style="width: 100%;">
                                        @foreach ($sites as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('sites'))
                                        <span class="text-danger">{{ $errors->first('sites') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="floor" class="form-label">Floor:</label>
                                    <select class="select2" name="floor" data-placeholder="floor" style="width: 100%;">
                                    </select>
                                    @if ($errors->has('floor'))
                                        <span class="text-danger">{{ $errors->first('floor') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">Start Date:</label>
                                    {!! Form::date('start_date', null, ['placeholder' => 'Start Date', 'class' => 'form-control']) !!}
                                    @if ($errors->has('start_date'))
                                        <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">End Date:</label>
                                    {!! Form::date('end_date', null, ['placeholder' => 'End Date', 'class' => 'form-control']) !!}
                                    @if ($errors->has('end_date'))
                                        <span class="text-danger">{{ $errors->first('end_date') }}</span>
                                    @endif
                                </div>
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
                                                <label>{{ Form::checkbox('sites[]', $id, false, ['class' => 'name']) }}
                                                    {{ $name }}</label>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
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