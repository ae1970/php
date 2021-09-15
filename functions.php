<?php
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=myproject', 'root', 'root');
} catch (PDOException $e) {
    print_r($e);
}

function get_user_by_email($email)
{ // поиск польз-ля по email
    global $pdo;

    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function add_user($email, $password)
{ // add user to database
    global $pdo;
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "email" => $email,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ]);
    // возвращаем id добавленного польз-ля
    return $pdo->lastInsertId();
}

function display_flash_message($name)
{
    if (isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]);
    };
}

;

function display_flash_success($profile)
{
    // Профиль успешно обновлен.
    if (isset($_SESSION[$profile])) {
        echo $_SESSION[$profile];
        unset($_SESSION[$profile]);
    }
}

;
function set_flash_message($name, $message)
{
    $_SESSION[$name] = $message;
}

function redirect_to($path)
{
    header("Location: /$path");
}

;

function auth($email, $password)
{

    global $pdo;

    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $hash = $user['password'];

    if (isset($user)) {

        $_SESSION["user"] = $user;

        return true;

    } else {
        // Данных нет!
        return false;
    }

} // authorization function

function get_status($stat)
{ // Вывод текущего статуса пользователя
    if ($stat == 'online') $status = "success"; // онлайн
    if ($stat == 'busy') $status = "warning"; // занят
    if ($stat == 'offline') $status = "danger"; // отошёл
    return $status;
}

function get_list_users()
{ // получаем список пользователей из БД
    $sql = "SELECT * FROM users JOIN info ON (info.user_id = users.id) JOIN media ON (media.media_id = users.id)";
    global $pdo;
    $statement = $pdo->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function edit_information($user_id, $name, $workplace, $phone, $address)
{
    global $pdo;
    $sql = "INSERT INTO info (user_id, name, workplace, phone, address) VALUES (:user_id, :name, :workplace, :phone, :address)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "user_id" => $user_id,
        "name" => $name,
        "workplace" => $workplace,
        "phone" => $phone,
        "address" => $address
    ]);

    return $pdo->lastInsertId();
}

function upload_avatar($user_id, $avatar)
{
    $uploaddir = 'img/demo/avatars/';
    // загружаем файл аватара на сервер
    if (isset($_FILES['avatar'])) {

    }
    $uploadfile = $uploaddir . basename($user_id . '-avatar-' . $avatar['name']);
    // dirname(__FILE__)
    if (file_exists($uploadfile)) {
        //удаляем прежний файл аватара, если такой сущеcтвует, связанный с $user_id
        unlink($uploadfile);
    }
    move_uploaded_file($avatar['tmp_name'], $uploadfile);
    return basename($user_id . '-avatar-' . $avatar['name']);

}

function set_avatar($user_id, $filename)
{ // пишем имя файла аватара в БД
    global $pdo;
    $sql = "INSERT INTO media (media_id, avatar) VALUES (:media_id, :avatar)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "media_id" => $user_id,
        "avatar" => $filename
    ]);
}

function set_status($user_id, $status)
{ // установим онлайн-статус пользователя
    global $pdo;
    $sql = "UPDATE `media` SET `status` = :status WHERE `media`.`media_id` = :user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "status" => $status,
        "user_id" => $user_id
    ]);
}

function add_social_links($user_id, $vk, $telegram, $insta)
{ // добавим ссылки на соц.сети
    global $pdo;

    $sql = "UPDATE `media` SET `vk` = :vk, `telegram` = :telegram, `insta` = :insta WHERE `media`.`media_id` = :user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'vk' => $vk,
        'telegram' => $telegram,
        'insta' => $insta,
        'user_id' => $user_id
    ]);

}

?>