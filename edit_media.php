<?php
session_start();
require_once('functions.php');
if (!isset($_POST['submit'])) exit(); // признак, что не было отправки формы
$user_id = $_SESSION["media_id"];
if (!isset($_SESSION["user"])) { // пользователь не авторизован!!
    set_flash_message("danger", "Вы не авторизованы! Авторизуйтесь!");
    header("Location: /page_login.php");
    exit();
}
$avatar = $_FILES['avatar'];
$filename = upload_avatar($user_id, $avatar);
update_avatar($user_id, $filename);
set_flash_message('profile', 'Аватар изменён!');
header("Location: /users.php");
exit();

?>