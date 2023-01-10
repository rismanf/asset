@extends('layouts.admin')
@section('rack', 'active')

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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('Rack Management') }}</li>
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
                    <h3 class="card-title">{{ __('Rack List') }}</h3>

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
                    <div class="row">
                        <div class="col-sm-6">
                            <h2>{{ $rack->rack_name }}</h2>
                            <b>Detail :</b><br>
                            <table>
                                <tr>
                                    <td><b>Customer</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $rack->customer->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td><b>Location</b></td>
                                    <td><b>:</b></td>
                                    <td>{!! '- '.$rack->site->site_name . '<br> - ' . $rack->floor->floor_name !!}</td>
                                </tr>
                                <tr>
                                    <td><b>Rack VA Default</b></td>
                                    <td>:</td>
                                    <td>{{ $rack->rackpowerdefault->power_default }}</td>
                                </tr>
                                <tr>
                                    <td><b>Status</b></td>
                                    <td>:</td>
                                    <td><span class="badge bg-danger">{{ $rack->status->status_name }}</span></td>
                                </tr>
                            </table>
                            <br><br>
                            <b>Power Check:</b><br>
                            <table>
                                <tr>
                                    <td><b>Date</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $rack->approve_date ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><b>VA</b></td>
                                    <td>:</td>
                                    <td>{{ $rack->rack_va ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><b>PIC</b></td>
                                    <td>:</td>
                                    <td>{{ $rack->pic_name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-6" style="height: 550px; overflow: auto;">
                            <div class="timeline timeline-inverse">
                                <?php $date = ''; ?>
                                @foreach ($log_rack as $v)
                                    @if ($date == '')
                                        <div class="time-label">
                                            <span class="bg-primary">
                                                {{ $v->isdate }}
                                            </span>
                                        </div>
                                        <?php $date = $v->isdate; ?>
                                    @endif

                                    @if ($v->isdate != $date)
                                        <div class="time-label">
                                            <span class="bg-primary">
                                                {{ $v->isdate }}
                                            </span>
                                        </div>
                                        <?php $date = $v->isdate; ?>
                                    @else
                                        <?php $date = $v->isdate; ?>
                                    @endif

                                    <div>

                                        <i class="fas fa-clock bg-info"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i>
                                                {{ $v->istime }}</span>
                                            <h3 class="timeline-header">{{ $v->event }}-{!! $v->description !!}
                                            </h3>
                                        </div>
                                    </div>
                                @endforeach
                                <div>
                                    <i class="far fa-clock bg-gray"></i>
                                </div>
                            </div>
                        </div>
                    </div>
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
