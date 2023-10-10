<?php
   $inicio = ($pagina>0) ? (($pagina*$registro)-$registro) : 0;
   $table="";

   $campos="producto.producto_id,producto.producto_codigo,producto.producto_nombre,
   producto.producto_precio,producto.producto_stock,producto.producto_foto,categoria.categoria_nombre,
   usuario.usuario_nombre,usuario.usuario_apellido";

   if(isset($busqueda) && $busqueda!=""){
    $consulta_dato="SELECT $campos FROM producto 
    INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id
    INNER JOIN usuario ON producto.usuario_id=usuario.usuario_id
    WHERE producto.producto_codigo LIKE '%$busqueda%' 
    OR producto.producto_nombre LIKE '%$busqueda%'
    ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registro";

    $consulta_total="SELECT COUNT(producto_id) FROM producto 
    WHERE producto_codigo LIKE '%$busqueda%' 
    OR producto_nombre LIKE '%$busqueda%'";

   }elseif($categoria_id>0){
    $consulta_dato="SELECT $campos FROM producto 
    INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id
    INNER JOIN usuario ON producto.usuario_id=usuario.usuario_id
    WHERE producto.categoria_id='$categoria_id'
    ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registro";

    $consulta_total="SELECT COUNT(producto_id) FROM producto 
    WHERE producto.categoria_id='$categoria_id'";

   }
   else{
    $consulta_dato="SELECT $campos FROM producto 
    INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id
    INNER JOIN usuario ON producto.usuario_id=usuario.usuario_id
    ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registro";

    $consulta_total="SELECT COUNT(producto_id) FROM producto";
   }

   $conexion=conexion();
   $datos=$conexion->query($consulta_dato);
   $datos=$datos->fetchAll();//usamos fetchall cuando seleccionamos mas de un registro

   $total=$conexion->query($consulta_total);
   $total=(int) $total->fetchColumn();//esto devuelve una columna de los resultados

   $Npaginas=ceil($total/$registro);//aca creamos la cantidad de paginas que se muestra, pero como el numero de
   //registro y el total dearia un numero con coma le ponemos un ceil para que redondee para arriba y cree la cantidad de paginas necesarias

   

   if($total>=1 && $pagina<=$Npaginas){//con estas condiciones le decimos que el total de registros sea mayor a 1 y la pagina que colocamos sea menor o igual a la cantidad de paginas que tengo
      $contador=$inicio+1;
      $pag_inicio=$inicio+1;
      foreach($datos as $rows){
        $table.='

        <article class="media">
            <figure class="media-left">
                <p class="image is-64x64">';
                if(is_file("./img/producto/".$rows['producto_foto'])){
                    $table.='<img src="./img/producto/'.$rows['producto_foto'].'">';
                }else{
                    $table.='<img src="./img/Image_not_available.png">';
                }
        $table.='</p>
            </figure>
            <div class="media-content">
                <div class="content">
                    <p>
                        <strong>1'.$contador.' - '.$rows['producto_nombre'].'</strong><br>
                        <strong>CODIGO:</strong> '.$rows['producto_codigo'].', 
                        <strong>PRECIO:</strong> $'.$rows['producto_precio'].', 
                        <strong>STOCK:</strong> '.$rows['producto_stock'].', 
                        <strong>CATEGORIA:</strong> '.$rows['categoria_nombre'].', 
                        <strong>REGISTRADO POR:</strong> '.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'
                    </p>
                </div>
                <div class="has-text-right">
                    <a href="index.php?vistas=product_img&product_id_up='.$rows['producto_id'].'" class="button is-link is-rounded is-small">Imagen</a>

                    <a href="index.php?vistas=product_update&product_id_up='.$rows['producto_id'].'" class="button is-success is-rounded is-small">Actualizar</a>

                    <a href="'.$url.$pagina.'&product_id_del='.$rows['producto_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
                </div>
            </div>
        </article>


        <hr>
        ';
         $contador++;
      

      }
      $pag_final=$contador-1;

   }else{
      if($total>=1){
         $table.='
        <p class="has-text-centered">
            <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
            Haga clic acá para recargar el listado
            </a>
        </p>            
         ';
      }else{
         $table.='<p class="has-text-centered">No hay registros en el sistema</p>';
      };
   }


   if( $total>=1 && $pagina<=$Npaginas){
      $table.='<p class="has-text-right">Mostrando productos
      <strong>'.$pag_inicio.'</strong>
       al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
   }
   $conexion=null;
   echo $table;
   if( $total>=1 && $pagina<=$Npaginas){
      echo paginador_tablas($pagina,$Npaginas,$url,7);
   }