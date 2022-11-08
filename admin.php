<?php
session_start();
require_once('functions/database_request.php');
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $position = $_SESSION['position'];
    $authorized =  $_SESSION['authorized'];
} else {
    header("Location: /index.php");
}
if (isset($_GET['appoint'])) {
    $id_request = $_GET['appoint'];
    set_appointment($id_request);
    header("Location: /admin.php");
}
if (isset($_GET['repair'])) {
    $id_request = $_GET['repair'];
    set_repair_completed_request($id_request);
    header("Location: /admin.php");
}
if (isset($_GET['request'])) {
    $id_request = $_GET['request'];
    set_request_completed($id_request);
    header("Location: /admin.php");
}
$employyes = get_employees($login, $password);
$id_emloyees = $employyes['id'];
$name = $employyes['name'];
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
            <h2 class="h3">Здравствуйте, <?php echo $name?></h2>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead >
                <tr class="fw-bold fs-5">
                    <th>#</th>
                    <th>Марка автомобиля</th>
                    <th>Модель</th>
                    <th>Дата создания</th>
                    <th>Дата завершения</th>
                    <?php
                    if ($position == 'Администратор')
                        echo '<th>Мастер</th>';
                    ?>
                    <th>Действие</th>
                </tr>
                </thead>

                <tbody class="g-color-black">
                <?php
                    if ($position != 'Администратор') {
                        $i = 1;
                        $res = sql_find_all_car_by_employee($id_emloyees);
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['repair_completed'] == 0 and $row['appointment_date'] !=null and $row['is_active'] == 1)
                                echo '<tr class="table-primary" >
                    <td>'.$i++.'</td>
                    <td><a href="/admin_fol.php?id='.$row['id'].'"> '.$row['brand'].'</a></td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td></td>
                    <td><button type="button" onclick="document.location.href=\'/admin.php?repair='.$row['id'].'\'" class="btn btn-primary g-mr-10 g-mb-15">Завершить работу</button></td>
                </tr>';
                        }
                        $res = sql_find_all_car_by_employee($id_emloyees);
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['appointment_date'] == null and $row['is_active'] == 1)
                                echo '<tr class="table-danger" >
                    <td>'.$i++.'</td>
                    <td><a href="/admin_fol.php?id='.$row['id'].'"> '.$row['brand'].'</a></td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td></td>
                    
                    
                    <td><button type="button" onclick="document.location.href=\'/admin.php?appoint='.$row['id'].'\'" class="btn btn-danger g-mr-10 g-mb-15">Начать работу</button></td>
                </tr>';
                        }
                        $res = sql_find_all_car_by_employee($id_emloyees);
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['request_completed'] == 0 and $row['repair_completed'] == 1 and $row['is_active'] == 1)
                                echo '<tr class="table-success" >
                    <td>'.$i++.'</td>
                    <td><a href="/admin_fol.php?id='.$row['id'].'"> '.$row['brand'].'</a></td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td></td>
                    <td><button type="button" onclick="document.location.href=\'/admin.php?request='.$row['id'].'\'" class="btn btn-success g-mr-10 g-mb-15">Закрыть заявку</button></td>
                </tr>';
                        }
                        $res = sql_find_all_car_by_employee($id_emloyees);
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['is_active'] == 0 and $row['request_completed'] == 0)
                                echo '<tr class="table-info">
                    <td>'.$i++.'</td>
                    <td><a href="/admin_fol.php?id='.$row['id'].'"> '.$row['brand'].'</a></td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td></td>
                    <td><button type="button" onclick="document.location.href=\'/admin.php?check='.$row['id'].'\'" class="btn btn-info g-mr-10 g-mb-15">Проверить наличие деталей</button></td>
                </tr>';
                        }
                        $res = sql_find_all_car_by_employee($id_emloyees);
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['request_completed'] == 1)
                                echo '<tr class="table-secondary">
                    <td>'.$i++.'</td>
                    <td><a href="/admin_fol.php?id='.$row['id'].'"> '.$row['brand'].'</a></td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td>'.$row['end_date'].'</td>
                    <td><button type="button" class="btn btn-secondary g-mr-10 g-mb-15">Завершено</button></td>
                </tr>';
                        }}
                    else if ($position == 'Администратор') {
                        $i = 1;
                        $res = sql_find_all_car_by_admin();
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['repair_completed'] == 0 and $row['appointment_date'] !=null and $row['is_active'] == 1)
                                echo '<tr class="table-primary" >
                    <td>'.$i++.'</td>
                    <td>'.$row['brand'].'</td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td></td>
                    <td>'.$row['name'].'</td>
                    <td><button type="button" class="btn btn-primary g-mr-10 g-mb-15">В работе</button></td>
                </tr>';
                        }
                        $res = sql_find_all_car_by_admin();
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['appointment_date'] == null and $row['is_active'] == 1)
                                echo '<tr class="table-danger" >
                    <td>'.$i++.'</td>
                    <td>'.$row['brand'].'</td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td></td>
                    <td>'.$row['name'].'</td>
                    <td><button type="button" class="btn btn-danger g-mr-10 g-mb-15">Ожидает ремонта</button></td>
                </tr>';
                        }
                        $res = sql_find_all_car_by_admin();
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['request_completed'] == 0 and $row['repair_completed'] == 1 and $row['is_active'] == 1)
                                echo '<tr class="table-success" >
                    <td>'.$i++.'</td>
                    <td>'.$row['brand'].'</td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td></td>
                    <td>'.$row['name'].'</td>
                    <td><button type="button"  class="btn btn-success g-mr-10 g-mb-15">Ремонт завершен</button></td>
                </tr>';
                        }
                        $res = sql_find_all_car_by_admin();
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['is_active'] == 0 and $row['request_completed'] == 0)
                                echo '<tr class="table-info">
                    <td>'.$i++.'</td>
                    <td>'.$row['brand'].'</td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td></td>
                    <td>'.$row['name'].'</td>
                    <td><button type="button" class="btn btn-info g-mr-10 g-mb-15">Недостаточно деталей</button></td>
                </tr>';
                        }
                        $res = sql_find_all_car_by_admin();
                        while ($row = mysqli_fetch_array($res)){
                            if ($row['request_completed'] == 1)
                                echo '<tr class="table-secondary">
                    <td>'.$i++.'</td>
                    <td>'.$row['brand'].'</td>
                    <td>'.$row['model'].'</td>
                    <td>'.$row['creation_date'].'</td>
                    <td>'.$row['end_date'].'</td>
                    <td>'.$row['name'].'</td>
                    <td><button type="button" class="btn btn-secondary g-mr-10 g-mb-15">Завершено</button></td>
                </tr>';
                        }
                    }

                ?>

                </tbody>
            </table>
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

<!-- JS Plugins Init. -->
<script>
    function info(id) {
        window.location.href("/admin_fol.php?id="+id);
    }

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
