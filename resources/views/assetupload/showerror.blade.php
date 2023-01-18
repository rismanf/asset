<table class="table" width="100%">
    <thead>
        <tr>
            <th>Row</th>
            <th>Attribute</th>
            <th>Error Detail</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $v)
            <tr>
                <td>{{ $v['row'] }}</td>
                <td>{{ $v['attribute'] }}</td>
                <td>
                    <ul>
                        @foreach ($v['errors'] as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    {{ $v['values'][$v['attribute']] }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No Data</td>
            </tr>
        @endforelse
    </tbody>
</table>
