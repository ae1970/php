<?php
session_start();
require_once('functions.php');
$user_id = $_SESSION["user_id"];

$name = $_POST['name'];
$workplace = $_POST['workplace'];
$phone = $_POST['phone'];
$address = $_POST['address'];

edit_information($user_id, $name, $workplace, $phone, $address);
set_flash_message('profile', 'Данные пользователя обновлены!'); // we send the message to users.php
redirect_to("users.php");
exit();

?>

