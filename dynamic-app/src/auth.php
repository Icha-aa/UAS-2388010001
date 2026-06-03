<?php
require_once 'config.php';

function urusLogin() {
    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }
}
?>