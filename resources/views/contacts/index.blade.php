@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Contact Management') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-right">
                            @can('contact-create')
                                <a class="btn btn-success" href="{{ route('contact.create') }}"> Create New contact</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    
                    
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    
                    
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th width="280px">Action</th>
                        </tr>
                        
                        @foreach ($data as $key => $contact)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $contact->name }}</td>
                            <td>
                                <a class="btn btn-info" href="{{ route('contact.show',$contact->id) }}">Show</a>
                                @can('contact-edit')
                                    <a class="btn btn-primary" href="{{ route('contact.edit',$contact->id) }}">Edit</a>
                                @endcan
                                @can('contact-delete')
                                    {!! Form::open(['method' => 'DELETE','route' => ['contact.destroy', $contact->id],'style'=>'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    
                    {{ $data->links('layouts.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection