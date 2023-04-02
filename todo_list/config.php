<?php
// Подключение к БД
$link = mysqli_connect("localhost","root", "","todo_list");
if (!$link){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    exit();
}
mysqli_set_charset($link, "utf8");
?>