<?php
session_start();
unset($_SESSION["user"]);


header("Location: http://localhost/php_p.mishin/lesson_08/index.php");