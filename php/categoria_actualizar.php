<?php
    require_once "main.php";

    $id=limpiar_cadena($_POST['categoria_id']);

     //vaerificar categoria
    $check_categoria=conexion();
    $check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id='$id'");
 
    if($check_categoria->rowCount()<=0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoria no existe en el sistema
            </div>';
        exit();
    }else{
        $datos=$check_categoria->fetch();
     }
 
    $check_categoria=null;

     //almacenando datos

    $nombre=limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);

    //verificando campos obligatorios
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
    

    if($nombre!=$datos['categoria_nombre']){
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
    }

    //actualizar datos 
    $actualizar_categoria=conexion();
    $actualizar_categoria=$actualizar_categoria->prepare("UPDATE categoria Set
    categoria_nombre=:nombre,categoria_ubicacion=:ubicacion WHERE categoria_id=:id");

    $marcadores=[
        ":nombre"=>$nombre,
        ":ubicacion"=>$ubicacion,
        ":id"=>$id
    ];

    if($actualizar_categoria->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡Categoria actualizado!</strong><br>
                La categoria se actualizo correctamente
            </div>';
    
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar la categoria, por favor intente nuevamente
            </div>';
    
    }
    $actualizar_categoria=null;

    