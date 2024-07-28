@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="attendance__content">
    <div class="attendance__message">
        <p>{{ Auth::user()->name }}さんお疲れ様です！</p>
        <div class="attendance__error">
            @isset($message)
            {{ $message }}
            @endisset
        </div>
    </div>
    <div class=" attendance-form__group">
        <div class="work-form__group">
            @if($status === 1)
            <form class="work-form__button" action="/startwork" method="post">
                @csrf
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <button type="submit">勤務開始</button>
            </form>
            @else
            <div class="work-form__frame">
                <span>勤務開始</span>
            </div>
            @endif
            @if($status === 2)
            <form class=" work-form__button" action="/endwork" method="post">
                @csrf
                <button>勤務終了</button>
            </form>
            @else
            <div class="work-form__frame">
                <span>勤務終了</span>
            </div>
            @endif
        </div>
        <div class="rest-form__group">
            @if($status === 2)
            <form class="rest-form__button" action="/startrest" method="post">
                @csrf
                <button>休憩開始</button>
            </form>
            @else
            <div class="rest-form__frame">
                <span>休憩開始</span>
            </div>
            @endif
            @if($status === 3)
            <form class=" rest-form__button" action="/endrest" method="post">
                @csrf
                <button>休憩終了</button>
            </form>
            @else
            <div class="rest-form__frame">
                <span>休憩終了</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
