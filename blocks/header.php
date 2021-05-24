
    <div class="light-theme u-header__section g-transition-0_3 g-py-6 g-py-14--md"
         data-header-fix-moment-exclude="light-theme g-py-14--md"
         data-header-fix-moment-classes="dark-theme u-shadow-v27 g-bg-white g-py-11--md">
        <nav class="navbar navbar-expand-lg g-py-0">
            <div class="container g-pos-rel">
                <!-- Logo -->
                <a href="/index.php" class="navbar-brand u-header__logo"
                   data-type="static">
                    <img class="u-header__logo-img u-header__logo-img--main d-block g-width-60"
                         src="/assets/img/logo-light.png" alt="Image description"
                         data-header-fix-moment-exclude="d-block"
                         data-header-fix-moment-classes="d-none">

                    <img class="u-header__logo-img u-header__logo-img--main d-none g-width-60"
                         src="/assets/img/logo-dark.png" alt="Image description"
                         data-header-fix-moment-exclude="d-none"
                         data-header-fix-moment-classes="d-block"> </a>
                <!-- End Logo -->

                <!-- Navigation -->
                <div class="collapse navbar-collapse align-items-center flex-sm-row" id="navBar"
                     data-mobile-scroll-hide="true">
                    <div class="navbar-nav btn-group g-mr-10 g-mb-15 g-pt-20 g-pt-0--lg ml-auto">
                        <?php
                        if ($authorized == false) {
                            echo '<a href="/login.php" class="btn btn-primary">Авторизация клиента</a>';
                            } else {
                            if ($position == 'Администратор' || $position != 'Пользователь') {
                                echo '<a href="/admin.php" class="btn btn-primary">Просмотр всех заявок</a>';
                            }
                        }
                        ?>

                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                            if ($authorized == false)
                                echo '<a class="dropdown-item" href="/login_admin.php">Вход сотрудника</a>';
                            if ($authorized == true) {
                                if ($position == 'Администратор') {
                                    echo '<a href="/redist.php" class="dropdown-item">Добавить нового сотрудника</a>
                                            <div class="dropdown-divider"></div>';
                                }
                                echo '<a class="dropdown-item" href="/logout.php">Выход</a>';
                            }
                            ?>


                        </div>
                    </div>
                </div>
                <!-- End Navigation -->

                <!-- Responsive Toggle Button -->
                <button class="navbar-toggler btn g-line-height-1 g-brd-none g-pa-0 g-pos-abs g-top-20 g-right-0"
                        type="button"
                        aria-label="Toggle navigation"
                        aria-expanded="false"
                        aria-controls="navBar"
                        data-toggle="collapse"
                        data-target="#navBar">
                <span class="hamburger hamburger--slider">
                  <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                  </span>
                </span>
                </button>
                <!-- End Responsive Toggle Button -->
            </div>
        </nav>
    </div>
</header>