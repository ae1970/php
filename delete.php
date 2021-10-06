<?php
session_start();
include_once 'functions.php';
$user = $_SESSION["user"];
$user_id = $_GET['id'];
$info = get_status_media($user_id);
$filename = "img/demo/avatars/" . $info['avatar']; // путь к файлу аватара
unlink($filename); // delete the avatar
if (!isset($_SESSION["user"])) { // пользователь не авторизован!!!
    set_flash_message("danger", "Вы не авторизованы! Авторизуйтесь!");
    header("Location: /page_login.php");
    exit();
}
if ($user['role'] == 'admin') {
    if ($user['id'] != $user_id) { // admin удаляет другой аккаунт
        delete($user_id);
        set_flash_message("profile",
            "Аккаунт удален!");
        header("Location: /users.php");
    } else { // admin удаляет свой аккаунт
        delete($user_id);
        log_out();
    }

}
if ($user['role'] != 'admin') {
    // не админ
    if ($user['id'] != $user_id) {
        // и вы удаляете не свой аккаунт!
        set_flash_message("profile",
            "Вы можете удалять только свой аккаунт!");
        header("Location: /users.php");
    }
    // пользователь удаляет cвой аккаунт!";
    delete($user_id);
    log_out();
}

?>