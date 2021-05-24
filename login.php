<?php
require_once('functions/database_request.php');
session_start();
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
    <section class="g-height-100vh d-flex align-items-center g-bg-size-cover g-bg-pos-top-center" style="background-image: url(/assets/img/login.jpg);">
        <div class="container g-py-100 g-pos-rel g-z-index-1">
            <div class="row justify-content-center">
                <div class="col-sm-8 col-lg-5">
                    <?php
                    if (isset($_SESSION['error']))
                        echo '<div class="alert alert-dismissible fade show g-bg-yellow rounded-0" role="alert">
                        <button type="button" class="close u-alert-close--light" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>

                        <div class="media">
                            <span class="media-body align-self-center">
                                <strong>Внимание!</strong> Не найден пользователь с таким номером телефона
                            </span>
                        </div>
                    </div>';
                    unset($_SESSION['error']);
                    ?>
                    <!--Alert-->

                    <!--Alert-->
                    <div class="g-bg-white rounded g-py-40 g-px-30">
                        <header class="text-center mb-4">
                            <h2 class="h2 g-color-black g-font-weight-600">Авторизация</h2>
                        </header>

                        <!-- Form -->
                        <form action="functions/find_requests.php" method="post" class="g-py-15">
                            <div class="mb-4">
                                <input name="phone" class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="text" placeholder="(XXX) XXX-XXXX" data-mask="(999) 999-9999">
                            </div>


                            <div class="mb-4">
                                <button name="find_req" class="btn btn-md btn-block u-btn-primary rounded g-py-13" type="submit">Войти</button>
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

<?php include 'blocks/scripts.php' ?>

<script>
    $(document).on('ready', function () {
        $.HSCore.components.HSMaskedInput.init('[data-mask]');
    });
</script>
</body>

</html>
