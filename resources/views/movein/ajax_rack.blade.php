<table class="table" width="100%">
    <thead>
        <tr>
            <th width="15%">Rack Name</th>
            <th width="10%">VA</th>
            <th width="5%">Status</th>
            <th width="70%">Detail Item</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $k => $v)
            <?php $i = 0; ?>
            <tr>
                <td>{{ $v->rack_name }}</td>
                <td>Rack Default: {{ $v->rackpowerdefault->power_default }}<br>
                    VA Now: {{ $v->rack_va }}<br>
                    VA Avail: {{ $v->rackpowerdefault->power_default - $v->rack_va}}</td>
                <td>
                    <p class="status{{ $k }}"><span class="badge badge-success">Ready</span></p>
                </td>
                <td>
                    <div>
                        <table class="table" width="100%">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Item VA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="item[{{ $k }}][id]"
                                            value="{{ $v->id }}">
                                        <input type="hidden" name="item[{{ $k }}][va_default]"
                                            class="va_default_{{ $k }}" value="{{ $v->rackpowerdefault->power_default }}">
                                        <input type="hidden" name="item[{{ $k }}][va_now]"
                                            class="va_now_{{ $k }}" value="{{ $v->rack_va }}">
                                        <input type="text" class="form-control"
                                            name="item[{{ $k }}][data][{{ $i }}][item_name]"
                                            placeholder="Item Name" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control va_check_{{ $k }}"
                                            name="item[{{ $k }}][data][{{ $i }}][ampere]"
                                            placeholder="volt ampere">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p>
                            <button type="button" class="btn btn-xs btn-success add_{{ $k }}"><i
                                    class="fa fa-plus"></i> Add
                                More</button>
                            <button type="button" class="btn btn-xs btn-danger del"
                                style="display: none; ">Delete</button>
                        </p>
                    </div>
                    <script>
                        var id_mrc = {{ $i }};
                        $("button.add_{{ $k }}").click(function() {
                            $(this).siblings("button").show("fast");
                            $(this).parent().prev("table").find("tbody").append(
                                `<tr>
                                        <td> <input type="hidden" name="item[{{ $k }}][id]"
                                            value="{{ $v->id }}">
                                            <input type="text" class="form-control"
                                                name="item[{{ $k }}][data][${++id_mrc}][item_name]"
                                                placeholder="Item Name" required></td>
                                        <td><input type="text"
                                                class="form-control va_check_{{ $k }}"
                                                name="item[{{ $k }}][data][${id_mrc}][ampere]"
                                                placeholder="volt ampere"></td>
                                    </tr>`);
                            $('.va_check_{{ $k }}').keyup(function() {
                                var parent = $(this).parents('tr');
                                var va_default = Number($('.va_default_{{ $k }}', parent).val());
                                var va_now = Number($('.va_now_{{ $k }}', parent).val());
                                var avail = va_default - va_now;
                                calcTotalotc(avail, '{{ $k }}');
                            })
                        });
                        $('.va_check_{{ $k }}').keyup(function() {
                            var parent = $(this).parents('tr');
                            var va_default = Number($('.va_default_{{ $k }}', parent).val());
                            var va_now = Number($('.va_now_{{ $k }}', parent).val());
                            var avail = va_default - va_now;
                            calcTotalotc(avail, '{{ $k }}');
                        })
                    </script>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    function calcTotalotc(avail, k) {
        var sum = 0;
        $(".va_check_" + k).each(function() {
            sum += +$(this).val();
        });
        console.log(avail);
        console.log(sum);
        if (avail >= sum) {
            $('.status' + k).html('<span class="badge badge-success">Ready</span>');
            $('#save_move').removeAttr('disabled');
        } else {
            $('.status' + k).html('<span class="badge badge-danger">Not Ready</span>');
            $('#save_move').attr('disabled', 'disabled');
        }
    }

    $("button.del").click(function() {
        var table = $(this).parent().prev("table");
        var rowCount = table.find("tr").length;
        table.find("tr:last").remove();
        if (rowCount <= 2) {
            $(this).hide("fast");
        }

    });
</script>
