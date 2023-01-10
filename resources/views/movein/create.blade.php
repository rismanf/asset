@extends('layouts.admin')
@section('movein', 'active')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script> --}}

@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('Move-in Management') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('movein.index') }}">{{ __('Move-in Management') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Add Request Move-in') }}</li>
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
                    <h3 class="card-title">{{ __('Add Request Move-in') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"
                            title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div>
                </div>
                <form action="{{ route('movein.store') }}" method="post" onSubmit="return confirm('Are you sure?');">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No Ticket<small> by kayako</small></label>
                                    <input type="text" class="form-control" name="no_ticket"
                                        value="{{ old('no_ticket') }}" placeholder="Enter no ticket">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Installation Date</label>
                                    <input type="date" class="form-control" name="installation_date"
                                        value="{{ old('installation_date') }}" placeholder="Enter PIC Name" required>
                                    @if ($errors->has('installation_date'))
                                        <span class="text-danger">{{ $errors->first('installation_date') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <select class="form-control select2" name="customer" id="customer">
                                        <option value="">-- Choose --</option>
                                        @foreach ($customer as $id => $customer_name)
                                            <option value="{{ $id }}"
                                                {{ old('customer') == $id ? 'selected' : '' }}>{{ $customer_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('customer'))
                                        <span class="text-danger">{{ $errors->first('customer') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Site:</label>
                                    <select id="site" class="select2 form-control" name="site_id"
                                        data-placeholder="Site" style="width: 100%;">
                                        <option value="">-- Choose --</option>
                                    </select>
                                    @if ($errors->has('site_id'))
                                        <span class="text-danger">{{ $errors->first('site_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PIC Customer Name</label>
                                    <input type="text" class="form-control" name="pic_name" placeholder="Enter PIC Name"
                                        value="{{ old('pic_name') }}" required>
                                    @if ($errors->has('pic_name'))
                                        <span class="text-danger">{{ $errors->first('pic_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PIC Customer Phone</label><br>
                                    <input class="form-control" type="text" name="pic_phone" placeholder="0818888"
                                        value="{{ old('pic_phone') }}" required>
                                    @if ($errors->has('pic_phone'))
                                        <span class="text-danger">{{ $errors->first('pic_phone') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn bg-gradient-primary btn-sm" id="add_rack_customer">
                            Select Rack
                        </button>
                        <div class="row">
                            <div class="col-md-12" id="detail_item">
                                <table class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="17%">Rack Name</th>
                                            <th width="8%">Status</th>
                                            <th width="75%">Detail Item</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                @if ($errors->has('item'))
                                    <span class="text-danger">{{ $errors->first('item') }}</span>
                                @endif
                            </div>
                        </div>
                        <button type="submit" id="save_move" class="btn btn-primary">Save</button>
                    </div>
                </form>
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

    <div class="modal fade" id="select_rack" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form name="add_rack_detail" id="add_rack_detail" method="POST">
                <input type="hidden" id="customer_id_detail" name="customer_id" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="header_select_rack">Select Rack</h5>
                    </div>
                    <div class="modal-body">
                        <table class="table" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Rack</th>
                                    <th>status</th>
                                    <th>Default VA</th>
                                    <th>Last Check VA</th>
                                    <th>Avail VA</th>
                                </tr>
                            </thead>
                            <tbody id="detail_rack_customer">
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="submit_add_rack" class="btn btn-primary">ADD Rack</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('javascript')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js') }}">
    </script>
    <script>
        // const phoneInputField = document.querySelector("#phone");
        // const phoneInput = window.intlTelInput(phoneInputField, {
        //     preferredCountries: ["id", "sg", "us"],
        //     utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        // });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script>
        $('#customer').change(function() {
            var id = $(this).val();
            $("#site").html('<option value="">Loading</option>');
            $.ajax({
                type: "GET",
                url: "{{ route('getsitecustomer') }}",
                data: {
                    id: id,
                },
                dataType: 'JSON',
                success: function(res) {
                    $("#site").empty();
                    if (res) {
                        if (res.length == 0) {
                            $("#site").append('<option value="">NO Data</option>');
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
    </script>
    <script>
        $("#add_rack_customer").click(function() {
            var id = $('#customer').val();
            var siteid = $('#site').val();
            if (!siteid) {
                alert("Please select site first.");
                return false;
            }
            if (!id) {
                alert("Please select customer first.");
                return false;
            }

            $('#select_rack').modal('show');
            $('#detail_rack_customer').html('Loading...');
            var customerName = $('#customer').find(":selected").text();
            var siteName = $('#site').find(":selected").text();
            $.ajax({
                type: "GET",
                url: "{{ route('get.rack_customer') }}",
                data: {
                    id: id,
                    siteid: siteid,
                },
                dataType: 'JSON',
                success: function(res) {

                    $('#header_select_rack').html('Rack - ' + customerName + ' - ' + siteName);
                    $('#customer_id_detail').val(id);
                    if (res.length == 0) {
                        $('#detail_rack_customer').html('No Data');
                        $('#submit_add_rack').attr('disabled', 'disabled');
                    } else {
                        $('#detail_rack_customer').html('');
                        $.each(res, function(k, data) {
                            var avail = data.rackpowerdefault.power_default - data.rack_va;
                            var disable = 'disabled';
                            if (data.flagging == 1 && data.status_id == 9) {
                                var disable = '';
                            }
                            $('#detail_rack_customer').append(`<tr>
                            <td><input type="checkbox" value="${data.id}" name="id[]" ${disable}/></td>
                            <td>${data.rack_name}</td>
                            <td><span class="badge badge-${data.status.badge}">${data.status.status_name}</span> </td>
                            <td>${data.rackpowerdefault.power_default} VA</td>
                            <td>${data.rack_va} VA</td>
                            <td>${avail} VA</td>
                        </tr>`);
                        });
                        $('#submit_add_rack').removeAttr('disabled');
                    }
                },
                error: function(res) {
                    $('#detail_rack_customer').html('Please refresh the page and try again');
                }
            });

        });

        $('#add_rack_detail').on('submit', function(e) {
            $('#select_rack').modal('hide');
            e.preventDefault();
            var data = $('#add_rack_detail').serialize();
            $.ajax({
                type: "GET",
                url: "{{ route('get.rack_customer_check') }}",
                data: data,
                dataType: 'html',
                success: function(res) {
                    $("#detail_item").html(res);
                }
            });


        });
    </script>
@endsection
