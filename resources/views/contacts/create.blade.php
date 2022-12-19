@extends('layouts.app')



@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Create New contact') }}</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-right">
                                    <a class="btn btn-primary" href="{{ route('contact.index') }}"> Back</a>
                                </div>
                            </div>
                        </div>


                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        {!! Form::open(['route' => 'contact.store', 'method' => 'POST']) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group" >
                                    <strong>Phone:</strong>
                                    {!! Form::text('phone', null, ['placeholder' => 'phone', 'class' => 'form-control']) !!}
                                </div>
                                <div class="form-group" >
                                    <strong>Email:</strong>
                                    {!! Form::text('email', null, ['placeholder' => 'email', 'class' => 'form-control']) !!}
                                </div>
                                <div class="form-group" >
                                    <strong>Address:</strong>
                                    {!! Form::text('address', null, ['placeholder' => 'Address', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <hr>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

