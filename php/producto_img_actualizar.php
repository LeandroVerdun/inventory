<?php

require_once "main.php";

$product_id=limpiar_cadena($_POST['img_up_id']);

//vaerificar el producto
$check_producto=conexion();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$product_id'");

if($check_producto->rowCount()==1){
   $datos=$check_producto->fetch(); 
}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La imagen del producto no existe en el sistema
        </div>';
    exit();
    
 }

$check_producto=null;

//directorio de imagenes

$img_dir="../img/producto/";

//comprobar si se selecciono una imagen

if ($_FILES['producto_foto']['name'] == "" || $_FILES['producto_foto']['size'] == 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No ha seleccionado una imagen valida
        </div>';
    exit();
}

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

chmod($img_dir,0777);

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

$img_nombre=renombrar_fotos($datos['producto_nombre']);
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

//eliminando la imagen anterior

if(is_file($img_dir.$datos['producto_foto']) && $datos['producto_foto']!=$foto){
    chmod($img_dir.$datos['producto_foto'], 0777);
    unlink($img_dir.$datos['producto_foto']);
}

 //actualizar datos 
 $actualizar_producto=conexion();
 $actualizar_producto=$actualizar_producto->prepare("UPDATE producto Set producto_foto=:foto
 WHERE producto_id=:id");

$marcadores=[
 ":foto"=>$foto,
 ":id"=>$product_id
];

if($actualizar_producto->execute($marcadores)){
 echo '
     <div class="notification is-info is-light">
         <strong>¡imagen actualizada!</strong><br>
         La imagen del producto ha sido actualizada con exito, pulse aceptar 
         para recargar los cambios.

         <p class="has-text-centered pt-5 pb-5">
             <a href="index.php?vistas=product_img&product_id_up='.$product_id.'" 
             class="button is-link is-rounded">Aceptar</a>
         
         </p>
     </div>';

}else{
    if(is_file($img_dir.$foto)){
        chmod($img_dir.$foto, 0777);
        unlink($img_dir.$foto);

    }
 echo '
     <div class="notification is-warning is-light">
         <strong>¡ocurrio un error!</strong><br>
         No podemos subir la imagen en este momento, por favor intente nuevamente
     </div>';

}
$actualizar_producto=null;

