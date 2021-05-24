<?php
session_start();
require_once("functions/database_request.php");
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <!-- Title -->
    <title>Авторемонт</title>

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
    <!-- Login -->
    <section class="g-height-100vh d-flex align-items-center g-bg-size-cover g-bg-pos-top-center" style="background-image: url(/assets/img/login_admin.jpg);">
        <div class="container g-py-100 g-pos-rel g-z-index-1">
            <div class="row justify-content-center">
                <div class="col-sm-8 col-lg-5">

                    <!--Alert-->
                    <?php
                    if (isset($_SESSION['error']))
                        echo '<div class="alert alert-dismissible fade show g-bg-yellow rounded-0" role="alert">
                        <button type="button" class="close u-alert-close--light" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        
                        <div class="media">
                            <span class="media-body align-self-center">
                                <strong>Внимание!</strong> Не найден работник с таким логином или паролем
                            </span>
                        </div>
                    </div>';
                    unset($_SESSION['error'])
                    ?>
                    <!--Alert-->
                    <div class="g-bg-white rounded g-py-40 g-px-30">
                        <header class="text-center mb-4">
                            <h2 class="h2 g-color-black g-font-weight-600">Авторизация</h2>
                        </header>

                        <!-- Form -->
                        <form class="g-py-15" action="functions/login.php" method="post">
                            <div class="mb-4">
                                <input name="login" class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="text" placeholder="Login">
                            </div>

                            <div class="mb-4">
                                <input name="password" class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="password" placeholder="Password">
                            </div>


                            <div class="mb-4">
                                <button name="log" class="btn btn-md btn-block u-btn-primary rounded g-py-13" type="submit">Войти</button>
                            </div>
                        </form>
                        <!-- End Form -->
                        <footer class="text-center">
                            <a class="g-font-weight-600" href="/">Перейти на главную сайта</a>
                            </p>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Login -->
</main>

<div class="u-outer-spaces-helper"></div>

<!-- JS Global Compulsory -->
<script src="/assets/vendor/jquery/jquery.min.js"></script>
<script src="/assets/vendor/jquery-migrate/jquery-migrate.min.js"></script>
<script src="/assets/vendor/popper.js/popper.min.js"></script>
<script src="/assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- JS Implementing Plugins -->
<script src="/assets/vendor/appear.js"></script>
<script src="/assets/vendor/slick-carousel/slick/slick.js"></script>
<script src="/assets/vendor/cubeportfolio-full/cubeportfolio/js/jquery.cubeportfolio.min.js"></script>
<script  src="/assets/vendor/jquery.maskedinput/src/jquery.maskedinput.js"></script>

<!-- JS Unify -->
<script src="/assets/js/hs.core.js"></script>
<script src="/assets/js/components/hs.header.js"></script>
<script src="/assets/js/helpers/hs.hamburgers.js"></script>
<script src="/assets/js/components/hs.scroll-nav.js"></script>
<script src="/assets/js/components/hs.rating.js"></script>
<script src="/assets/js/components/hs.carousel.js"></script>
<script src="/assets/js/components/hs.cubeportfolio.js"></script>
<script src="/assets/js/components/hs.go-to.js"></script>
<script src="/assets/js/components/hs.masked-input.js"></script>

<!-- JS Customization -->
<script src="/assets/js/custom.js"></script>

<script>
    $(document).on('ready', function () {
        $.HSCore.components.HSMaskedInput.init('[data-mask]');
    });
</script>
</body>

</html>
