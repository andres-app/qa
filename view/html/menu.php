<?php
require_once("../../models/Rol.php");
$rol = new Rol();

$datos = $rol->get_menu_x_rol($_SESSION["rol_id"]);
?>
<div class="vertical-menu">
    <div data-simplebar="" class="h-100">

        <div id="sidebar-menu">

            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <?php
                // Arrays para menús principales y submenús
                $submenus_mantenimiento = ["Usuarios", "Area", "Tramite", "Tipo de persona", "Roles"];
                $submenus = [];

                foreach ($datos as $row) {
                    // Si el nombre del menú es parte del grupo de submenús, lo guardamos en un array separado
                    if (in_array($row["men_nom_vista"], $submenus_mantenimiento)) {
                        $submenus[] = $row;
                    } else {
                        // Menús principales
                        ?>
                        <li>
                            <a href="<?php echo $row["men_ruta"]; ?>">
                                <i data-feather="<?php echo $row["men_icon"]; ?>"></i>
                                <span data-key="t-<?php echo strtolower($row["men_nom_vista"]); ?>"><?php echo $row["men_nom_vista"]; ?></span>
                            </a>
                        </li>
                        <?php
                    }
                }

                // Verificamos si hay submenús y los mostramos en un menú desplegable
                if (!empty($submenus)) {
                    ?>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="settings"></i>
                            <span data-key="t-mantenimiento">Ajustes</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <?php
                            foreach ($submenus as $submenu) {
                                ?>
                                <li><a href="<?php echo $submenu["men_ruta"]; ?>" data-key="t-<?php echo strtolower($submenu["men_nom_vista"]); ?>"><?php echo $submenu["men_nom_vista"]; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                ?>

            </ul>

        </div>

    </div>
</div>
