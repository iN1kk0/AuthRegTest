<?php

//con db
include "db/config.php";

if (isset($_POST['login'])) {
    $errors = '';

    //Check login/password 
    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) {
        $errors .= "Only english and numbers, please (login)<br />";
    }

    if (strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30) {
        $errors .= "At least three characters and no more than thirty (login)<br />";
    }

    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])) {
        $errors .= "Only english and numbers, please (password)<br />";
    }

    if (strlen($_POST['password']) < 3 or strlen($_POST['password']) > 30) {
        $errors .= "At least three characters and no more than thirty (password)<br />";
    }

    $query = mysql_query("SELECT COUNT(id) FROM users WHERE login='" . mysql_real_escape_string($_POST['login']) . "'") or die("<br>Invalid query: " . mysql_error());
    if (mysql_result($query, 0) > 0) {
        $errors .= "User exists<br />";
    }

    if (!empty($errors)) {
        $err = $errors;
        $success = "fail";
    } else {
        $login = $_POST['login'];
        $password = md5(md5(trim($_POST['password'])));

        mysql_query("INSERT INTO users SET login='" . $login . "', password='" . $password . "'");
        $success = "ok";
    }
    $result = array("login" => $_POST['login'], "errors" => $err, "success" => $success);
    echo json_encode($result);
}
?>
