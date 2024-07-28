<?php

namespace App\Libs;

use App\Models\Stamp;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Attendance{
    public static function attendanceStatusCheck() {
        $user_id = Auth::id();
        $dt_now = Carbon::now();
        $cond = ['user_id' => $user_id, 'stamp_date' => $dt_now->isoFormat('YYYY/MM/DD')];
        $stamp = Stamp::where($cond)->first();
        /*
        <$item['status']>
        1 : 勤務可能
        2 : 勤務中
        3 : 休憩中
        4 : 勤務済
        */
        if ($stamp === null) {
            $cond = ['user_id' => $user_id, 'end_work' => null];
            $stamp = Stamp::where($cond)->first();
            if ($stamp === null) {
                $item['status'] = 1;
                //勤務可能
            } else {
                $cond = ['stamp_id' => $stamp->id, 'end_rest' => null];
                $rest = Rest::where($cond)->first();
                if ($rest === null) {
                    $item['status'] = 2;
                    //勤務中
                } else {
                    $item['status'] = 3;
                    //休憩中
                }
            }
        } else {
            if ($stamp->end_work === null) {
                $cond = ['stamp_id' => $stamp->id, 'end_rest' => null];
                $rest = Rest::where($cond)->first();
                if ($rest === null) {
                    $item['status'] = 2;
                    //勤務中
                } else {
                    $item['status'] = 3;
                }
            } else {
                $item['status'] = 4;
                //勤務済
            }
        }
        $item['message'] = null;
        return $item;
    }

    public static function restCalculation($start, $end, $total){
        $start_rest = new Carbon($start);
        $end_rest = new Carbon($end);
        $total_rest = new Carbon($total);
        $total_minute = $total_rest->hour * 60 + $total_rest->minute + $start_rest->diffInMinutes($end_rest);
        $result = floor(strval($total_minute / 60)) . ":" . $total_minute % 60;
        return $result;
    }

    public static function workCalculation($start, $end, $rest)
    {
        $start_work = new Carbon($start);
        $end_work = new Carbon($end);
        $total_rest = new Carbon($rest);
        $total_minute = $start_work->diffInMinutes($end_work) - $total_rest->hour * 60 - $total_rest->minute;
        $result = sprintf('%02d', floor(strval($total_minute / 60))) . ":" . sprintf('%02d', $total_minute % 60);
        return $result;
    }
}
