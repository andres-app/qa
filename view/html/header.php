<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="../../assets/picture/logo-sm.svg" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="../../assets/picture/logo-sm.svg" alt="" height="24"> <span
                            class="logo-txt">TEMPLATE</span>
                    </span>
                </a>

                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="../../assets/picture/logo-sm.svg" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="../../assets/picture/logo-sm.svg" alt="" height="24"> <span
                            class="logo-txt">TEMPLATE</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <!-- <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Buscar...">
                    <button class="btn btn-primary" type="button"><i class="bx bx-search-alt align-middle"></i></button>
                </div>
            </form> -->
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar ..."
                                    aria-label="Search Result">

                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <input type="hidden" id="user_idx" value="<?php echo $_SESSION["usu_id"] ?>">
            <input type="hidden" id="rol_idx" value="<?php echo $_SESSION["rol_id"] ?>">

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-soft-light border-start border-end"
                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="<?php echo $_SESSION["usu_img"] ?>"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium"><?php echo $_SESSION["usu_nomape"] ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="../perfil/perfil.php"><i
                            class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Perfil</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../html/logout.php"><i
                            class="mdi mdi-logout font-size-16 align-middle me-1"></i> Cerrar Sesion</a>
                </div>
            </div>

        </div>
    </div>
</header>