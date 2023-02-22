<?php
date_default_timezone_set("Europe/Moscow");
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name; // Создание строки с путем к файлу шаблона относительно папки templates
    $result = ''; // Итоговый HTML

    if (!is_readable($name)) { // Определяем существование файла и доступен ли он для чтения
        return $result; // В случае неудачи возвращаем пустую строку
    }

    ob_start(); // Включение буферизации вывода
    extract($data); // Импортируем переменные из массива в текущую таблицу символов 
                    // и если переменная с таким именем существует, она будет перезаписана, т.к. второй параметр отсутсвует
    require $name; // Подключаем шаблон

    $result = ob_get_clean(); // Получаем содержимое текущего буфера и удаляем его

    return $result; // Возвращаем HTML
}

// Функция подсчета количества задач в проекте
function funTaskCount($tasks_list, $category) {
    $count_tasks = 0;
    foreach($tasks_list as $task) {
        if($task["project_id"] == $category) {
            ++$count_tasks;
        }
    }
    return $count_tasks;
}

// Функция для напоминания даты выполнения задачи
function funTaskDeadline($task) {
    if($task["dt_deadline"] == null) {
        return false;
    }
    $cur_date = strtotime("now");
    $deadline_date = strtotime($task["dt_deadline"]);
    
    if($deadline_date < $cur_date) {
        return true;
    }    
    else {
        if((($deadline_date - $cur_date)/3600) <= 24) {
            return true;
        }
    }
    return false;
}

// Функция сохранения данных из формы
function getPostVal($name) {
    return $_POST[$name];
}

// Функции для валидации полей формы добавления задачи
function validateFilled($name) {
    if(empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }
}

function validateCategory($name, $allowed_list) {
    $category = $_POST[$name];
    
    if(!in_array($category, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

function validateDate($name) {
    if($_POST[$name] != null) {
        if(!is_date_valid($name) && $_POST[$name] < date('Y-m-d')) {
            return "Дата должна быть больше либо равна текущей и в формате ГГГГ-ММ-ДД";
        }
    }
    return null;
}

// Функция защиты данных от XSS 
function esc($str) {
    $text = strip_tags($str);
    return $text;
}

// Функция для валидации email при регистрации
function validateEmail($name) {
    if(filter_var($_POST[$name], FILTER_VALIDATE_EMAIL) === false) {
        return "E-mail введён некорректно";
    }
    return null;
}