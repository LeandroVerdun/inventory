<?PHP

    $product_id_del=limpiar_cadena($_GET['product_id_del']);
    //verificando el producto
    $check_producto=conexion();
    $check_producto=$check_producto->query("SELECT * FROM producto WHERE
    producto_id='$product_id_del'");
    if($check_producto->rowCount()==1){
        $datos=$check_producto->fetch();

        $nombre_foto = $datos['producto_foto'];


        $eliminar_producto=conexion();
            $eliminar_producto=$eliminar_producto->prepare("DELETE FROM producto WHERE producto_id= :id");
            $eliminar_producto->execute([":id"=>$product_id_del]);

            if ($nombre_foto != "" && is_file("./img/producto/" . $nombre_foto)) {
                chmod("./img/producto/" . $nombre_foto, 0777);
                unlink("./img/producto/" . $nombre_foto);
            }

            if($eliminar_producto->rowCount()==1){
                echo '<div class="notification is-info is-light">
                    <strong>¡Producto eliminado!</strong><br>
                    Los datos del producto se eliminaron con exito
                </div>';

            }else{
                echo '<div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo eliminar el producto, por favor intente nuevamente
                </div>';

            }
            $eliminar_producto=null;

    }else{
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El producto que intenta eliminar no existe
        </div>';

    }

    $check_producto=null;

