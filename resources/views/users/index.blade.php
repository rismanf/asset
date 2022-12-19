@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Users Management') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-right">
                                <a class="btn  btn-success" href="{{ route('users.create') }}"> Create New User</a>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($data as $key => $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $v)
                                            <span class="badge text-bg-success">{{ $v }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('users.show', $user->id) }}">Show</a>
                                    <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
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
