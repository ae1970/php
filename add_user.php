<?php
session_start();
require_once('functions.php');
$email = $_POST['email'];
$password = $_POST['password'];
global $pdo;
$user = get_user_by_email($email);

if (!empty($user)) {
    set_flash_message("danger", "Такой email занят, выберите другой");
    redirect_to("create_user.php");
    exit();
}
if ($password) {
    // set_flash_message("success", "Пользователь создан, добавьте информацию");
    $user_id = add_user($email, $password);

    // add to table info
    $name = $_POST['name'];
    $workplace = $_POST['workplace'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $avatar = $_FILES['avatar'];
    $vk = $_POST['vk'];
    $telegram = $_POST['telegram'];
    $insta = $_POST['insta'];

    edit_information($user_id, $name, $workplace, $phone, $address);
    $filename = upload_avatar($user_id, $avatar); // загружаем файл аватара
    // add to table media
    set_avatar($user_id, $filename);              // записываем имя файла аватара в БД
    set_status($user_id, $status);
    add_social_links($user_id, $vk, $telegram, $insta); // записываем ссылки на соц.сети
    set_flash_message('profile', 'Данные пользователя добавлены!'); // we send the message to users.php
    redirect_to("users.php");
    exit();
}



