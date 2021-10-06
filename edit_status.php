<?php
session_start();
require_once('functions.php');
$user_id = $_SESSION["media_id"];
$status = $_POST["status"];
set_status($user_id, $status);
set_flash_message('profile', 'Статус обновлен!');
redirect_to('users.php');
exit();

