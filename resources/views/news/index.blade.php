@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('News Management') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-right">
                            @can('news-create')
                                <a class="btn btn-success" href="{{ route('news.create') }}"> Create New news</a>
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
                        
                        @foreach ($data as $key => $news)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $news->name }}</td>
                            <td>
                                <a class="btn btn-info" href="{{ route('news.show',$news->id) }}">Show</a>
                                @can('news-edit')
                                    <a class="btn btn-primary" href="{{ route('news.edit',$news->id) }}">Edit</a>
                                @endcan
                                @can('news-delete')
                                    {!! Form::open(['method' => 'DELETE','route' => ['news.destroy', $news->id],'style'=>'display:inline']) !!}
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