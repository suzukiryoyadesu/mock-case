@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user_attendance.css') }}">
@endsection

@section('content')
<div class="attendance__content">
    <div class="attendance__header">
        <h2>ユーザー一覧</h2>
    </div>
    <div class=" attendance__group">
        <div class="attendance__table">
            <table>
                <tr class="attendance__table-row">
                    <th class="attendance__table-header">No.</th>
                    <th class="attendance__table-header">名前</th>
                    <th class="attendance__table-header">最終打刻日</th>
                </tr>
                @foreach($users as $user)
                <tr class="attendance__table-row">
                    <td class="attendance__table-description">{{ $user->number }}</td>
                    <td class="attendance__table-description"><a href="/attendance/month/?id={{ $user->id }}">{{ $user->name }}</a></td>
                    <td class="attendance__table-description">{{ $user->last_date }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        {{ $users->links('vendor.pagination.attendance') }}
    </div>
</div>
@endsection