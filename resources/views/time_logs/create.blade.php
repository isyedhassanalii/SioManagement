@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class='col-sm-6'>
            <div class="form-group">
                <form method="POST" action="{{ route('time-logs.store') }}">
                    @csrf

                    <span>Start Date</span>
                    <div class='input-group date' id='datetimepicker1'>
                        <input type='text' class="form-control" name="start_time"  autocomplete="off" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>

                    <span>End Date</span>
                    <div class='input-group date' id='datetimepicker2'>
                        <input type='text' class="form-control" name="end_time"  autocomplete="off"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#datetimepicker1').datetimepicker({
        format: 'MM/DD/YYYY HH:mm',
        useCurrent: false // Prevent automatic selection of current date/time
    });

    $('#datetimepicker2').datetimepicker({
        format: 'MM/DD/YYYY HH:mm',
        useCurrent: false // Prevent automatic selection of current date/time
    });

    // Date validation
    $('#datetimepicker1').on('dp.change', function (e) {
        $('#datetimepicker2').data('DateTimePicker').minDate(e.date);
    });

    $('#datetimepicker2').on('dp.change', function (e) {
        $('#datetimepicker1').data('DateTimePicker').maxDate(e.date);
    });
</script>

@endsection
