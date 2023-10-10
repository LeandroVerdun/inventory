<?php require "./inc/session_start.php";?> 
<!DOCTYPE html>
<html>
    <head>
       <?php include "./inc/head.php";?> 
    </head>
    <body>
        <?php
            if(!isset($_GET['vistas']) || $_GET['vistas']=="") {
                $_GET['vistas']="login";
            }

            if(is_file("./vistas/".$_GET['vistas'].".php") && $_GET['vistas']!="login" 
            && $_GET['vistas']!="404"){

                //cerrar sesion forzada
                if((!isset($_SESSION['id']) || $_SESSION['id']=="") || 
                (!isset($_SESSION['usuario']) || $_SESSION['usuario']=="")){
                    include "./vistas/logout.php";
                    exit();
                }

                include "./inc/navbar.php";

                include "./vistas/".$_GET['vistas'].".php";
                
                include "./inc/script.php";

            }else{
                if($_GET['vistas']=="login"){
                    include "./vistas/login.php";
                }else{
                    include "./vistas/404.php";
                }
            }
            
        ?>
        <script src="./js/ajax.js"></script>
    </body>
</html>