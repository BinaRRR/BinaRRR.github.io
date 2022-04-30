<?php
    if (isset($_SESSION)) {
        header("Location: index.php");
        exit();
    }

    else if (isset($_POST['ForgotPassword'])) {
        header("Location: forgot-password.php");
        exit();
    }

    else if (isset($_POST['BackToLogin'])) {
        header("Location: login.php");
        exit();
    }

    else if (isset($_POST['CreateAccount'])) {
        header("Location: register.php");
        exit();
    }
?>