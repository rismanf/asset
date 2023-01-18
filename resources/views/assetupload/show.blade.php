<table class="table" width="100%">
    <thead>
        <tr>
            <th>Asset Name</th>
            <th>Status</th>
            <th>Detail Item</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $v)
            <tr>
                <td>{{ $v->asset_name }}</td>
                <td></td>
                <td>{{ $v->updated_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No Data</td>
            </tr>
        @endforelse
    </tbody>
</table>
