<?php
session_start();
require_once("functions/database_request.php");

if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    $position = $_SESSION['position'];
    $authorized =  $_SESSION['authorized'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>Signup Page 7 | | Unify - Responsive Website Template</title>

    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800">
    <!-- CSS Global Compulsory -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/icon-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/vendor/animate.css">

    <!-- CSS Unify -->
    <link rel="stylesheet" href="/assets/css/unify-core.css">
    <link rel="stylesheet" href="/assets/css/unify-components.css">
    <link rel="stylesheet" href="/assets/css/unify-globals.css">

    <!-- CSS Customization -->
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>

<body>
<main>
    <section class="g-min-height-100vh g-flex-centered g-bg-img-hero g-bg-pos-top-center" style="background-image: url(/assets/img/registr.jpg);">
        <div class="container g-py-50 g-pos-rel g-z-index-1">
            <div class="row justify-content-center u-box-shadow-v24">
                <div class="col-sm-11 col-md-10 col-lg-8">

                    <!--Alert-->
                    <?php
                    if (isset($_SESSION['success']))
                        echo '<div class="alert alert-success fade show g-bg-green rounded-0" role="alert">
                        <button type="button" class="close u-alert-close--light" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        
                        <div class="media">
                            <span class="media-body align-self-center">
                                <strong>Успех!</strong> Сотрудник был создан
                            </span>
                        </div>
                    </div>';
                    unset($_SESSION['success'])
                    ?>
                    <!--Alert-->
                    <!--Alert-->
                    <?php
                    if (isset($_SESSION['error']))
                        echo '<div class="alert alert-dismissible fade show g-bg-yellow rounded-0" role="alert">
                        <button type="button" class="close u-alert-close--light" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        
                        <div class="media">
                            <span class="media-body align-self-center">
                                <strong>Внимание!</strong> Логин занят
                            </span>
                        </div>
                    </div>';
                    unset($_SESSION['error'])
                    ?>
                    <!--Alert-->

                    <div class="g-bg-white rounded g-py-40 g-px-30">
                        <header class="text-center mb-4">
                            <h2 class="h2 g-color-black g-font-weight-600">Регистрация</h2>
                        </header>

                        <form class="g-py-15" action="/functions/registration.php" method="post">
                            <div class="mb-4">
                                <input name="login" class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="text" placeholder="ivanov">
                            </div>

                            <div class="mb-4">
                                <input name="name" class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="text" placeholder="Иванов Иван Иванович">
                            </div>


                            <div class="mb-4">
                                <input name="position" class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="text" placeholder="Должность">
                            </div>

                            <div class="mb-4">
                                <input name="password" class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="password" placeholder="Password">
                            </div>


                            <div class="mb-5 text-center">
                                    <button name="reg" class="btn btn-md u-btn-primary rounded g-py-13 g-px-25" type="submit">Регистрация</button>
                            </div>
                            <footer class="text-center">
                                <a class="g-font-weight-600" href="/admin.php">Вернуться назад</a>
                                </p>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="u-outer-spaces-helper"></div>


<!-- JS Global Compulsory -->
<script src="/assets/vendor/jquery/jquery.min.js"></script>
<script src="/assets/vendor/jquery-migrate/jquery-migrate.min.js"></script>
<script src="/assets/vendor/popper.js/popper.min.js"></script>
<script src="/assets/vendor/bootstrap/bootstrap.min.js"></script>


<!-- JS Unify -->
<script src="/assets/js/hs.core.js"></script>

<!-- JS Customization -->
<script src="/assets/js/custom.js"></script>


</body>

</html>
