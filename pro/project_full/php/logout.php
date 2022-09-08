<?php
session_name('AwsSession');

session_start();

if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {

    $redirect_string = "../index_proj.html";

    session_unset();
    session_destroy();
    header('Location: ' . $redirect_string);
} else {
    $redirect_string = "./login.php";
    header('Location: ' . $redirect_string);
}
