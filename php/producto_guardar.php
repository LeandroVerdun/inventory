<?php
    require_once "../inc/session_start.php";
    require_once "main.php";

    //almacenando datos

    $codigo=limpiar_cadena($_POST['producto_codigo']);
    $nombre=limpiar_cadena($_POST['producto_nombre']);

    $precio=limpiar_cadena($_POST['producto_precio']);
    $stock=limpiar_cadena($_POST['producto_stock']);
    $categoria=limpiar_cadena($_POST['producto_categoria']);

    //verificar campos obligatorios
    if($codigo=="" || $nombre=="" || $precio=="" || $stock=="" || $categoria==""){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    //verificando integridad de los datos
    if(verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El codigo de barras no coincide con el formato solicitado
        </div>';
        exit();
    }


    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El nombre no coincide con el formato solicitado
        </div>';
        exit();
    }

    if(verificar_datos("[0-9.]{1,25}",$precio)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El precio no coincide con el formato solicitado
        </div>';
        exit();
    }


    if(verificar_datos("[0-9]{1,25}",$stock)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El stock no coincide con el formato solicitado
        </div>';
        exit();
    }


    //verificando el codigo

    $check_codigo=conexion();//llamamos a la conecxion
    $check_codigo=$check_codigo->query("SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo'");//con esto haces una consulta o peticion a la base de datos
    if($check_codigo->rowCount()>0){//rowCount devuelve la cantidad de registros que se hizo con esa consulta
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El codigo de barras ingresado ya se encuentra registrado, por favor elija otro
            </div>';
            exit();
    }
    $check_codigo=null;



    //verificando el nombre

    $check_nombre=conexion();//llamamos a la conecxion
    $check_nombre=$check_nombre->query("SELECT producto_nombre FROM producto WHERE producto_nombre='$nombre'");//con esto haces una consulta o peticion a la base de datos
    if($check_nombre->rowCount()>0){//rowCount devuelve la cantidad de registros que se hizo con esa consulta
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El Nombre ingresado ya se encuentra registrado, por favor elija otro
            </div>';
            exit();
    }
    $check_nombre=null;


    //verificando  categoria

    $check_categoria=conexion();//llamamos a la conecxion
    $check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");//con esto haces una consulta o peticion a la base de datos
    if($check_categoria->rowCount()<=0){//rowCount devuelve la cantidad de registros que se hizo con esa consulta
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoria ingresada no existe, por favor elija otro
            </div>';
            exit();
    }
    $check_categoria=null;


    //directorio de imagenes

    $img_dir="../img/producto/";

    //comprobar si se selecciono una imagen

    if($_FILES['producto_foto']['name']!="" && $_FILES['producto_foto']['size']>0){

        //varificando directorio de imagenes
        if(!file_exists($img_dir)){
            if(!mkdir($img_dir,0777)){
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        error al crear directorio
                     </div>';
                exit();
            }
        }

        //verificar formato de las imagenes
        if(mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/jpeg" &&
        mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/png"){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La imagen seleccionada es de formato no permitido
                </div>';
            exit();
        }

        //verificar el peso de la imagen
        if(($_FILES['producto_foto']['size']/1024)>3072){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La imagen seleccionada supera el limite permitido
                </div>';
            exit();

        }

        // extension de la imagen
        switch(mime_content_type($_FILES['producto_foto']['tmp_name'])){
            case 'image/jpeg':
                $img_ext=".jpg";
            break;
            case 'image/png':
                $img_ext=".png";
            break;
        }

        chmod($img_dir,0777);
        $img_nombre=renombrar_fotos($nombre);
        $foto=$img_nombre.$img_ext;

        //moviendo imagen al directorio
        if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    no podemos cargar la imagen al sistema en este momento
                </div>';
            exit();

        }


    }else{
        $foto="";
    }

    //guadando datos
    $guardar_producto=conexion();
    $guardar_producto=$guardar_producto->prepare("INSERT INTO producto
    (producto_codigo,producto_nombre,producto_precio,producto_stock,producto_foto,
    categoria_id,usuario_id)
    VALUES(:codigo, :nombre, :precio, :stock, :foto, :categoria, :usuario)"
    );

    $marcadores=[
        ":codigo"=>$codigo,
        ":nombre"=>$nombre,
        ":precio"=>$precio,
        ":stock"=>$stock,
        ":foto"=>$foto,
        ":categoria"=>$categoria,
        ":usuario"=>$_SESSION['id']
    ];

    $guardar_producto->execute($marcadores);
    
    if($guardar_producto->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>PRODUCTO REGISTRADO</strong><br>
                El producto se registro con exito
            </div>';
            exit();

    }else{

        if(is_file($img_dir.$foto)){
            chmod($img_dir.$foto,0777);
            unlink($img_dir.$foto);
        }


        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el producto, por favor intente nuevamente
            </div>';
            
    }
    $guardar_producto=null;




  

    
