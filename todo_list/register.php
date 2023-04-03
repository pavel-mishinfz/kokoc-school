<?php
session_start();

require_once "helpers.php";
require_once "config.php";

$errors = []; // массив с ошибками
if($_SERVER['REQUEST_METHOD'] == 'POST') { // если для доступа к файлу был произведен метод POST
    foreach($_POST as $key => $value) {
        $register[$key] = esc($value);
    }

    $required = ['email', 'password', 'name']; // обязательные для заполнения поля

    // Правила валидации для каждого поля
    $rules = [
        'email' => function() {
            return validateEmail('email');
        },
        'password' => function() {
            return validateFilled('password');
        },
        'name' => function() {
            return validateFilled('name');
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

    if(empty($errors)) { // если все поля заполнены верно, то проверяем email на уникальность
        // Защита от SQL-инъекции с помощью подготовленного выражения
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$register['email']]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result)) {
            $errors['email'] = "Пользователь с данным email уже зарегистрирован";
        }
        else {
            $register['password'] = password_hash($register['password'], PASSWORD_DEFAULT); // хэшируем пароль
            // Добавлем пользователя в БД
            $sql = "INSERT INTO users(dt_registration, email, password, name) VALUES (NOW(), ?, ?, ?)";
            $stmt = db_get_prepare_stmt($link, $sql, $register);
            $result = mysqli_stmt_execute($stmt);
        }

        if($result && empty($errors)) {
            header("Location: index.php");
            exit();
        }
    }
}
if(isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$page_content = include_template("form-register.php", ['errors' => $errors]);
$layout_content = include_template("layout.php", ['page_title' => "Дела в порядке", 'page_content' => $page_content]);
print($layout_content);
