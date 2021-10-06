<?php
// Инициализировать сессию.
session_start();
// Unset все переменные сессии.
$_SESSION = array();
include_once('functions.php');
session_destroy(); // мочим сессию!
header("Location: /page_login.php");
exit();

