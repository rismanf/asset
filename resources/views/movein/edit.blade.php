@extends('layouts.admin')
@section('rack', 'active')

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
                    <h1>{{ __('Rack Management') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rack.index') }}">{{ __('Rack Management') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('Edit Rack') }}</li>
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
                <h3 class="card-title">{{ __('Edit Rack') }}</h3>
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
                @can('rack-edit')
                {!! Form::model($rack, ['method' => 'PATCH','route' => ['rack.update', $rack->id]]) !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Customer:</strong>
                                {!! Form::select('customer_id', $customer, $customer_id, ['id'=>'customer' ,'class' => 'form-control select2']) !!}
                                @if ($errors->has('customer_id'))
                                    <span class="text-danger">{{ $errors->first('customer_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Site:</strong>
                                {!! Form::select('site_id', $site, $site_id, ['id'=>'site' ,'class' => 'form-control select2']) !!}
                                @if ($errors->has('site_id'))
                                    <span class="text-danger">{{ $errors->first('site_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Floor:</strong>
                                {!! Form::select('floor_id', $floor, $floor_id, ['id'=>'floor' ,'class' => 'form-control select2']) !!}
                                @if ($errors->has('floor_id'))
                                    <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Rack VA Default:</strong>
                                {!! Form::select('rack_default_id', $power_default, $rack_default_id, ['class' => 'form-control']) !!}
                                @if ($errors->has('rack_default_id'))
                                    <span class="text-danger">{{ $errors->first('rack_default_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Rack Name:</strong>
                                {!! Form::text('rack_name', null, ['placeholder' => 'Rack Name', 'class' => 'form-control']) !!}
                                @if ($errors->has('rack_name'))
                                    <span class="text-danger">{{ $errors->first('rack_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Rack Description:</strong>
                                {!! Form::text('rack_description', null, ['placeholder' => 'Rack Description', 'class' => 'form-control']) !!}
                                @if ($errors->has('rack_description'))
                                    <span class="text-danger">{{ $errors->first('rack_description') }}</span>
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
    <script>
        $('#customer').change(function() {
            var id = $(this).val();
            $("#site").html('<option value="">Loading ...</option>');
            $.ajax({
                type: "GET",
                url: "{{ route('getsitecustomer') }}",
                data: {
                    id: id,
                },
                dataType: 'JSON',
                success: function(res) {
                    $("#site").empty();
                    $("#floor").empty();
                    if (res) {
                        if (res.length == 0) {
                            $("#site").append('<option value="">No Data</option>');
                            $("#floor").append('<option value="">No Data</option>');
                        } else {
                            $("#site").append('<option value="">-- Choose  --</option>');
                            $.each(res, function(id, nama) {
                                $("#site").append('<option value="' + id + '">' + nama +
                                    '</option>');
                            });
                        }
                    }
                }
            });
        });

        $('#site').change(function() {
            var id = $(this).val();
            $("#floor").html('<option value="">Loading...</option>');
            $.ajax({
                type: "GET",
                url: "{{ route('getfloorcustomer') }}",
                data: {
                    id: id,
                },
                dataType: 'JSON',
                success: function(res) {
                    $("#floor").empty();
                    if (res) {
                        if (res.length == 0) {
                            $("#floor").append('<option value="">No Data</option>');
                        } else {
                            $("#floor").append('<option value="">-- Choose  --</option>');
                            $.each(res, function(id, nama) {
                                $("#floor").append('<option value="' + id + '">' + nama +
                                    '</option>');
                            });
                        }

                    }
                }
            });
        });
    </script>
@endsection
