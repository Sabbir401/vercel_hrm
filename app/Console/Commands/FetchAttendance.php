<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\attendence;
use App\Models\zkt_attendence;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Console\Command;
use App\Models\zkteco_attendence;

class FetchAttendance extends Command
{
    protected $signature = 'attendance:fetch';
    protected $description = 'Fetch attendance data from ZKTeco K40';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $zk = new ZKTeco(config('zkteco.ip'), config('zkteco.port'));

        if ($zk->connect()) {
            $attendanceData = $zk->getAttendance();
            foreach ($attendanceData as $attendance) {
                $timestamp = Carbon::parse($attendance['timestamp']);
                $date = $timestamp->toDateString();
                $time = $timestamp->toTimeString();

                // Check if the record already exists
                $existingAttendance = zkt_attendence::where('user_id', $attendance['id'])
                    ->where('date', $date)
                    ->where('time', $time)
                    ->first();
                if (!$existingAttendance) {
                    zkt_attendence::create([
                        'user_id' => $attendance['id'],
                        'date' => $date,
                        'time' => $time,
                    ]);
                }
            }
            $zk->disconnect();
            $this->info('Attendance data fetched successfully.');
        } else {
            $this->error('Unable to connect to the device.');
        }
    }
}
