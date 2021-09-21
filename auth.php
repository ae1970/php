<?php
session_start();
require_once('functions.php');
$email = $_POST['email'];
$password = $_POST['password'];

if (auth($email, $password)) {

    redirect_to("users.php");
    exit;
} else {

    redirect_to("page_login.php");
    exit();
}
?>