@extends('layouts.admin')
@section('locationtree', 'menu-open')
@section('room', 'active')

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
                        <h1>{{ __('Site Management') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('site.index') }}">{{ __('Site Management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Edit Site') }}</li>
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
                    <h3 class="card-title">{{ __('Edit Site') }}</h3>
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
                    @can('room-edit')
                        {!! Form::model($room, ['method' => 'PATCH', 'route' => ['room.update', $room->id]]) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Site:</strong>
                                    <select id="site" class="select2 form-control" name="site_id" data-placeholder="Site"
                                        style="width: 100%;">
                                        <option value="">-- Choose --</option>
                                        @foreach ($site as $id => $item)
                                            <option value="{{ $id }}"
                                                {{ old('site_id', $room->floor->site_id) == $id ? 'selected' : '' }}>{{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('site_id'))
                                        <span class="text-danger">{{ $errors->first('site_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Floor:</strong>
                                    @if ($floor)
                                        <select id="floor" class="select2 form-control" name="floor_id"
                                            data-placeholder="Floor" style="width: 100%;">
                                            @foreach ($floor as $id => $item)
                                                <option value="{{ $id }}"
                                                    {{ old('floor_id', $room->floor_id) == $id ? 'selected' : '' }}>
                                                    {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select id="floor" class="select2 form-control" name="floor_id"
                                            data-placeholder="Floor" style="width: 100%;">
                                        </select>
                                    @endif
                                    @if ($errors->has('floor_id'))
                                        <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Room Name:</strong>
                                    {!! Form::text('room_name', null, ['placeholder' => 'Room Name', 'class' => 'form-control']) !!}
                                    @if ($errors->has('room_name'))
                                        <span class="text-danger">{{ $errors->first('room_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Description:</strong>
                                    <textarea placeholder="Place some text here" class="form-control textarea" name="description" cols="50"
                                        rows="5"></textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
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
        $('#site').change(function() {
            var id = $(this).val();
            $("#floor").html('<option value="">Loading</option>');
            $.ajax({
                type: "GET",
                url: "{{ route('getfloor') }}",
                data: {
                    id: id,
                },
                dataType: 'JSON',
                success: function(res) {
                    $("#floor").empty();
                    if (res) {
                        if (res.length == 0) {
                            $("#floor").append('<option value="">NO Data</option>');
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
