@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Evaluation</h1>
        <div class="row">
            <div class="col-md-4">
                <label for="dateFilter">Filter by Date:</label>
                <input type="date" id="dateFilter">
            </div>
            <div class="col-md-4">
                <label for="weekFilter">Filter by Week:</label>
                <input type="week" id="weekFilter">
            </div>
            <div class="col-md-4">
                <label for="monthFilter">Filter by Month:</label>
                <input type="month" id="monthFilter">
            </div>
        </div>
        @if (count($formattedEvaluationData) > 0)
            <table class="table" id="evaluationTable">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Time Period</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formattedEvaluationData as $evaluationData)
                        <tr>
                            <td>{{ $evaluationData['start_date'] }}</td>
                            <td>{{ $evaluationData['end_date'] }}</td>
                            <td>
                                {{ sprintf('%02d:%02d', $evaluationData['total_hours'], $evaluationData['total_minutes']) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No evaluation data available.</p>
        @endif

        <div class="row">
            <div class="col-md-12">
                <canvas style="padding-bottom: 100px;" id="evaluationChart" height="100"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->

    <script>
        $(document).ready(function () {
            var table = $('#evaluationTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'csv', text: 'Export to CSV' },
                    { extend: 'print', text: 'Print' }
                ]
            });

            // Create an array to store labels and data for the chart
            var chartLabels = [];
            var chartData = [];

            // Populate the chart data from the formattedEvaluationData
            @foreach ($formattedEvaluationData as $evaluationData)
                chartLabels.push("{{ $evaluationData['start_date'] }} - {{ $evaluationData['end_date'] }}");
                chartData.push({{ $evaluationData['total_hours'] + ($evaluationData['total_minutes'] / 60) }});
            @endforeach

            // Create the evaluation chart
            var ctx = document.getElementById('evaluationChart').getContext('2d');
            var evaluationChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Total Hours',
                        data: chartData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            $('#dateFilter, #weekFilter, #monthFilter').on('change', function () {
                table.draw();
            });

            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var startDate = new Date($('#dateFilter').val());
                var weekValue = $('#weekFilter').val();
                var monthValue = $('#monthFilter').val();
                var startDateColumn = new Date(data[0]); // Assuming start date is in the first column
                
                // Compare only year, month, and day parts for exact date match
                var selectedYear = startDate.getFullYear();
                var selectedMonth = startDate.getMonth();
                var selectedDay = startDate.getDate();

                var rowYear = startDateColumn.getFullYear();
                var rowMonth = startDateColumn.getMonth();
                var rowDay = startDateColumn.getDate();

                if ($('#dateFilter').val() &&
                    selectedYear === rowYear &&
                    selectedMonth === rowMonth &&
                    selectedDay === rowDay
                ) {
                    return true;
                }

                if (weekValue && weekValue === moment(startDateColumn).format('YYYY-[W]WW')) {
                    return true;
                }

                if (monthValue && monthValue === moment(startDateColumn).format('YYYY-MM')) {
                    return true;
                }

                return false;
            });
        });
    </script>
@endsection
