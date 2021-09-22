@extends('layouts.app')

@section('content')
<!--
1) Создание\Удаление места
2) Создание\Удаление акций
3) Создание меню
4) Создание разметки места
5) добавление места на карту
6) Создание\Удаление пользователей
7) Оплата
-->
  
<div id="app" class="container-fluid">
  <div class="row navigation">
    <div class="comname m-2 pl-2 col-2 d-flex justify-content-center">Company name</div>
    <div class="col-9 d-flex justify-content-end">
      <div class="dropdown">
        <button class="usercomponent dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Username</button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
          <button class="dropdown-item" type="button">Настройки</button>
          <button class="dropdown-item" type="button">Кабинет</button>
          <div class="dropdown-divider"></div>
          <button class="dropdown-item" type="button">Выйти</button>
        </div>
      </div>
  </div>
  <div class="row second_line">
      <div class="col-md-3 col-lg-2 left_panel">
        <div class="admincomponents container-fluid">
          <button class="col-12">Места</button>
          <button class="col-12">Акции</button>
          <button class="col-12">Меню</button>
          <button class="col-12">Пользователи</button>
          <button class="col-12">Баланс</button>
        </div>
      </div>
      <div class="p-0 col-md-9 col-lg-10 content_panel">
        <table class="table">
          <thead class="thead-secondary">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Адрес</th>
              <th scope="col">Название</th>
              <th scope="col">Посетителей</th>
              <th scope="col">Доход</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Адрес 1</td>
              <td>Шаурма фром джигид</td>
              <td>1589</td>
              <td>1927849872</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Адрес 2</td>
              <td>Ростикс</td>
              <td>1726</td>
              <td>455345</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td>Адрес 2</td>
              <td>макдак</td>
              <td>1786</td>
              <td>455234652345</td>
            </tr>
          </tbody>
        </table>
      </div>
  </div>
</div>

<script src="/js/admin.js"></script>
@endsection