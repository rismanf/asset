@extends('layouts.admin')
@section('checkpower', 'active')

@section('style')
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('Check Power Management') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('checkpower.index') }}">{{ __('Check Power Management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Check Power') }}</li>
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
                    <h3 class="card-title">{{ __('Check Power') }}</h3>
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
                        <div class="col-sm-4 invoice-col">
                            <h2>{{ $rackpower->rack->rack_name }}</h2>
                            <b>Detail :</b><br>
                            <table>
                                <tr>
                                    <td><b>Customer</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $rackpower->rack->customer->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td><b>Location</b></td>
                                    <td><b>:</b></td>
                                    <td>- {{ $rackpower->rack->site->site_name }}<br>-
                                        {{ $rackpower->rack->floor->floor_name }}</td>
                                </tr>

                                <tr>
                                    <td><b>Rack VA Default</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $rackpower->rack->rackpowerdefault->power_default ?? 0 }} VA</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4 invoice-col">
                            <b>Last check:</b><br>
                            <table>
                                <tr>
                                    <td><b>Date</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $rackpower->rack->approve_date ?? 'No Data' }}</td>
                                </tr>
                                <tr>
                                    <td><b>VA</b></td>
                                    <td>:</td>
                                    <td>{{ $rackpower->rack->approve_date ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>
                        @if ($rackpower->status_id == 1)
                            <div class="col-sm-4 invoice-col">
                                <b>Need Approve:</b><br>
                                <table>
                                    <tr>
                                        <td><b>Date</b></td>
                                        <td><b>:</b></td>
                                        <td>{{ $rackpower->updated_at }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>VA</b></td>
                                        <td>:</td>
                                        <td>{{ $rackpower->rack_va }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>PIC</b></td>
                                        <td>:</td>
                                        <td>{{ $rackpower->user->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>

                    <br><br>

                    @if ($rackpower->status_id == 8 || $rackpower->status_id == 6)
                        <form action="{{ route('checkpower.update', $rackpower->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Rack VA </label>
                                        <input type="hidden" name="proses" value="check">
                                        <input type="hidden" name="rack_before" value="{{ $rackpower->rack_va ?? 0 }}">
                                        <input type="text" class="form-control rack_va" name="rack_va"
                                            placeholder="Volt ampere" required autofocus>
                                        @if ($errors->has('rack_va'))
                                            <span class="text-danger">{{ $errors->first('rack_va') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <button type="submit" id="update" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    @endif

                    @if ($rackpower->status_id == 1)
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <table>
                                    <tr>
                                        <td>
                                            <form action="{{ route('checkpower.update', $rackpower->id) }}" method="POST">
                                                @method('PATCH')
                                                @csrf
                                                <input type="hidden" name="proses" value="reject">
                                                <input type="hidden" name="rack_before"
                                                    value="{{ $rackpower->rack_before ?? 0 }}">
                                                <input type="hidden" name="rack_va"
                                                    value="{{ $rackpower->rack_va ?? 0 }}">
                                                <button type="submit" id="update"
                                                    class="btn btn-warning">Reject</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('checkpower.update', $rackpower->id) }}" method="POST">
                                                @method('PATCH')
                                                @csrf
                                                <input type="hidden" name="proses" value="approved">
                                                <input type="hidden" name="rack_before"
                                                    value="{{ $rackpower->rack_before ?? 0 }}">
                                                <input type="hidden" name="rack_va"
                                                    value="{{ $rackpower->rack_va ?? 0 }}">
                                                <button type="submit" id="update"
                                                    class="btn btn-primary">Approve</button>
                                            </form>
                                        </td>
                                    </tr>
                                </table>


                            </div>
                        </div>
                    @endif
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
    <script src="{{ asset('https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js') }}">
    </script>
    <script>
        $(".rack_va").inputmask({
            alias: "numeric",
        });

        $(".rack_va").on('input', function() {
            if ($(this).val() > {{ $rackpower->rack->rackpowerdefault->power_default }}) {
                alert('you have reached a limit of {{ $rackpower->rack->rackpowerdefault->power_default }} VA');
                $('#update').attr('disabled', 'disabled');
            } else {
                $('#update').removeAttr('disabled');
            }
        });
    </script>
@endsection
