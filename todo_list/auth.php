<?php
session_start();
require_once "helpers.php";
require_once "config.php";

if($_SERVER['REQUEST_METHOD'] == 'POST') { // если для доступа к файлу был произведен метод POST
    $auth = $_POST;

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

    // Валидация всех полей
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

    $user_email = mysqli_real_escape_string($link, $auth['email']);
    $sql = "SELECT * FROM users WHERE email = '$user_email'";
    $result = mysqli_query($link, $sql);

    $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;

    if(!count($errors) && $user) { // если все поля заполнены верно и пользователь найден в БД, то проверяем пароль
        if(password_verify($auth['password'], $user['password'])) {
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

$layout_content = include_template("layout.php", ['page_title' => "Дела в порядке", 'page_content' => $page_content]);
print($layout_content);