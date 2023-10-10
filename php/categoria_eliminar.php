<?php
    $categoria_id_del=limpiar_cadena($_GET['categoria_id_del']);

    //verificando usuario

    $check_category=conexion();
    $check_category=$check_category->query("SELECT categoria_id FROM categoria WHERE
    categoria_id='$categoria_id_del'");
    if($check_category->rowCount()==1){
        //verificar productos
        $check_productos=conexion();
        $check_productos=$check_productos->query("SELECT categoria_id FROM producto WHERE
        categoria_id='$categoria_id_del' LIMIT 1");

        if($check_productos->rowCount()<=0){
            $eliminar_categoria=conexion();
            $eliminar_categoria=$eliminar_categoria->prepare("DELETE FROM categoria WHERE categoria_id= :id");

            $eliminar_categoria->execute([":id"=>$categoria_id_del]);

            if($eliminar_categoria->rowCount()==1){
                echo '<div class="notification is-info is-light">
                    <strong>¡Categoria eliminado!</strong><br>
                    Los datos de la categoria se eliminaron con exito
                </div>';

            }else{
                echo '<div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo eliminar la categoria, por favor intente nuevamente
                </div>';

            }
            $eliminar_categoria=null;

        }else{
            echo '<div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo eliminar la categoria, ya que tiene productos registrados
                </div>';

        }
        $check_productos=null;

    }else{
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La categoria que intenta eliminar no existe
        </div>';

    }

    $check_category=null;
