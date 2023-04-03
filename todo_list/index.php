<?php
session_start();
// показывать или нет выполненные задачи
$show_complete_tasks = 1;

require_once "helpers.php";
require_once "config.php";

// SQL-запрос для получения списка проектов у текущего пользователя
if(isset($_SESSION['user'])) {
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
    }

    // SQL-запрос для получения списка из всех задач у текущего пользователя
    $sql = "SELECT t.id, t.name, t.dt_deadline, t.status_ext, t.project_id, t.file_path FROM tasks t JOIN users u ON t.user_id = u.id WHERE u.id = ?";
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

    // Показываем задачи, которые относятся к конкретному проекту
    if(isset($_GET['project_id'])) { // проверяем, что был запрос на показ задач конкретного проекта
        $project_id = $_GET['project_id'];

        // Защита от SQL-инъекции с помощью подготовленного выражения
        $sql = "SELECT id, name, dt_deadline, status_ext, project_id, file_path FROM tasks WHERE project_id = ? AND user_id = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$project_id, $user_id]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(!$result) {
            $error = mysqli_error($link);
            print("Ошибка SQL-запроса на чтение: " . $error);
        }
        else { 
            $tasks_list_for_project = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }
    else {
        $tasks_list_for_project = $tasks_list;
    }

    // Поиск задач
    $search = $_GET['q'] ?? '';
    if($search) {
        $search = trim($search);
        $sql = "SELECT id, name, dt_deadline, status_ext, project_id, file_path FROM tasks WHERE MATCH(name) AGAINST(?) and user_id = ?";
        
        $stmt = db_get_prepare_stmt($link, $sql, [$search, $user_id]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if($result) {
            $tasks_list_search = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $tasks_list_for_project = $tasks_list_search;
        }
    }

    // Пометить задачу, как выполненную
    $task_id = $_POST['task_id'] ?? '';
    if($task_id) {
        // Получение статуса задачи по id
        $sql = "SELECT status_ext FROM tasks WHERE id = ? AND user_id = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$task_id, $user_id]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($result) { // если задача с таким id найдена
            // Получаем значение поля status_ext
            $task_status = mysqli_fetch_assoc($result);
            $status = $task_status['status_ext'] ? 0 : 1;
            // Обновляем данные в БД
            $sql = "UPDATE tasks SET status_ext = ? WHERE id = ?";
            $stmt = db_get_prepare_stmt($link, $sql, [$status, $task_id]);
            $result = mysqli_stmt_execute($stmt);

            if(!$result) {
                $error = mysqli_error($link);
                print("Ошибка SQL-запроса на чтение: " . $error);
            }
            else {
                $param = $_SERVER['QUERY_STRING'] ? ("?" . $_SERVER['QUERY_STRING']) : "";
                header("Location: index.php" . $param);
            }
        }
    }

    // Показать выполненные задачи
    if(isset($_POST['show_comleted'])) {
        $show_complete_tasks = $_POST['show_comleted'];
    }

    // Пагинация по задачам
    $tasks_switch = $_GET['switch'] ?? '';
    if($tasks_switch) {
        switch($tasks_switch) {
            case 1: 
                $sql = "SELECT id, name, dt_deadline, status_ext, project_id, file_path FROM tasks WHERE user_id = ? and dt_deadline = CURDATE()";
                break;
            case 2: 
                $sql = "SELECT id, name, dt_deadline, status_ext, project_id, file_path FROM tasks WHERE user_id = ? and dt_deadline = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
                break;
            case 3: 
                $sql = "SELECT id, name, dt_deadline, status_ext, project_id, file_path FROM tasks WHERE user_id = ? and dt_deadline < CURDATE() and status_ext = 0";
                break;
            default:
                break;
        }

        $stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(!$result) {
            $error = mysqli_error($link);
            print("Ошибка SQL-запроса на чтение: " . $error);
        }
        else {
            $tasks_list_for_project = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }
}


if(http_response_code() == 404) { // если код ответа равен 404, сообщаем о том, что страница не найдена
    print(http_response_code() . " Not Found");
    exit();
}
if(empty($_SESSION['user'])) {
    $page_content = include_template("guest.php", []);
    $layout_content = include_template("layout.php", ['page_title' => "Дела в порядке", 'page_content' => $page_content]);
    print($layout_content);
}
else { 
    $page_content = include_template("main.php", ['categories' => $categories, 'tasks_list' => $tasks_list, 'tasks_list_for_project' => $tasks_list_for_project, 'show_complete_tasks' => $show_complete_tasks]);
    $layout_content = include_template("layout.php", ['page_title' => "Дела в порядке", 'user_name' => $user_name, 'page_content' => $page_content]);
    print($layout_content);
}
