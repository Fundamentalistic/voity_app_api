<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Voity') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}"/>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
</head>
<body>
    <div class="container-fluid fixed-top d-flex justify-content-end">
        <div class="col-3">
            <a href="/register">Регистрация</a>
            <a href="/login">Войти</a>
        </div>
    </div>
    <div class="container">
    <div class="row d-flex justify-content-center"><h1>Voity</h1></div>
    <div class="row d-flex justify-content-center"><p>Панель администратора</p></div>
    <div class="row">
    <form class="form-signin">
      
      <h1 class="h3 mb-3 font-weight-normal">Регистрация</h1>
      <label for="login" class="sr-only">Логин</label>
      <input type="text" id="login" class="form-control" placeholder="Логин" required="" autofocus="">
      <label for="inputEmail" class="sr-only">Почта</label>
      <input type="text" id="inputEmail" class="form-control" placeholder="Почта" required="" autofocus="">
      <label for="phone" class="sr-only">Номер телефона</label>
      <input type="text" id="phone" class="form-control" placeholder="Номер телефона" required="" autofocus="">
      <label for="inputPassword" class="sr-only">Пароль</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="Пароль" required="">
      <label for="confirm" class="sr-only">Подтверждение</label>
      <input type="password" id="confirm" class="form-control" placeholder="Подтверждение" required="">
      <button class="btn btn-lg btn-primary btn-block" type="submit">Далее</button>
    </form>
</div>  
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>