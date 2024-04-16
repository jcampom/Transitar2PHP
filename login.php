<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Login|PagAppDiario</title>
    <!-- Favicon-->
   
    <link rel="icon" href="logo_transitar.png" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="interno/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="interno/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="interno/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="interno/css/style.css" rel="stylesheet">

</head>

<body class="login-page" style="background: url('fondo_transitar.png') no-repeat no-repeat;background-size: cover;background-attachment: fixed;">
    <div class="login-box">
        <div class="logo_transitar">
           <center> <a href="interno/javascript:void(0);"><img src="logo_transitar.png" style="position:relative;left:-20px" width="350"></a></center>
<br><br>
        </div>
        <div class="card" style="border-radius:50px">
            <div class="body">
                <form id="sign_in" action="sessiones/validar.php" method="POST">
                    <div class="msg">Iniciar Sesión</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="usuario" placeholder="Usuario" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <!--<label for="rememberme">Recuerdame</label>-->
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block waves-effect" type="submit"  style="background-color:#F3D71E;color:black"><b>INGRESAR</b></button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                  
                    
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/examples/sign-in.js"></script>
</body>

</html>
