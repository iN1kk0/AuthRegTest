<?php

//con db
include "db/config.php";

//regi validation
if (isset($_POST['login'])) {
    $errors = '';

    //Check login/password 
    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) {
        $errors .= "Only english and numbers, please (for login)<br />";
    }

    if (strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30) {
        $errors .= "At least three characters and no more than thirty (for login)<br />";
    }

    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])) {
        $errors .= "Only english and numbers, please (for password)<br />";
    }

    if (strlen($_POST['password']) < 3 or strlen($_POST['password']) > 30) {
        $errors .= "At least three characters and no more than thirty (for password)<br />";
    }

    $query = mysql_query("SELECT COUNT(id) FROM users WHERE login='" . mysql_real_escape_string($_POST['login']) . "'") or die("<br>ERROR: " . mysql_error());
    if (mysql_result($query, 0) > 0) {
        $errors .= "User exists<br />";
    }

    if (!empty($errors)) {
        $error = $errors;
        $success = "fail";
    } else {
        $login = $_POST['login'];
        $password = md5(md5(trim($_POST['password'])));

        mysql_query("INSERT INTO users SET login='" . $login . "', password='" . $password . "'");
        $success = "ok";
    }

    $result = array("login" => $_POST['login'], "errors" => $error, "success" => $success);
    echo json_encode($result);
}

//auth valid
if (isset($_POST['name'])) {
    $errors = '';

    $user = mysql_fetch_assoc(mysql_query("SELECT id, login, password FROM `users` WHERE `login`='" . mysql_real_escape_string($_POST['name']) . "' LIMIT 1"));

    if ($user['password'] === md5(md5($_POST['pass']))) {
        setcookie("id", $user['id'], time() + 60 * 60 * 24 * 30);
        setcookie("username", $user['login'], time() + 60 * 60 * 24 * 30);
    } else {
        $errors .= "Incorrect login/password <br />";
    }

    if (!empty($errors)) {
        $error = $errors;
        $success = "fail";
    } else {
        $success = "ok";
    }

    $result = array("username" => $_POST['name'], "errors" => $error, "success" => $success);
    echo json_encode($result);
}

//exit
if (isset($_POST['exit'])) {
    setcookie("id", '');
    $result = array("test" => $_POST['exit'], "success" => 'ok');
    echo json_encode($result);
}
?>
