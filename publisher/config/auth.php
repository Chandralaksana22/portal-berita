<?php
session_start();

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function is_publisher() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'publisher';
}

function get_user_id() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

function redirect_if_not_logged_in() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>
