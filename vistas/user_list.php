<div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Lista de usuarios</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        require_once "./php/main.php";
        //eliminar usuario
        if(isset($_GET['user_id_del'])){
            require_once "./php/usuario_eliminar.php";
        }

        if(!isset($_GET['page'])){
            $pagina=1;
        }else{
            $pagina=(int) $_GET['page'];
            if($pagina<=1){
                $pagina=1;
            }
        }

        $pagina=limpiar_cadena($pagina);//aca linpiamos la cadena para que no aparescan signos no deseados
        $url="index.php?vistas=user_list&page=";
        $registro=5;//aca defini cuantas registros se van a generar por pagina
        $busqueda="";//aca es donde podemos buscar

        require_once "./php/usuari_lista.php";
    ?>
</div>





    
               

                

                

            

    

    

</div>