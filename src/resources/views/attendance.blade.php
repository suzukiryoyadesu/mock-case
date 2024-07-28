@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="attendance__content">
    <div class="date__group">
        <form class="date__form" action="/attendance" method="post">
            @csrf
            <input type="hidden" name="date_status" value="back">
            <input type="hidden" name="date" value="{{ $date }}">
            <button class="date__form-button" type="submit">
                <span>&lt;</span>
            </button>
        </form>
        <span>{{ $date }}</span>
        <form class="date__form" action="/attendance" method="post">
            @csrf
            <input type="hidden" name="date_status" value="next">
            <input type="hidden" name="date" value="{{ $date }}">
            <button class="date__form-button" type="submit">
                <span>&gt;</span>
            </button>
        </form>
    </div>
    <div class=" attendance__group">
        <div class="attendance__table">
            <table>
                <tr class="attendance__table-row">
                    <th class="attendance__table-header">名前</th>
                    <th class="attendance__table-header">勤務開始</th>
                    <th class="attendance__table-header">勤務終了</th>
                    <th class="attendance__table-header">休憩時間</th>
                    <th class="attendance__table-header">勤務時間</th>
                </tr>
                @foreach($stamps as $stamp)
                <tr class="attendance__table-row">
                    <td class="attendance__table-description">{{ $stamp->user->name }}</td>
                    <td class="attendance__table-description">{{ substr($stamp->start_work,0,5) }}</td>
                    <td class="attendance__table-description">{{ substr($stamp->end_work,0,5) }}</td>
                    <td class="attendance__table-description">{{ substr($stamp->total_rest,0,5) }}</td>
                    <td class="attendance__table-description">
                        {{ $stamp->total_work }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        {{ $stamps->appends(['date'=>$date])->links('vendor.pagination.attendance') }}
    </div>
</div>
@endsection
