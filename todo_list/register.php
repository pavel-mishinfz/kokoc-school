<?php

require "helpers.php";
// Подключение к БД
$link = mysqli_connect("localhost", "root", "", "todo_list");
if (!$link) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
} 
else {
    // print("Соединение установлено успешно!");
    mysqli_set_charset($link, "utf8");

    if($_SERVER['REQUEST_METHOD'] == 'POST') { // если для доступа к файлу был произведен метод POST
        $register = $_POST;

        $required = ['email', 'password', 'name']; // обязательные для заполнения поля
        $errors = []; // массив с ошибками

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

        if(empty($errors)) { // если все поля заполнены верно, то проверяем email на уникальность
            $user_email = mysqli_real_escape_string($link, $register['email']);
            $sql = "SELECT * FROM users WHERE email = '$user_email'";
            $result = mysqli_query($link, $sql);
            
            if(mysqli_num_rows($result) > 0) {
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
}
$page_content = include_template("form-register.php", ['errors' => $errors]);
$layout_content = include_template("layout.php", ['page_title' => "Дела в порядке", 'page_content' => $page_content]);
print($layout_content);
