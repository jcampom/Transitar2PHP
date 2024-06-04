<?php
include 'conexion.php';
include 'sessiones/seguridadempresa.php';
// die('JLCM : menu.php : #0 : ' );
// print_r($opcionesPerfil);
// die('\nJLCM : menu.php : #1 : ' );
//Establecemos zona horaria por defecto

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Transitar2 </title>
    <!-- Favicon-->
    <link rel="icon" href="logo_transitar.png" type="image/x-icon">
	<!-- Google Fonts -->
    <!--link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Core Css -->
    <link href="interno/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="interno/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="interno/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Sweetalert Css -->
    <link href="interno/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="interno/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="interno/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom Css -->
    <link href="interno/css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0/css/bootstrap-select.min.css">-->
	<link rel="stylesheet" href="interno/ajax/libs/bootstrap-select/1.14.0/css/bootstrap-select.min.css">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="interno/css/themes/all-themes.css" rel="stylesheet" />

    <!-- Colorpicker Css -->
    <link href="interno/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

    <!-- Dropzone Css -->
    <link href="interno/plugins/dropzone/dropzone.css" rel="stylesheet">

    <!-- Multi Select Css -->
    <link href="interno/plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="interno/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="interno/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <link href="interno/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="interno/fontawesome/css/brands.css" rel="stylesheet">
    <link href="interno/fontawesome/css/solid.css" rel="stylesheet">
    <!-- noUISlider Css -->

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js" defer></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">


    <script>
        $(document).ready(function() {
            $(".cargar").click();
        });

        function myFunction() {

            console.log("cargando");

        }
    </script>
    <link href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css" rel="stylesheet">
    <style>
        .card{
            border-radius:20px;
        }
        .list {
           height: 100% !important;
        }

        .slimScrollDiv {
            height: 70% !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin: 0;
            white-space: nowrap;
            text-align: right;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            align-content: center;
            flex-direction: row;
        }

        .dataTables_wrapper .dataTables_paginate span {
            display: contents;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #008000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                text-decoration: none;
                cursor: pointer;
            }
        }
        .page-loader-wrapper .loader-message {

        }

        .loader-spinner {
        width: 35px;
        height: 35px;
        border: 4px solid #178735;
        border-bottom-color: transparent;
        border-radius: 50%;
        display: inline-block;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    </style>

     <script>
  $(function () {
    //Inicia el select2


    $('.select2').select2({
        theme: 'bootstrap4',
  minimumInputLength: 3 // para buscar a partir del 3er digito

})
  })

 $(document).ready(function(){

 $(".clientes").select2({
  ajax: {
   url: "select_clientes.php",
   type: "post",
   dataType: 'json',
   delay: 250,
   data: function (params) {
    return {
      searchTerm: params.term // search term
    };
   },
   processResults: function (response) {
     return {
        results: response
     };
   },
   cache: true
  }
 });
});

 $(document).ready(function(){

 $(".cobradores").select2({
  ajax: {
   url: "select_cobradores.php",
   type: "post",
   dataType: 'json',
   delay: 250,
   data: function (params) {
    return {
      searchTerm: params.term // search term
    };
   },
   processResults: function (response) {
     return {
        results: response
     };
   },
   cache: true
  }
 });
});

 $(document).ready(function(){

 $(".rutas").select2({
  ajax: {
   url: "select_rutas.php",
   type: "post",
   dataType: 'json',
   delay: 250,
   data: function (params) {
    return {
      searchTerm: params.term // search term
    };
   },
   processResults: function (response) {
     return {
        results: response
     };
   },
   cache: true
  }
 });
});

</script>
<style>

input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.btn-flotante {
	font-size: 16px; /* Cambiar el tamaño de la tipografia */
	text-transform: uppercase; /* Texto en mayusculas */
	font-weight: bold; /* Fuente en negrita o bold */
	color: #ffffff; /* Color del texto */
	border-radius: 5px; /* Borde del boton */
	letter-spacing: 2px; /* Espacio entre letras */
	background-color: green; /* Color de fondo */
	padding: 18px 30px; /* Relleno del boton */
	position: fixed;
	bottom: 40px;
	right: 40px;
	transition: all 300ms ease 0ms;
	box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
	z-index: 99;
}
.btn-flotante:hover {
	background-color: red; /* Color de fondo al pasar el cursor */
	box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.3);
	transform: translateY(-7px);
}
@media only screen and (max-width: 600px) {
 	.btn-flotante {
		font-size: 14px;
		padding: 12px 20px;
		bottom: 20px;
		right: 20px;
	}
}


.vibrar{
  -webkit-animation: tiembla 1s ;
}
@-webkit-keyframes tiembla{
  0%  { -webkit-transform:rotateZ(-5deg); }
  50% { -webkit-transform:rotateZ( 0deg) scale(.8); }
  100%{ -webkit-transform:rotateZ( 5deg); }
}
        </style>

        <style>
    .notifications-container {
        position: absolute;
        top: 60px; /* Ajusta la posición vertical según sea necesario */
        right: 20px; /* Ajusta la posición horizontal según sea necesario */
        z-index: 999;
        background-color: #fff;
        border-radius: 4px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        padding: 10px;
        max-height: 200px; /* Ajusta la altura máxima según sea necesario */
        overflow-y: auto;
    }

    .notification {
        padding: 5px;
        margin-bottom: 5px;
        background-color: #f7f7f7;
        border-radius: 4px;
    }
</style>

<script>
    $(document).ready(function() {
        // Mostrar notificaciones al hacer clic en el ícono de campana
        $(".js-notifications").click(function() {
            // Aquí puedes realizar una petición AJAX para obtener las notificaciones
            // y luego agregarlas al contenedor "notifications-container"

            // Ejemplo de cómo agregar una notificación de prueba
            var notification = "<div class='notification'>Nueva notificación</div>";
            $("#notifications-container").append(notification);
        });
    });
</script>

</head>

<body class="theme-red">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">

                  <img src="logo_transitar.png" width="220">


            </div>
            <div class="loader-message" style="display: flex !important;
                                        justify-content: center !important;
                                        align-items: center !important;
                                        width: 100% !important;
                                        margin-top: 15px;
                                        gap: 18px;"
            >
                <span class="loader-spinner"></span>
                <p>Cargando...</p>
            </div>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="QUE DESEA BUSCAR?...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar" style="height:68px;background-color:green;">

        <div class="container-fluid" style="background-color:green;">

            <div class="navbar-header" style="background-color:green;">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>

            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse" style="background-color:green;">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="#" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <li><a href="#" class="js-notifications"><i class="material-icons">notifications</i></a></li>

                    <!-- #END# Call Search -->
                    <!-- Notifications -->

                </ul>

            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info" style="/*Aquí se cambia el fondo*/background: url('logo_transitar.png') no-repeat no-repeat;background-size: 280px;background-position-x: center;">

                <!--<img src="images/logo_transitar.png" style="width:120px;height:90px">-->
                <div class="image"></div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                    <div class="email"></div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
            <!-- aquí se genera algo -->
                <ul class="list" style="height: 100% !important;">
                    <li class="header" style="display: flex;align-items: center;justify-content: space-between; padding: 5px 20px;background-color:red;color:white">
                        <span><?= ucwords($nombre_usuario) ?></span>


                    </li>

                    <li>
                        <a href="micuenta.php">
                            <i class="material-icons">person</i>
                            <span>Inicio</span>
                        </a>
                    </li>

<?php
// Función recursiva para generar los menús y submenús
function generarMenu($items, $padre = 0) {
    $html = '';
    foreach ($items as $item) {
        if ($item['padre_id'] == $padre) {
            $html .= "<li><a href='" . $item['enlace'] . "' class='menu-toggle'>
            <i class='material-icons'>".$item['icono']."</i>
            <span>" . ucwords($item['nombre']) . "</span></a>";

            // Check if there are submenus
            $hasSubmenus = false;
            foreach ($items as $subItem) {
                if ($subItem['padre_id'] == $item['id']) {
                    $hasSubmenus = true;
                    break;
                }
            }

            if ($hasSubmenus) {
                $html .= "<ul class='ml-menu'>";
                $html .= generarMenu($items, $item['id']); // Recursive call for submenus
                $html .= "</ul>";
            } else {

            }

            $html .= "</li>";

        }

    }

    return $html;
}

// Consulta a la base de datos para obtener los elementos del menú
$sql = "SELECT * FROM menu_items";
$result = sqlsrv_query($mysqli,$sql, array(), array('Scrollable' => 'buffered'));
$menuItems = array();
if (sqlsrv_num_rows($result) > 0) {
    // echo "JLCM : menu.php : #3 --> opcionesPerfil = ";
	// print_r($opcionesPerfil);
	// die("JLCM : menu.php : #4");

	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		if (in_array($row['id'], $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {
			$menuItems[] = $row;
			//  $menuItems[] = $row['padre_id'];
		}
	}
}

// Generar el menú principal
echo generarMenu($menuItems);

?>
<?php  if (in_array("Form Mov", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {  ?>
                      <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                         <i class="material-icons">settings</i>
                            <span>ForMov Pro</span>
                        </a>
                        <ul class="ml-menu">
                            <?php  if (in_array("Perfiles", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {  ?>
                            <li><a href="crear_perfiles.php"><b>Perfiles</b></a></li>
                            <?php } ?>
                             <?php  if (in_array("Usuarios", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {  ?>
                            <li><a href="crear_usuarios.php"><b>Usuarios</b></a></li>
                            <?php } ?>
                            <?php  if (in_array("Menu", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {  ?>
                            <li><a href="crear_menu.php"><b>Menu</b></a></li>
                            <?php } ?>
                            <?php  if (in_array("Tablas", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {  ?>
                            <li><a href="crear_tabla.php"><b>Tablas</b></a></li>
                            <?php } ?>
                            <?php  if (in_array("Formularios", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {  ?>
                            <li><a href="crear_formularios.php"><b>Formularios</b></a></li>
                            <?php } ?>

                        </ul>
                    </li>

                    <?php } ?>

                    <li>
                        <a href="cerrar.php">
                            <i class="material-icons">arrow_back</i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2023 Transitar 2.
                </div>
                <div class="version">
                    <b>Version: </b> 2.0
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->

    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">


        <?php  if (in_array("Liquidaciones", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {  ?>
 <!-- <a href="#" class="btn-flotante vibrar"><span style="font-size: 25px;border-radius:50%" class="fa fa-hand-holding-usd"></span></a> -->
 <?php } ?>
