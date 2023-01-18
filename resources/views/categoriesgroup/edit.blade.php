@extends('layouts.admin')
@section('utilititree', 'menu-open')
@section('categoriestree', 'menu-open')
@section('categoriesgroup', 'active')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('Category Group Management') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('categoriesgroup.index') }}">{{ __('Category Group Management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Edit Category Group') }}</li>
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
                    <h3 class="card-title">{{ __('Edit Category Group') }}</h3>
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
                    @can('category-edit')
                        {!! Form::model($CategoryGroup, [
                            'method' => 'PATCH',
                            'route' => ['categoriesgroup.update', $CategoryGroup->id],
                        ]) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Category Group Name:</strong>
                                    {!! Form::text('category_group_name', null, ['placeholder' => 'Category Group Name', 'class' => 'form-control']) !!}
                                    @if ($errors->has('category_group_name'))
                                        <span class="text-danger">{{ $errors->first('category_group_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Category Group Code:</strong>
                                    {!! Form::text('category_group_code', null, ['placeholder' => 'Category Group Code', 'class' => 'form-control']) !!}
                                    @if ($errors->has('category_group_code'))
                                        <span class="text-danger">{{ $errors->first('category_group_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Description:</strong>
                                    <textarea placeholder="Body" class="form-control" name="body" cols="50" rows="10">{{ old('description', $CategoryGroup->description) }}</textarea>
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
