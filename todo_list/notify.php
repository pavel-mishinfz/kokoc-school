<?php
require_once __DIR__ . "/vendor/autoload.php"; 

$link = mysqli_connect("localhost", "root", "", "todo_list");
if (!$link) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
} 
else {
    // print("Соединение установлено успешно!");
    mysqli_set_charset($link, "utf8");

    $sql = "SELECT id FROM users"; // находим всех пользователей

    $res = mysqli_query($link, $sql);

    if($res && mysqli_num_rows($res)) {
        $users = mysqli_fetch_all($res, MYSQLI_ASSOC);

        foreach($users as $user) { // перебираем всех пользователей
            $user_id = $user['id'];
            // Находим пользоавтеля у которого есть задача на текущую дату
            $sql = "SELECT name, dt_deadline FROM tasks WHERE user_id = '$user_id' and status_ext = 0 and dt_deadline = CURDATE()";

            $res = mysqli_query($link, $sql);

            if($res && mysqli_num_rows($res)) {
                $tasks_deadline = mysqli_fetch_all($res, MYSQLI_ASSOC);
                // Получаем данные пользователя
                $sql = "SELECT name, email FROM users WHERE id = '$user_id'";

                $res = mysqli_query($link, $sql);

                if($res && mysqli_num_rows($res)) {
                    $cnt = 0;
                    $user_data = mysqli_fetch_all($res, MYSQLI_ASSOC);

                    $str_for_message = "Уважаемый, " . $user_data[0]['name'] . "." . " У вас запланирована задача ";
                    if(count($tasks_deadline) > 1) {
                        foreach($tasks_deadline as $elem) {
                            $str_for_message .= "\"" . $elem['name'] . "\"" . " на " . $elem['dt_deadline'] . ", ";
                        }
                        $str_for_message = substr_replace($str_for_message,'. ',-2);
                    }
                    else {
                        $str_for_message .= "\"" . $tasks_deadline[0]['name'] . "\"" . " на " . $tasks_deadline[0]['dt_deadline'] . ".";
                    }
                    
                    // Отправляем письмо
                    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587,'tls'))
                    ->setUsername('pavel.mishinfz@gmail.com')
                    ->setPassword('yspusfrervavivth');
                    
                    $message = new Swift_Message("Уведомление от сервиса «Дела в порядке»");
                    $message->setFrom(["keks@phpdemo.ru" => "Дела в порядке"]);
                    $message->setTo([$user_data[0]['email'] => $user_data[0]['name']]);
                    $message->setBody($str_for_message);
                    
                    $mailer = new Swift_Mailer($transport);
                    $mailer->send($message);
                }
            }
        }
    }   
}
?>
