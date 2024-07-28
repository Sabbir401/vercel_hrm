<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\employee;
use App\Models\religion;
use App\Models\attendence;
use Illuminate\Http\Request;
use App\Models\zkt_attendence;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;

class AttendenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $zk;

    //Manual Attendence Save
    public function index()
    {
        $attendence = attendence::get();
        return response()->json($attendence);
    }

    public function getAttendance(Request $request)
    {
        $month = $request->query('month');
        $attendance = attendence::whereMonth('date', $month + 1)->get();
        return response()->json($attendance);
    }

    public function fetchZktAttendence()
    {
        Artisan::call('attendance:fetch');
        return response()->json(['message' => 'Attendance fetched successfully.']);
    }

    public function store(Request $request)
    {
        $attendanceData = $request->input('attendanceData');
        $year = now()->year;
        $month = $request->input('month');

        foreach ($attendanceData as $employeeData) {
            $employeeId = $employeeData['id'];
            $attendance = $employeeData['attendance'];

            foreach ($attendance as $day => $status) {
                if (!empty($status)) {
                    attendence::updateOrCreate(
                        [
                            'EID' => $employeeId,
                            'Date' => Carbon::create($year, $month + 1, $day + 1)->toDateString(),
                        ],
                        [
                            'Status' => $status,
                            // 'Time' => now()->toTimeString()
                        ]
                    );
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function generatePdf()
    {
        $attendances = religion::get();
        $employee = employee::get();
        $pdf = Pdf::loadView('reports.attendance', compact('employee'));
        
        return $pdf->stream('attendance_report.pdf');
    }



    public function fetchAttendence()
    {
        // Fetch all attendance records from zkt_attendence
        $attendances = zkt_attendence::all()->groupBy(function ($attendance) {
            return $attendance->user_id . '_' . $attendance->date;
        });

        // Process each attendance record
        $attendances->each(function ($userAttendance, $key) {
            $date = $userAttendance->first()->date;
            $userId = $userAttendance->first()->user_id;

            // Extract the first punch-in time and last punch-out time for each user each day
            $timeIn = $userAttendance->sortBy('time')->first()->time;
            $timeOut = $userAttendance->sortByDesc('time')->first()->time;

            // Determine status based on the first punch-in time
            $status = 'A'; // Default status is Absent

            if ($timeIn) {
                $status = ($timeIn > '11:00:00') ? 'L' : 'P';
            }

            // Update or create the attendance record in the attendence table
            attendence::updateOrCreate(
                [
                    'EID' => $userId,
                    'Date' => $date,
                    'Time_In' => $timeIn,
                    'Time_Out' => $timeOut,
                ],
                [
                    'Status' => $status,
                ]
            );
        });

        return response()->json(['message' => 'Attendance records processed successfully']);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $EID = $request->input('Employee_Id');
        $startDate = $request->input('From_Date');
        $endDate = $request->input('To_Date');

        $attendances = attendence::select('employees.full_name', 'attendences.Date', 'attendences.Time_In', 'attendences.Time_Out', 'attendences.Status')
            ->join('employees', 'employees.id', '=', 'attendences.EID')
            ->whereBetween('attendences.Date', [$startDate, $endDate])
            ->where('attendences.EID', $EID)
            ->get();

        return response()->json($attendances);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, attendence $attendence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(attendence $attendence)
    {
        //
    }
}
