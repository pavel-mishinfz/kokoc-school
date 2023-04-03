<?php
session_start();
require_once "helpers.php";
require_once "config.php";

if(isset($_SESSION['user'])) {
    // SQL-запрос для получения списка проектов у текущего пользователя
    $user_id = $_SESSION['user'];
    $sql = "SELECT p.id, p.name FROM projects p JOIN users u ON p.user_id = u.id WHERE u.id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(!$result) {
        $error = mysqli_error($link);
        print("Ошибка SQL-запроса на чтение: " . $error);
    }
    else {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $categories_id = array_column($categories, 'id');
    }

    // SQL-запрос для получения списка из всех задач у текущего пользователя
    $sql = "SELECT t.name, t.dt_deadline, t.status_ext, t.project_id FROM tasks t JOIN users u ON t.user_id = u.id WHERE u.id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(!$result) {
        $error = mysqli_error($link);
        print("Ошибка SQL-запроса на чтение: " . $error);
    }
    else {
        $tasks_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Запрос на получение имени пользователя
    $sql = "SELECT name FROM users WHERE id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(!$result) {
        $error = mysqli_error($link);
        print("Ошибка SQL-запроса на чтение: " . $error);
    }
    else {
        $user = mysqli_fetch_assoc($result);
    }

    $user_name = $user['name'];

    if($_SERVER['REQUEST_METHOD'] == 'POST') { // если для доступа к файлу был произведен метод POST
        foreach($_POST as $key => $value) {
            $form_task[$key] = esc($value);
        }

        $required = ['name', 'project']; // обязательные для заполнения поля
        $errors = []; // массив с ошибками

        // Правила валидации для каждого поля
        $rules = [
            'name' => function() {
                return validateFilled('name');
            },
            'project' => function() use ($categories_id) {
                return validateCategory('project', $categories_id);
            },
            'date' => function() {
                return validateDate('date');
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

        if(isset($_FILES['file']['name'])) {
            $current_path = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $new_path = 'uploads/' . $filename;
            move_uploaded_file($current_path, $new_path);
            $form_task['file'] = $filename;
        }

        if(count($errors)) {
            $page_content = include_template("form-task.php", ['categories' => $categories, 'tasks_list' => $tasks_list, 'errors' => $errors, 'form_task' => $form_task]);
        }
        else {
            // Защита от SQL-инъекции с помощью подготовленного выражения
            $sql = "INSERT INTO tasks (name, dt_add, status_ext, project_id, dt_deadline, user_id, file_path) VALUES (?, NOW(), 0, ?, ?, '$user_id', ?)";
            $stmt = db_get_prepare_stmt($link, $sql, $form_task);
            $result = mysqli_stmt_execute($stmt);

            if(!$result) {
                $error = mysqli_error($link);
                print("Ошибка SQL-запроса на добавление: " . $error);
            }
            else { 
                header("Location: index.php");
            }
        }
    }
    else {
        $page_content = include_template("form-task.php", ['categories' => $categories, 'tasks_list' => $tasks_list]);
    }
    $layout_content = include_template("layout.php", ['page_title' => "Дела в порядке", 'user_name' => "$user_name", 'page_content' => $page_content]);
    print($layout_content);
}
http_response_code(404);
print(http_response_code() . ' Not Found');

