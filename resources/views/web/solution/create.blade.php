@extends('layouts.admin')
@section('webtreeview', 'menu-open')
@section('websolution', 'active')

@section('style')
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
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
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('websolution.index') }}">{{ __('Web Solution Management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Add New Solution') }}</li>
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
                    <h3 class="card-title">{{ __('Add New Web Solution') }}</h3>
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
                    <form method="POST" action="{{ route('websolution.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="form-label">Title</label>
                            <input value="{{ old('title') }}" type="text" class="form-control" name="title"
                                placeholder="Title" required>
                            @if ($errors->has('title'))
                                <span class="text-danger text-left">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="image" class="form-label">Img</label>
                            <input type="file" name="image"
                                class="form-control @error('image') is-invalid @enderror" required>
                            @if ($errors->has('image'))
                                <span class="text-danger text-left">{{ $errors->first('image') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="text" class="form-label">Title</label>
                            <textarea class="textarea" placeholder="Place some text here" name="text"
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                            @if ($errors->has('text'))
                                <span class="text-danger text-left">{{ $errors->first('text') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
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
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(function() {
            // Summernote
            $('.textarea').summernote()
        })
    </script>
@endsection
