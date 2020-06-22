<?php
/*ЭТО БУДУЩИЙ ФАЙЛ GAME_API , НО СЕЙЧАС ЭТО INDEX ДЛЯ ПРОВЕРКИ И ОТЛАДКИ*/


include_once 'connection_control.php';
include_once 'game_control.php';

$connection = new connection_control();
$game = new game_control(); 

//setcookie("id", "" , time() - HOUR);
//$connection->control_start(FIRST_START, "");
//$connection->control_start(NEW_GAME, "");
//$connection->control_start(NEW_GAME, "");

$connection->control_start(EXIST, "6");

?>