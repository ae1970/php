<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=myproject', 'root', 'root');
}
catch (PDOException $e) {

}
function get_user_by_email($email) { // поиск польз-ля по email
    global $pdo;

    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
};
function add_user($email, $password) { // add user to database
    global $pdo;
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "email" => $email,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ]);
};
function display_flash_message($name) {
    if (isset($_SESSION[$name])) {
         echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
         unset($_SESSION[$name]);
    }
};

function display_flash_success($profile) {
   // Профиль успешно обновлен.
    if (isset($_SESSION[$profile])) {
        echo $_SESSION[$profile];
        unset($_SESSION[$profile]);
    }
};
function set_flash_message($name, $message) {
    $_SESSION[$name] = $message;
};
function redirect_to($path) {
    header("Location: /$path");
};
function auth($email, $password) {

    global $pdo;

    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $hash = $user['Password'];

    if(!empty($user) && password_verify($password, $hash)) {
        $_SESSION["user"] = $user;

        return true;
    }
        else
    {
        set_flash_message("danger","Имя или пароль неверны!");
        return false;
    }

}; // authorization function

function status($stat) { // Вывод статуса пользователя
    if ($stat == 'online') $status = "success"; // онлайн
    if ($stat == 'busy') $status = "warning"; // занят
    if ($stat == 'offline') $status = "danger"; // отошёл
    return $status;
}

function get_list_users() {
    $sql = "SELECT * FROM users JOIN info ON (info.User_id = users.Id) JOIN media ON (media.Media_id = users.Id)";
    global $pdo;
    $statement = $pdo->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>