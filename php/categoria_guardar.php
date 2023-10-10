<?php
    require_once "main.php";

    //almacenando datos

    $nombre=limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);

    //verificando campos oblicgatorios
    if($nombre==""){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    //verificando integridad de los datos
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El nombre no coincide con el formato solicitado
        </div>';
        exit();
    }

    if($ubicacion!="") {
        if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La ubicacion no coincide con el formato solicitado
            </div>';
            exit();
        }
    }

    //verificando nombre

    $check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre='$nombre'");//con esto haces una consulta o peticion a la base de datos
    if($check_nombre->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                la categoria ingresada ya se encuentra registrado, por favor elija otro
            </div>';
            exit();
    }
    $check_usuario=null;

    //guadando datos en la base de datos
    $guardar_categoria=conexion();
    $guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria
    (categoria_nombre,categoria_ubicacion)
    VALUES(:nombre,:ubicacion)"
    );

    $marcadores=[
        ":nombre"=>$nombre,
        ":ubicacion"=>$ubicacion

    ];

    $guardar_categoria->execute($marcadores);

    if($guardar_categoria->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡categoria registrada!</strong><br>
                la categoria se registro con exito
            </div>';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                no se pudo registrar la categoria, por favor intente nuevamente
            </div>';
    }

    $guardar_categoria=null;


