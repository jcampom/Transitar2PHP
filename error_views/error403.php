<!DOCTYPE html>
<html lang="es">
     <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Transitar2 </title>
        <!-- Favicon-->
        <link rel="icon" href="logo_transitar.png" type="image/x-icon">
		<title>&iexcl;Acceso prohibido!</title>
        <style>
            ::-moz-selection {background: #b3d4fc; text-shadow: none;}
            ::selection {background: #b3d4fc; text-shadow: none;}
            html {padding: 30px 10px; font-size: 16px; line-height: 1.4; color: #737373; background: #f0f0f0;
                -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
            html,
            input {font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;}
            body {max-width:700px; _width: 700px; padding: 30px 20px 50px; border: 1px solid #b3b3b3;
                border-radius: 4px;margin: 0 auto; box-shadow: 0 1px 10px #a7a7a7, inset 0 1px 0 #fff;
                background: #fcfcfc;}
            h1 {margin: 0 10px; font-size: 50px; text-align: center;}
            h1 span {color: #bbb;}
            h2 {color: #D35780;margin: 0 10px;font-size: 40px;text-align: center;}
            h2 span {color: #bbb;font-size: 80px; text-align:center;}
            h3 {margin: 1.5em 0 0.5em;}
            p {margin: 1em 0; text-align:center;}
            ul {padding: 0 0 0 40px;margin: 1em 0;}
            .container {max-width: 380px;_width: 480px;margin: 0 auto; display: flex; flex-direction: column; justify-content: center; align-items: venter}
            input::-moz-focus-inner {padding: 0;border: 0;}
            a {
                text-decoration: none;
                padding: 8px 15px;
                background-color: #D35780;
                border-radius: 7px;
                color: #fff;
                text-align: center;
                width: 35%;
                margin: auto;
                transition: all ease 300ms;
           }
           a:active {
               scale: 0.96;
           }
           a:hover {
               background-color: #B9003D;;;
           }
           a.mt {
               margin-top: 5px;
           }
        </style>
    </head>
    <body>
        <div class="container">
            <h2><span>403</span><br>Prohibido</h2>
            <p>¡Vaya! No Deberias estar aqui.<br /><br />No se permite el acceso a esta ruta.</p>
            <a href="#" onclick="regresarPaginaAnterior()">Volver</a>
            <a href="micuenta.php" class="mt">Menú principal</a>
        </div>
    </body>
    <script type="text/javascript">
        function regresarPaginaAnterior() {
            window.history.back();
        }

    </script>
</html>
