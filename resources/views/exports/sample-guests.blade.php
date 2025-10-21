@php
// This view is used by the SampleGuestsExport class to generate the Excel file
@endphp
<table>
    <thead>
        <tr>
            <th>name</th>
            <th>email</th>
            <th>phone</th>
        </tr>
    </thead>
    <tbody>
        @foreach($guests as $guest)
        <tr>
            <td>{{ $guest['name'] }}</td>
            <td>{{ $guest['email'] }}</td>
            <td>{{ $guest['phone'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>