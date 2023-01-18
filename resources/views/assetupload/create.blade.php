@extends('layouts.admin')
@section('asset', 'active')

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
                        <h1>{{ __('Asset Management') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('asset.index') }}">{{ __('Asset Management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Add New Asset') }}</li>
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
                    <h3 class="card-title">{{ __('Add New Asset') }}</h3>
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
                    @can('asset-create')
                        {!! Form::open(['route' => 'asset.store', 'method' => 'POST']) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Asset Name:</strong>
                                    {!! Form::text('asset_name', null, ['placeholder' => 'Asset Name', 'class' => 'form-control']) !!}
                                    @if ($errors->has('asset_name'))
                                        <span class="text-danger">{{ $errors->first('asset_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Asset code:</strong>
                                    {!! Form::text('asset_code', null, ['placeholder' => 'Asset Code', 'class' => 'form-control']) !!}
                                    @if ($errors->has('asset_code'))
                                        <span class="text-danger">{{ $errors->first('asset_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Serial Number:</strong>
                                    {!! Form::text('serial_number', null, ['placeholder' => 'Serial Number', 'class' => 'form-control']) !!}
                                    @if ($errors->has('serial_number'))
                                        <span class="text-danger">{{ $errors->first('serial_number') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Asset facility:</strong>
                                    {!! Form::text('asset_facility', null, ['placeholder' => 'Asset facility', 'class' => 'form-control']) !!}
                                    @if ($errors->has('asset_facility'))
                                        <span class="text-danger">{{ $errors->first('asset_facility') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Asset class:</strong>
                                    {!! Form::text('asset_class', null, ['placeholder' => 'Asset class', 'class' => 'form-control']) !!}
                                    @if ($errors->has('asset_class'))
                                        <span class="text-danger">{{ $errors->first('asset_class') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Asset type:</strong>
                                    {!! Form::text('asset_type', null, ['placeholder' => 'Asset type', 'class' => 'form-control']) !!}
                                    @if ($errors->has('asset_type'))
                                        <span class="text-danger">{{ $errors->first('asset_type') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Asset Category:</strong>
                                    <select class="select2 form-control" name="category_id" data-placeholder="Category Group"
                                        style="width: 100%;">
                                        <option value="">-- Choose --</option>
                                        @foreach ($category as $id => $item)
                                            <option value="{{ $id }}"
                                                {{ old('category_id') == $id ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category_id'))
                                        <span class="text-danger">{{ $errors->first('category_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Price:</strong>
                                    {!! Form::text('price', null, ['placeholder' => 'Price', 'class' => 'form-control']) !!}
                                    @if ($errors->has('price'))
                                        <span class="text-danger">{{ $errors->first('price') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Brand:</strong>
                                    <select class="select2 form-control" name="brand_id" data-placeholder="Category Group"
                                        style="width: 100%;">
                                        <option value="">-- Choose --</option>
                                        @foreach ($brand as $id => $item)
                                            <option value="{{ $id }}"
                                                {{ old('brand_id') == $id ? 'selected' : '' }}>
                                                {{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('brand_id'))
                                        <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Vendor:</strong>
                                    <select class="select2 form-control" name="vendor_id" data-placeholder="Category Group"
                                        style="width: 100%;">
                                        <option value="">-- Choose --</option>
                                        @foreach ($vendor as $id => $item)
                                            <option value="{{ $id }}"
                                                {{ old('vendor_id') == $id ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('vendor_id'))
                                        <span class="text-danger">{{ $errors->first('vendor_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Old Tag:</strong>
                                    {!! Form::text('old_tag', null, ['placeholder' => 'Old Tag', 'class' => 'form-control']) !!}
                                    @if ($errors->has('old_tag'))
                                        <span class="text-danger">{{ $errors->first('old_tag') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Buy date:</strong>
                                    {!! Form::Date('buy_date', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('buy_date'))
                                        <span class="text-danger">{{ $errors->first('buy_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Description:</strong>
                                    {!! Form::text('description', null, ['placeholder' => 'Description', 'class' => 'form-control']) !!}
                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>SAP Number:</strong>
                                    {!! Form::text('SAP_number', null, ['placeholder' => 'SAP Number', 'class' => 'form-control']) !!}
                                    @if ($errors->has('SAP_number'))
                                        <span class="text-danger">{{ $errors->first('SAP_number') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>DO Number:</strong>
                                    {!! Form::text('do_number', null, ['placeholder' => 'DO Number', 'class' => 'form-control']) !!}
                                    @if ($errors->has('do_number'))
                                        <span class="text-danger">{{ $errors->first('do_number') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>DO Date:</strong>
                                    {!! Form::Date('do_date', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('do_date'))
                                        <span class="text-danger">{{ $errors->first('do_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>PO Number:</strong>
                                    {!! Form::text('po_number', null, ['placeholder' => 'PO Number', 'class' => 'form-control']) !!}
                                    @if ($errors->has('po_number'))
                                        <span class="text-danger">{{ $errors->first('po_number') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>PO Date:</strong>
                                    {!! Form::Date('po_date', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('po_date'))
                                        <span class="text-danger">{{ $errors->first('po_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Depreciation Start Date:</strong>
                                    {!! Form::Date('dep_start_date', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('dep_start_date'))
                                        <span class="text-danger">{{ $errors->first('dep_start_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Depreciation End Date:</strong>
                                    {!! Form::Date('dep_end_date', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('dep_end_date'))
                                        <span class="text-danger">{{ $errors->first('dep_end_date') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Polis:</strong>
                                    {!! Form::text('polis', null, ['placeholder' => 'Polis', 'class' => 'form-control']) !!}
                                    @if ($errors->has('polis'))
                                        <span class="text-danger">{{ $errors->first('polis') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>condition:</strong>
                                    {!! Form::text('condition', null, ['placeholder' => 'Condition', 'class' => 'form-control']) !!}
                                    @if ($errors->has('condition'))
                                        <span class="text-danger">{{ $errors->first('condition') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Remark:</strong>
                                    {!! Form::text('remarks', null, ['placeholder' => 'Remark', 'class' => 'form-control']) !!}
                                    @if ($errors->has('remarks'))
                                        <span class="text-danger">{{ $errors->first('remarks') }}</span>
                                    @endif
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
