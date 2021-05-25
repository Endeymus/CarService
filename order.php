<?php
session_start();
require_once('functions/database_request.php');
if (!isset($_SESSION['phone'])) {
    $_SESSION['error'] = 2;
    header("Location: /login.php");
}
$phone = $_SESSION['phone'];
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    $position = $_SESSION['position'];
    $authorized = $_SESSION['authorized'];
} else {
    $authorized = false;
    $position = 'Пользователь';
}
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
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Leckerli+One">

    <!-- CSS Global Compulsory -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/icon-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/vendor/icon-hs/style.css">
    <link rel="stylesheet" href="/assets/vendor/icon-line/css/simple-line-icons.css">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="/assets/vendor/hamburgers/hamburgers.min.css">
    <link rel="stylesheet" href="/assets/vendor/slick-carousel/slick/slick.css">
    <link rel="stylesheet" href="/assets/vendor/cubeportfolio-full/cubeportfolio/css/cubeportfolio.min.css">

    <!-- CSS Template -->
    <link rel="stylesheet" href="/assets/css/styles.op-spa.css">

    <!-- CSS Customization -->
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>

<body>
<main>
    <!-- Header -->
    <header id="js-header" class="u-header">
        <?php include './blocks/header.php' ?>
        <!-- End Header -->

        <!-- Section Content -->
        <section class="container">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Исполнитель</th>
                        <th>Дата создания заявки</th>
                        <th>Статус заявки</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $sql = get_all_requests_by_phone($phone);
                    $i = 1;
                    while ($res = mysqli_fetch_array($sql)) {
                        if ($res['is_active'] == 1 and $res['repair_completed'] == 0 and $res['request_completed'] == 0 and $res['appointment_date'] != null)
                            echo '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$res["name"].'</td>
                        <td>'.$res["creation_date"].'</td>
                        <td>
                            <span class="u-label u-label-danger g-color-white">В работе</span>
                        </td>
                    </tr>';
                    }
                    $sql = get_all_requests_by_phone($phone);
                    while ($res = mysqli_fetch_array($sql)) {
                        if ($res['repair_completed'] == 1 and $res['request_completed'] == 0 and $res['is_active'] == 1)
                            echo '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$res["name"].'</td>
                        <td>'.$res["creation_date"].'</td>
                        <td>
                            <span class="u-label u-label-info g-color-white">Готов к выдаче</span>
                        </td>
                    </tr>';
                    }
                    $sql = get_all_requests_by_phone($phone);
                    while ($res = mysqli_fetch_array($sql)) {
                        if ($res['appointment_date'] == null and $res['is_active'] == 1)
                            echo '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$res["name"].'</td>
                        <td>'.$res["creation_date"].'</td>
                        <td>
                            <span class="u-label u-label-success g-color-white">В ожидании</span>
                        </td>
                    </tr>';
                    }
                    $sql = get_all_requests_by_phone($phone);
                    while ($res = mysqli_fetch_array($sql)) {
                        if ($res['is_active'] == 0)
                            echo '<tr class="table-secondary">
                        <td>'.$i++.'</td>
                        <td>'.$res["name"].'</td>
                        <td>'.$res["creation_date"].'</td>
                        <td>
                            <span class="u-label u-label-info g-color-white">В ожидании деталей</span>
                        </td>
                    </tr>';
                    }
                    $sql = get_all_requests_by_phone($phone);
                    while ($res = mysqli_fetch_array($sql)) {
                        if ($res['request_completed'] == 1 and $res['is_active'] == 1)
                            echo '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$res["name"].'</td>
                        <td>'.$res["creation_date"].'</td>
                        <td>
                            <span class="u-label u-label-success g-color-white">Завершено</span>
                        </td>
                    </tr>';
                    }
                    unset($_SESSION['phone']);
                    ?>

                    </tbody>
                </table>
            </div>
        </section>
        <!-- End Section Content -->


        <footer>
            <div class="container-fluid text-center g-color-gray-dark-v5 g-pt-40">
                <a class="d-inline-block g-mb-25" href="/"> <img src="/assets/img/logo-dark.png" alt="Logo"> </a>
                <p class="g-mb-30">In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>
            </div>
        </footer>
        <!-- End Footer -->

        <a class="js-go-to u-go-to-v1" href="#"
           data-type="fixed"
           data-position='{
           "bottom": 15,
           "right": 15
         }'
           data-offset-top="400"
           data-compensation="#js-header"
           data-show-effect="zoomIn"> <i class="hs-icon hs-icon-arrow-top"></i> </a>
</main>

<!-- JS Global Compulsory -->
<script src="/assets/vendor/jquery/jquery.min.js"></script>
<script src="/assets/vendor/jquery-migrate/jquery-migrate.min.js"></script>
<script src="/assets/vendor/popper.js/popper.min.js"></script>
<script src="/assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- JS Implementing Plugins -->
<script src="/assets/vendor/appear.js"></script>
<script src="/assets/vendor/slick-carousel/slick/slick.js"></script>
<script src="/assets/vendor/cubeportfolio-full/cubeportfolio/js/jquery.cubeportfolio.min.js"></script>
<script src="/assets/vendor/jquery.maskedinput/src/jquery.maskedinput.js"></script>

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

<!-- JS Plugins Init. -->
<script>

    $(document).on('ready', function () {
        // initialization of carousel
        $.HSCore.components.HSCarousel.init('.js-carousel');

        // initialization of header
        $.HSCore.components.HSHeader.init($('#js-header'));
        $.HSCore.helpers.HSHamburgers.init('.hamburger');

        // initialization of rating
        $.HSCore.components.HSRating.init($('.js-rating'), {
            spacing: 2
        });

        // initialization of go to section
        $.HSCore.components.HSGoTo.init('.js-go-to');

        // initialization of forms
        $.HSCore.components.HSMaskedInput.init('[data-mask]');
    });

    $(window).on('load', function () {
        // initialization of HSScrollNav
        $.HSCore.components.HSScrollNav.init($('#js-scroll-nav'), {
            duration: 700
        });

        // initialization of cubeportfolio
        $.HSCore.components.HSCubeportfolio.init('.cbp');
    });
</script>
</body>
</html>
