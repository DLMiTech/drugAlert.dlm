<?php
include '../private/config/init.php';
if (!isset($_SESSION['user_id'])){
    header('Location: ../index');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Drug Alert</title>
    <link rel="shortcut icon" href="../assets/images/logo.webp" type="image/x-icon">
    <link rel="stylesheet" href="../assets/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="header">
    <section class="header-flex">
        <div style="display: flex; align-items: center; gap: 30px">
            <div class="" style="display:flex; align-items: center">
                <div class="logo me-3">
                    <img src="../assets/images/logo.webp" alt="" style="width: 2.5rem;">
                </div>
                <div class="name">
                    <p class="m-0 text-uppercase fw-bold">Drug Alert</p>
                </div>
            </div>

        </div>

        <div id="menu-btn" class="open-close-menu">
            <span><i class="bi bi-list-ul"></i></span>
        </div>
    </section>
</header>


<?php
include "sidebar.php";
?>