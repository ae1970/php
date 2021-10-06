<?php
session_start();
require_once('functions.php');

$email = $_POST['email']; // email от редактируемого пользователя
$password = $_POST['password'];
$my_email = $_SESSION["email"]; // прежний email пользователя
// ищем есть ли пользователь с таким имейлом
$user = get_user_by_email($email);
$user_id = $_SESSION["id"];
$hash_password = $_SESSION["hash_password"];

if (($user['email'] != $email) and ($password != $hash_password)) {
    edit_credentials($user_id, $email, $password);
    set_flash_message('profile', 'Данные пользователя обновлены!');
    redirect_to("users.php");

} elseif (($user['email'] != $email) and ($password == $hash_password)) {
    // Смените пароль!
    set_flash_message('danger', "Смените пароль!");
    header("Location: ".$_SERVER['HTTP_REFERER']);
} elseif (($user['email'] == $my_email) and ($password != $hash_password)) {
    edit_credentials($user_id, $email, $password);
    set_flash_message('profile', 'Данные пользователя обновлены!');
    redirect_to("users.php");
} elseif (($user['email'] == $my_email) and ($password == $hash_password)) {
    set_flash_message('danger', "Вы ничего не изменили!");
    header("Location: ".$_SERVER['HTTP_REFERER']);
} else {
    set_flash_message('danger', "Такой имейл уже занят!");
    header("Location: ".$_SERVER['HTTP_REFERER']);
}

?>
