<?php
session_start();
require_once("./functions/database_request.php");
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    $position = $_SESSION['position'];
    $authorized = $_SESSION['authorized'];
} else {
    $authorized = false;
    $position = 'Пользователь';
}
/*foreach ($_SESSION as $key => $value) {
    echo "$key + ' : '  + $value";
}*/
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php include './blocks/head.php' ?>
</head>

<body>
<main>
    <!-- Header -->
    <header id="js-header" class="u-header u-header--sticky-top u-header--change-appearance g-z-index-9999"
            data-header-fix-moment="100">
    <?php include './blocks/header.php' ?>
    <!-- End Header -->

    <!-- Section Content -->
    <section id="home" class="u-bg-overlay g-height-100vh g-min-height-600 g-bg-img-hero g-bg-black-opacity-0_3--after"
             style="background-image: url(/assets/img/slider_img.jpg);">
        <div class="u-bg-overlay__inner g-absolute-centered--y w-100">
            <div class="container text-center g-max-width-750" data-mobile-scroll-hide="true">
                <h3 class="d-inline-block text-uppercase g-font-weight-700 g-font-size-22 g-color-white g-brd-bottom g-brd-3 g-brd-primary g-mb-10">
                    Горячее предложение</h3>
                <h1 class="g-line-height-1_5 g-font-weight-700 g-font-size-50 g-color-white g-mb-15">Качественный
                    авторемонт</h1>
                <h3 class="text-uppercase g-font-weight-700 g-font-size-35 g-color-white g-mb-35">успей подать
                    заявку</h3>
                <a class="btn btn-md text-uppercase u-btn-primary g-font-weight-700 g-font-size-11 g-brd-none rounded-0 g-py-10 g-px-25"
                   href="#about">Подать заявку</a>
            </div>
        </div>
    </section>
    <!-- End Section Content -->

    <!-- Section Content -->
    <section id="about">
        <div class="container">
            <div class="g-bg-main g-brd-primary g-my-30 ">
                <h2 class="h3 text-center">Заявка на ремонт автомобиля</h2>
            </div>
            <form action="functions/add_request.php" class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">
                <div class="form-group g-mb-20">
                    <label class="g-mb-10">ФИО</label>
                    <div class="input-group g-brd-primary--focus">
                        <div class="input-group-prepend">
                            <span class="input-group-text rounded-0 g-bg-white g-color-gray-light-v1"><i
                                        class="icon-user-follow"></i></span>
                        </div>
                        <input name="name" class="form-control form-control-md rounded-0" type="text"
                               placeholder="Комиcсарова Екатерина Геннадьевна" required>
                    </div>
                </div>
                <div class="form-group g-mb-20">
                    <label class="g-mb-10">Номер телефона</label>
                    <div class="input-group g-brd-primary--focus">
                        <div class="input-group-prepend">
                            <span class="input-group-text rounded-0 g-bg-white g-color-gray-light-v1"><i
                                        class="icon-phone"></i></span>
                        </div>
                        <input name="phone" id="inputGroup1_2"
                               class="form-control form-control-md g-brd-right-none rounded-0" type="text"
                               placeholder="(XXX) XXX-XXXX" data-mask="(999) 999-9999" required>
                    </div>
                </div>
                <div class="form-group g-mb-20">
                    <label for="exampleSelect1">Марка и модель автомобиля</label>
                    <select name="id_car" class="form-control rounded-0" id="exampleSelect1" required>
                        <option value="0" selected disabled>Выберите марку и модель</option>
                        <?php
                        $cars = get_all_cars();
                        while ($rows = mysqli_fetch_array($cars))
                            echo '<option value=' . $rows['id'] . '>' . $rows['brand'] . ' ' . $rows['model'] . '</option>';
                        ?>
                        <!--						<option>1</option>-->

                    </select>
                </div>
                <div class="form-group g-mb-20">
                    <button type="button" onclick="add()" name="send_request" class="btn btn-md u-btn-primary rounded-0">Добавить еще поломку</button>
                    <label for="exampleSelect2">Поломка</label>
                    <select name="id_defects[]" onchange="cost(this)" class="form-control rounded-0" id="exampleSelect2"
                            required>
                        <option data-id="0" value="0" selected disabled>Выберите поломку</option>
                        <?php
                        $defects = get_all_defects();
                        while ($rows = mysqli_fetch_array($defects))
                            echo '<option  data-id="' . $rows['cost'] . '" value=' . $rows['id'] . '>' . $rows['name'] . '</option>';
                        ?>
                    </select>

                </div>
                <div class="form-group u-has-disabled-v1 g-mb-20">
                    <label class="g-mb-10" for="inputGroup1_1">Цена ремонта</label>
                    <input id="inputGroup1_1" class="form-control form-control-md rounded-0" type="email" disabled="">
                    <small class="form-control-feedback">Поле расчитывается автоматически</small>
                </div>
                <button type="submit" name="send_request" class="btn btn-md u-btn-primary rounded-0">Отправить заявку
                </button>
            </form>
        </div>
    </section>
    <!-- End Section Content -->

    <footer>

        <div class="container-fluid text-center g-color-gray-dark-v5 g-pt-40">
            <a class="d-inline-block g-mb-25" href="/"> <img src="assets/img/logo-dark.png" alt="Logo"> </a>
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

<?php include 'blocks/scripts.php' ?>

<!-- JS Plugins Init. -->
<script>
    let select_id = 2;

    function cost(arg) {
        let sum = 0;
        for (let i = select_id; i >= 2; i--) {
            let rd = $('#exampleSelect'+i).val();
            let cost;
            $(arg)
                .find('option')
                .each(function () {
                    if ($(this).val().toString() === rd.toString()) {
                        cost = $(this).data('id');
                        sum += parseInt(cost);
                    }
                })
        }


        $('#inputGroup1_1').attr('value', sum);
    }
    function add() {
        let select = $("<select name='id_defects[]' onchange='cost(this)' class='form-control rounded-0' id='exampleSelect"+(select_id+1)+"' required></select>");
        let inner = $('#exampleSelect2').html()
        $(select).append($(inner));
        $('#exampleSelect'+select_id).after($(select))
        select_id++;
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
