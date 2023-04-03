<?php
session_start();
require_once "helpers.php";
require_once "config.php";

if($_SERVER['REQUEST_METHOD'] == 'POST') { // если для доступа к файлу был произведен метод POST
    foreach($_POST as $key => $value) {
        $auth[$key] = esc($value);
    }
    
    $required = ['email', 'password']; // обязательные для заполнения поля
    $errors = []; // массив с ошибками

    // Правила валидации для каждого поля
    $rules = [
        'email' => function() {
            return validateEmail('email');
        },
        'password' => function() {
            return validateFilled('password');
        }
    ];

    // Валидация всех полей (проверка корректности введенных данных)
    foreach($_POST as $key => $value) {
        if(isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    // Для каждого обязательного поля проверка на заполненность
    foreach($required as $key) {
        if(empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    // Защита от SQL-инъекции с помощью подготовленного выражения
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$auth['email']]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(!count($errors) && $result) { // если все поля заполнены верно и пользователь найден в БД, то проверяем пароль
        $user = mysqli_fetch_assoc($result);
        if(isset($user['password']) && password_verify($auth['password'], $user['password'])) {
            $_SESSION['user'] = $user['id'];
        }
        else {
            $errors['err'] = "Вы ввели неверный email/пароль";
        }
    }
    else {
        $errors['err'] = "Вы ввели неверный email/пароль";
    }

    if(count($errors)) {
        $page_content = include_template("auth.php", ['errors' => $errors]);
    }
    else {
        header("Location: index.php");
        exit();
    }
}
else {
    $page_content = include_template("auth.php", []);
}

if(isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$layout_content = include_template("layout.php", ['page_title' => "Дела в порядке", 'page_content' => $page_content]);
print($layout_content);