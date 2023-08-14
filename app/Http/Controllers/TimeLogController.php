<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeLog;
use Carbon\Carbon;

/**
 * Class TimeLogController
 * @package App\Http\Controllers
 */
class TimeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = auth()->user()->id;
        $timeLogs = TimeLog::where('user_id', $userId)->get();
    
        return view('time_logs.index', compact('timeLogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('time_logs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $startDateTime = Carbon::createFromFormat('m/d/Y H:i', $request->input('start_time'));
        $endDateTime = Carbon::createFromFormat('m/d/Y H:i', $request->input('end_time'));

        // Get the ID of the currently authenticated user
        $userId = auth()->user()->id;

        TimeLog::create([
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'user_id' => $userId, // Add the user ID to the TimeLog record
        ]);

        return redirect()->route('time-logs.index')->with('success', 'Time log recorded successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return \Illuminate\View\View
     */
    public function edit(TimeLog $timeLog)
    {
        return view('time_logs.edit', compact('timeLog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TimeLog  $timeLog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TimeLog $timeLog)
    {
        $timeLog->update([
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
        ]);

        return redirect()->route('time-logs.index')->with('success', 'Time log updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TimeLog $timeLog)
    {
        $timeLog->delete();
        return redirect()->route('time-logs.index')->with('success', 'Time log deleted successfully.');
    }

    /**
     * Display evaluation data for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function evaluation()
    {
        // Get the ID of the currently authenticated user
        $userId = auth()->user()->id;

        // Retrieve TimeLog records for the user
        $timeLogs = TimeLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedEvaluationData = [];

        foreach ($timeLogs as $timeLog) {
            $startDateString = $timeLog['start_time'];
            $endDateString = $timeLog['end_time'];

            // Convert start and end date strings to Carbon objects
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDateString);
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDateString);

            // Calculate the time difference
            $interval = $endDate->diff($startDate);

            // Calculate the total hours, including days
            $totalHours = $interval->days * 24 + $interval->h;
            $totalMinutes = $interval->i;

            $formattedEvaluationData[] = [
                'start_date' => $startDateString,
                'end_date' => $endDateString,
                'total_hours' => $totalHours,
                'total_minutes' => $totalMinutes,
            ];
        }

        return view('time_logs.evaluation', compact('formattedEvaluationData'));
    }
}
