@extends('layouts.admin')
@section('customer', 'active')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('Customer Management') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('customer.index') }}">{{ __('Customer Management') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Add New Customer') }}</li>
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
                    <h3 class="card-title">{{ __('Add New Customer') }}</h3>
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
                                    <strong>Customer Name:</strong>
                                    {!! Form::text('customer_name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                                    @if ($errors->has('customer_name'))
                                        <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Customer Short Name:</strong>
                                    {!! Form::text('customer_short_name', null, ['placeholder' => 'Short Name', 'class' => 'form-control']) !!}
                                    @if ($errors->has('customer_short_name'))
                                        <span class="text-danger">{{ $errors->first('customer_short_name') }}</span>
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
