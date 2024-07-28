<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stamp;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Libs\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
        $item = Attendance::attendanceStatusCheck();
        return view('index', $item);
    }
    //打刻ページの表示

    public function startWork()
    {
        $item = Attendance::attendanceStatusCheck();
        if ($item['status'] === 1) {
            $dt_now = Carbon::now();
            $stamp_array = [
                'user_id' => Auth::id(),
                'start_work' => $dt_now->isoFormat('HH:mm'),
                'end_work' => null,
                'total_rest' => 0,
                'stamp_date' => $dt_now->isoFormat('YYYY-MM-DD')
            ];
            Stamp::create($stamp_array);
            $item = Attendance::attendanceStatusCheck();
        } else {
            $item['message'] = "只今の処理は無効です。勤怠を確認してください。";
        }
        return view('index', $item);
    }
    //勤務開始

    public function endWork()
    {
        $item = Attendance::attendanceStatusCheck();
        if ($item['status'] === 2) {
            $dt_now = Carbon::now();
            $cond = ['user_id' => Auth::id(), 'end_work' => null];
            $stamp = Stamp::where($cond)->first();
            $stamp_array = $stamp->attributesToArray();
            $diff = $dt_now->diffInDays($stamp_array['stamp_date']);
            $dt = new Carbon($stamp_array['stamp_date']);
            if ($diff === 0) {
                $stamp_array['end_work'] = $dt_now->isoFormat('HH:mm');
                Stamp::find($stamp_array['id'])->update($stamp_array);
            } else {
                for ($i = 0; $i < $diff; $i++) {
                    $stamp_array['end_work'] = "23:59";
                    Stamp::find($stamp_array['id'])->update($stamp_array);
                    $stamp_array = [
                        'user_id' => Auth::id(),
                        'start_work' => "00:00",
                        'end_work' => null,
                        'total_rest' => 0,
                        'stamp_date' => $dt->addDay()->isoFormat('YYYY-MM-DD')
                    ];
                    $stamp = Stamp::create($stamp_array);
                    $stamp_array = $stamp->attributesToArray();
                }
                $stamp_array['end_work'] = $dt_now->isoFormat('HH:mm');
                Stamp::find($stamp_array['id'])->update($stamp_array);
            }
            $item = Attendance::attendanceStatusCheck();
        } else {
            $item['message'] = "只今の処理は無効です。勤怠を確認してください。";
        }
        return view('index', $item);
    }
    //勤務終了

    public function startRest()
    {
        $item = Attendance::attendanceStatusCheck();
        if ($item['status'] === 2) {
            $dt_now = Carbon::now();
            $cond = ['user_id' => Auth::id(), 'end_work' => null];
            $stamp = Stamp::where($cond)->first();
            $stamp_array = $stamp->attributesToArray();
            $diff = $dt_now->diffInDays($stamp_array['stamp_date']);
            $dt = new Carbon($stamp_array['stamp_date']);
            if ($diff === 0) {
                $rest_array = [
                    'stamp_id' => $stamp_array['id'],
                    'start_rest' => $dt_now->isoFormat('HH:mm'),
                    'end_rest' => null
                ];
                Rest::create($rest_array);
            } else {
                for ($i = 0; $i < $diff; $i++) {
                    $stamp_array['end_work'] = "23:59";
                    Stamp::find($stamp_array['id'])->update($stamp_array);
                    $stamp_array = [
                        'user_id' => Auth::id(),
                        'start_work' => "00:00",
                        'end_work' => null,
                        'total_rest' => 0,
                        'stamp_date' => $dt->addDay()->isoFormat('YYYY-MM-DD')
                    ];
                    $stamp = Stamp::create($stamp_array);
                    $stamp_array = $stamp->attributesToArray();
                }
                $rest_array = [
                    'stamp_id' => $stamp_array['id'],
                    'start_rest' => $dt_now->isoFormat('HH:mm'),
                    'end_rest' => null
                ];
                Rest::create($rest_array);
            }
            $item = Attendance::attendanceStatusCheck();
        } else {
            $item['message'] = "只今の処理は無効です。勤怠を確認してください。";
        }
        return view('index', $item);
    }
    //休憩開始

    public function endRest()
    {
        $item = Attendance::attendanceStatusCheck();
        if ($item['status'] === 3) {
            $dt_now = Carbon::now();
            $cond = ['user_id' => Auth::id(), 'end_work' => null];
            $stamp = Stamp::where($cond)->first();
            $stamp_array = $stamp->attributesToArray();
            $diff = $dt_now->diffInDays($stamp_array['stamp_date']);
            $dt = new Carbon($stamp_array['stamp_date']);
            $cond = ['stamp_id' => $stamp_array['id'], 'end_rest' => null];
            $rest = Rest::where($cond)->first();
            $rest_array = $rest->attributesToArray();
            if ($diff === 0) {
                $rest_array['end_rest'] = $dt_now->isoFormat('HH:mm');
                Rest::find($rest_array['id'])->update($rest_array);
                $stamp_array['total_rest'] = Attendance::restCalculation($rest_array['start_rest'], $rest_array['end_rest'], $stamp_array['total_rest']);
                Stamp::find($stamp_array['id'])->update($stamp_array);
            } else {
                for ($i = 0; $i < $diff; $i++) {
                    $rest_array['end_rest'] = "23:59";
                    Rest::find($rest_array['id'])->update($rest_array);
                    $stamp_array['total_rest'] = Attendance::restCalculation($rest_array['start_rest'], $rest_array['end_rest'], $stamp_array['total_rest']);
                    $stamp_array['end_work'] = "23:59";
                    Stamp::find($stamp_array['id'])->update($stamp_array);
                    $stamp_array = [
                        'user_id' => Auth::id(),
                        'start_work' => "00:00",
                        'end_work' => null,
                        'total_rest' => 0,
                        'stamp_date' => $dt->addDay()->isoFormat('YYYY-MM-DD')
                    ];
                    $stamp = Stamp::create($stamp_array);
                    $stamp_array = $stamp->attributesToArray();
                    $rest_array = [
                        'stamp_id' => $stamp_array['id'],
                        'start_rest' => "00:00",
                        'end_rest' => null
                    ];
                    $rest = Rest::create($rest_array);
                    $rest_array = $rest->attributesToArray();
                }
                $rest_array['end_rest'] = $dt_now->isoFormat('HH:mm');
                Rest::find($rest_array['id'])->update($rest_array);
                $stamp_array['total_rest'] = Attendance::restCalculation($rest_array['start_rest'], $rest_array['end_rest'], $stamp_array['total_rest']);
                Stamp::find($stamp_array['id'])->update($stamp_array);
            }
            $item = Attendance::attendanceStatusCheck();
        } else {
            $item['message'] = "只今の処理は無効です。勤怠を確認してください。";
        }
        return view('index', $item);
    }
    //休憩終了

    public function atteRecord(Request $request)
    {
        if ($request->date_status == "back") {
            $dt = new Carbon($request->date);
            $date = $dt->subDay()->isoFormat('YYYY-MM-DD');
        } elseif ($request->date_status == "next") {
            $dt = new Carbon($request->date);
            $date = $dt->addDay()->isoFormat('YYYY-MM-DD');
        } elseif ($request->page) {
            $date = $request->date;
        } else {
            $dt = Carbon::now();
            $date = $dt->isoFormat('YYYY-MM-DD');
        }
        $cond = ['stamp_date' => $date];
        $stamps = Stamp::where($cond)->with('user')->Paginate(5)->onEachSide(3);
        foreach ($stamps as $stamp) {
            if ($stamp->end_work != null) {
                $stamp['total_work'] = Attendance::workCalculation($stamp->start_work, $stamp->end_work, $stamp->total_rest);
            }
        }
        return view('attendance', compact('stamps', 'date'));
    }
    //日付別勤怠ページの表示

    public function userRecord(Request $request)
    {
        $users = User::Paginate(5)->onEachSide(3);
        $i = 0;
        foreach ($users as $user) {
            $user['number'] = $users->firstItem() + $i;
            $user['last_date'] = $user->stamps()->latest('stamp_date')->first()->stamp_date;
            $i += 1;
        }
        return view ('user_attendance', compact('users'));
    }
    //ユーザー一覧の表示

    public function monthRecord(Request $request)
    {
        if ($request->date_status == "back") {
            $dt = new Carbon($request->date);
            $dt_first = $dt->subMonth()->startOfMonth()->isoFormat('YYYY-MM-DD');
            $dt_end = $dt->endOfMonth()->isoFormat('YYYY-MM-DD');
            $period = CarbonPeriod::create($dt_first, $dt_end)->toArray();
        } elseif ($request->date_status == "next") {
            $dt = new Carbon($request->date);
            $dt_first = $dt->addMonth()->startOfMonth()->isoFormat('YYYY-MM-DD');
            $dt_end = $dt->endOfMonth()->isoFormat('YYYY-MM-DD');
            $period = CarbonPeriod::create($dt_first, $dt_end)->toArray();
        } else {
            $dt = Carbon::now();
            $dt_first = $dt->startOfMonth()->isoFormat('YYYY-MM-DD');
            $dt_end = $dt->endOfMonth()->isoFormat('YYYY-MM-DD');
            $period = CarbonPeriod::create($dt_first, $dt_end)->toArray();
        }
        if ($request->id) {
            $id = $request->id;
            $cond = ['user_id' => $id];
        } else {
            $id = Auth::id();
            $cond = ['user_id' => $id];
        }
        $stamps = Stamp::where($cond)->whereBetween('stamp_date', [$dt_first, $dt_end])->with('user')->get();
        foreach ($stamps as $stamp) {
            if ($stamp->end_work != null) {
                $stamp['total_work'] = Attendance::workCalculation($stamp->start_work, $stamp->end_work, $stamp->total_rest);
            }
        }
        return view ('month_attendance', compact('stamps', 'period', 'id'));
    }
}
