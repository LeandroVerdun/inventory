<?php
   $inicio = ($pagina>0) ? (($pagina*$registro)-$registro) : 0;
   $table="";

   if(isset($busqueda) && $busqueda!=""){
    $consulta_dato="SELECT * FROM categoria WHERE categoria_nombre Like '%$busqueda%'
    OR categoria_ubicacion LIKE '%$busqueda%' ORDER BY categoria_nombre ASC LIMIT $inicio,$registro";

    $consulta_total="SELECT COUNT(categoria_id) FROM categoria WHERE 
    categoria_nombre Like '%$busqueda%'
    OR categoria_ubicacion LIKE '%$busqueda%'";

   }else{
    $consulta_dato="SELECT * FROM categoria  
    ORDER BY categoria_nombre ASC LIMIT $inicio,$registro";

    $consulta_total="SELECT COUNT(categoria_id) FROM categoria";
   }

   $conexion=conexion();
   $datos=$conexion->query($consulta_dato);
   $datos=$datos->fetchAll();//usamos fetchall cuando seleccionamos mas de un registro

   $total=$conexion->query($consulta_total);
   $total=(int) $total->fetchColumn();//esto devuelve una columna de los resultados

   $Npaginas=ceil($total/$registro);//aca creamos la cantidad de paginas que se muestra, pero como el numero de
   //registro y el total dearia un numero con coma le ponemos un ceil para que redondee para arriba y cree la cantidad de paginas necesarias

   $table.='
      <div class="table-container">
         <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr class="has-text-centered">
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Productos</th>
                    <th colspan="2">Opciones</th>
                </tr>
            </thead>
         <tbody>
      
   ';

   if($total>=1 && $pagina<=$Npaginas){//con estas condiciones le decimos que el total de registros sea mayor a 1 y la pagina que colocamos sea menor o igual a la cantidad de paginas que tengo
      $contador=$inicio+1;
      $pag_inicio=$inicio+1;
      foreach($datos as $rows){
         $table.='
         <tr class="has-text-centered" >
            <td>'.$contador.'</td>
               <td>'.$rows['categoria_nombre'].'</td>
               <td>'.substr($rows['categoria_ubicacion'],0,25).'</td>
               <td>
                    <a href="index.php?vistas=product_category&categoria_id='.$rows['categoria_id'].'" 
                    class="button is-link is-rounded is-small">Ver productos</a>
                </td>             
               <td>
                     <a href="index.php?vistas=category_update&categoria_id_up='.$rows['categoria_id'].'" 
                     class="button is-success is-rounded is-small">Actualizar</a>
               </td>
               <td>
                     <a href="'.$url.$pagina.'&categoria_id_del='.$rows['categoria_id'].'" 
                     class="button is-danger is-rounded is-small">Eliminar</a>
            </td>
          </tr>';
         $contador++;
      

      }
      $pag_final=$contador-1;

   }else{
      if($total>=1){
         $table.='
         <tr class="has-text-centered" >
                     <td colspan="6">
                           <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                              Haga clic acá para recargar el listado
                           </a>
                     </td>
                </tr>
         ';
      }else{
         $table.='
         <tr class="has-text-centered" >
                    <td colspan="6">
                        No hay registros en el sistema
                    </td>
                </tr>
         ';
      };
   }

   $table.='
      </tbody></table></div>
   ';
   if( $total>=1 && $pagina<=$Npaginas){
      $table.='<p class="has-text-right">Mostrando categorias 
      <strong>'.$pag_inicio.'</strong>
       al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
   }
   $conexion=null;
   echo $table;
   if( $total>=1 && $pagina<=$Npaginas){
      echo paginador_tablas($pagina,$Npaginas,$url,7);
   }