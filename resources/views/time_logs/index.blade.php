@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('time-logs.create') }}" class="btn btn-primary mb-3">Create Time Log</a>

        <h1>Time Logs</h1>
        <table id="timeLogsTable">
            <thead>
                <tr>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timeLogs as $timeLog)
                    <tr>
                        <td>{{ $timeLog->start_time }}</td>
                        <td>{{ $timeLog->end_time }}</td>
                        <td><a href="{{ route('time-logs.edit', $timeLog) }}">Edit</a></td>
                        <td>
                            <form method="POST" action="{{ route('time-logs.destroy', $timeLog) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#timeLogsTable').DataTable();
        });
    </script>
@endsection
