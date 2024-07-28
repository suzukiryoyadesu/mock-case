<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>


<header>
    <div class="header__inner">
        <div class="header-utilities">
            <h1>
                Atte
            </h1>
            @if (Auth::check())
            <nav>
                <ul class="header-nav">
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/">
                            <button class="header-nav__button">ホーム</button>
                        </a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/attendance">
                            <button class="header-nav__button">日付一覧</button>
                        </a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/attendance/month">
                            <button class="header-nav__button">月一覧</button>
                        </a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/attendance/user">
                            <button class="header-nav__button">ユーザー一覧</button>
                        </a>
                    </li>
                    <li class="header-nav__item">
                        <form class="form" action="/logout" method="post">
                            @csrf
                            <button class="header-nav__button">ログアウト</button>
                        </form>
                    </li>
                </ul>
            </nav>
            @endif
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer>
    <div class="footer__inner">
        <small>Atte,inc.</small>
    </div>
</footer>

</body>

</html>
