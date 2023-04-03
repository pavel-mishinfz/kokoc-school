<?php
session_start();
require_once "helpers.php";
require_once "config.php";

if (isset($_SESSION['user'])) {
    // SQL-запрос для получения списка проектов у текущего пользователя
    $user_id = $_SESSION['user'];
    $sql = "SELECT p.id, p.name FROM projects p JOIN users u ON p.user_id = u.id WHERE u.id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка SQL-запроса на чтение: " . $error);
    } else {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $categories_name = array_column($categories, 'name');
    }

    // SQL-запрос для получения списка из всех задач у текущего пользователя
    $sql = "SELECT t.name, t.dt_deadline, t.status_ext, t.project_id FROM tasks t JOIN users u ON t.user_id = u.id WHERE u.id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка SQL-запроса на чтение: " . $error);
    } else {
        $tasks_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Запрос на получение имени пользователя
    $sql = "SELECT name FROM users WHERE id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка SQL-запроса на чтение: " . $error);
    } else {
        $user = mysqli_fetch_assoc($result);
    }

    $user_name = $user['name'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // если для доступа к файлу был произведен метод POST
        foreach ($_POST as $key => $value) {
            $form_project[$key] = esc($value);
        }

        $required = ['name']; // обязательные для заполнения поля
        $errors = []; // массив с ошибками

        // Правила валидации для каждого поля
        $rules = [
            'name' => function () use ($categories_name) {
                return validateProject('name', $categories_name);
            }
        ];

        // Валидация всех полей
        foreach ($_POST as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule();
            }
        }

        $errors = array_filter($errors);

        // Для каждого обязательного поля проверка на заполненность
        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }


        if (count($errors)) {
            $page_content = include_template("form-project.php", ['categories' => $categories, 'tasks_list' => $tasks_list, 'errors' => $errors]);
        } else {
            // Защита от SQL-инъекции с помощью подготовленного выражения
            $sql = "INSERT INTO projects (name, user_id) VALUES (?, ?)";
            $stmt = db_get_prepare_stmt($link, $sql, [$form_project['name'], $user_id]);
            $result = mysqli_stmt_execute($stmt);

            if (!$result) {
                $error = mysqli_error($link);
                print("Ошибка SQL-запроса на добавление: " . $error);
            } else {
                header("Location: index.php");
                exit();
            }
        }
    } else {
        $page_content = include_template("form-project.php", ['categories' => $categories, 'tasks_list' => $tasks_list]);
    }

    $layout_content = include_template("layout.php", ['page_title' => "Дела в порядке", 'user_name' => "$user_name", 'page_content' => $page_content]);
    print($layout_content);
}
http_response_code(404);
print(http_response_code() . ' Not Found');
