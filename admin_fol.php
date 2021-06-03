<?php
session_start();
require_once('functions/database_request.php');
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    $position = $_SESSION['position'];
    $authorized = $_SESSION['authorized'];
} else {
    header("Location: /index.php");
}
if (isset($_GET['id'])) {
    $id_request = $_GET['id'];
    $info = get_user_info($id_request);
} else {
    header("Location: /admin.php");
}
if (isset($_GET['defects'])) {
    $id_defects = $_GET['defects'];
    set_repair_completed_defects($id_request, $id_defects);
    header("Location: /admin_fol.php?id=$id_request");
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
            <div class="g-my-20">
                <h2 class="h3">Подробная информация об автомобиле</h2>
            </div>
            <div class="g-bg-secondary">
                <form class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">
                    <div class="form-group row g-mb-25">
                        <label for="example-text-input" class="col-2 col-form-label g-color-black">Номер заявки</label>
                        <div class="col-10">
                            <input class="form-control rounded-0 form-control-md" type="text"
                                   value="<?php echo $id_request ?>"
                                   id="example-text-input" readonly>
                        </div>
                    </div>
                    <div class="form-group row g-mb-25">
                        <label for="example-text-input" class="col-2 col-form-label g-color-black">ФИО
                            пользователя</label>
                        <div class="col-10">
                            <input class="form-control rounded-0 form-control-md" type="text"
                                   value="<?php echo $info['name'] ?>"
                                   id="example-text-input" readonly>
                        </div>
                    </div>
                    <div class="form-group row g-mb-25">
                        <label for="example-text-input" class="col-2 col-form-label g-color-black">Номер
                            телефона</label>
                        <div class="col-10">
                            <input class="form-control rounded-0 form-control-md" type="text"
                                   value="<?php echo $info['phone'] ?>"
                                   id="example-text-input" readonly>
                        </div>
                    </div>
                    <div class="form-group row g-mb-25">
                        <label for="example-text-input" class="col-2 col-form-label g-color-black">Марка</label>
                        <div class="col-10">
                            <input class="form-control rounded-0 form-control-md" type="text"
                                   value="<?php echo $info['brand'] ?>"
                                   id="example-text-input" readonly>
                        </div>
                    </div>
                    <div class="form-group row g-mb-25">
                        <label for="example-text-input" class="col-2 col-form-label g-color-black">Модель</label>
                        <div class="col-10">
                            <input class="form-control rounded-0 form-control-md" type="text"
                                   value="<?php echo $info['model'] ?>"
                                   id="example-text-input" readonly>
                        </div>
                    </div>
                    <div class="form-group row g-mb-25">
                        <label for="example-text-input" class="col-2 col-form-label g-color-black">Перечень
                            поломок</label>
                        <div class="col-10">
                            <?php
                            $res = get_all_defects_by_id($id_request);
                            while ($row = mysqli_fetch_array($res)) {
                                if ($row['repair_completed'] == 0) {
                                    echo '<div class="row">
                            <div class="col g-color-black">' . $row['name'] . '</div>
                            <div class="col">
                                <button type="button" onclick="document.location.href=\'/admin_fol.php?id=' . $id_request . '&defects=' . $row['id'] . '\'" class="btn btn-success g-mr-10 g-mb-15 btn-block">Завершить
                                </button>
                            </div>
                        </div>';
                                } else
                                    echo '<div class="row">
                            <div class="col">' . $row['name'] . '</div>
                            <div class="col">
                                <button type="button" class="btn btn-secondary g-mr-10 g-mb-15 btn-block">Завершено</button>
                            </div>
                        </div>';
                            }
                            ?>

                        </div>
                    </div>
                </form>
            </div>
        </section>
        <!-- End Section Content -->


        <footer>
            <div class="container-fluid text-center g-color-gray-dark-v5 g-pt-40">
                <a class="d-inline-block g-mb-25" href="/"> <img src="/assets/img/logo-dark.png" alt="Logo"> </a>
                <p class="g-mb-30">Created by Kate Komissarova in 2021</p>
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
