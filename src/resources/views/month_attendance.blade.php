@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/month_attendance.css') }}">
@endsection

@section('content')
<div class="attendance__content">
    <div class="date__group">
        <form class="date__form" action="/attendance/month" method="post">
            @csrf
            <input type="hidden" name="date_status" value="back">
            <input type="hidden" name="date" value="{{ $period[0] }}">
            <input type="hidden" name="id" value="{{ $id }}">
            <button class="date__form-button" type="submit">
                <span>&lt;</span>
            </button>
        </form>
        <span>{{ substr($period[0],0,7) }}</span>
        <form class="date__form" action="/attendance/month" method="post">
            @csrf
            <input type="hidden" name="date_status" value="next">
            <input type="hidden" name="date" value="{{ $period[0] }}">
            <input type="hidden" name="id" value="{{ $id }}">
            <button class="date__form-button" type="submit">
                <span>&gt;</span>
            </button>
        </form>
    </div>
    <div class=" attendance__group">
        <div class="attendance__table">
            <table>
                <tr class="attendance__table-row">
                    <th class="attendance__table-header">日付</th>
                    <th class="attendance__table-header">名前</th>
                    <th class="attendance__table-header">勤務開始</th>
                    <th class="attendance__table-header">勤務終了</th>
                    <th class="attendance__table-header">休憩時間</th>
                    <th class="attendance__table-header">勤務時間</th>
                </tr>
                @foreach($period as $obj)
                <tr class="attendance__table-row">
                    <td class="attendance__table-description">{{ substr($obj,0,10) }}</td>
                    @if($stamps->where('stamp_date',substr($obj,0,10))->first())
                    <td class="attendance__table-description">{{ $stamps->where('stamp_date',substr($obj,0,10))->first()->user->name }}</td>
                    <td class="attendance__table-description">{{ substr($stamps->where('stamp_date',substr($obj,0,10))->first()->start_work,0,5) }}</td>
                    <td class="attendance__table-description">{{ substr($stamps->where('stamp_date',substr($obj,0,10))->first()->end_work,0,5) }}</td>
                    <td class="attendance__table-description">{{ substr($stamps->where('stamp_date',substr($obj,0,10))->first()->total_rest,0,5) }}</td>
                    <td class="attendance__table-description">{{ substr($stamps->where('stamp_date',substr($obj,0,10))->first()->total_work,0,5) }}</td>
                    @else
                    <td class="attendance__table-description">---</td>
                    <td class="attendance__table-description">---</td>
                    <td class="attendance__table-description">---</td>
                    <td class="attendance__table-description">---</td>
                    <td class="attendance__table-description">---</td>
                    @endif
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection