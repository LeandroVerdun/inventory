<div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Buscar usuario</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
       require_once "./php/main.php";

       if(isset($_POST['modulo_buscador'])){//si enviamos el formulario entonces ejecutamos el codigo que hay en esta carpeta
        require_once "./php/buscador.php";

       }

       if(!isset($_SESSION['busqueda_usuario']) && empty($_SESSION['busqyeda_usuario'])){//nos fijamos si viene definida la variable de sesion con isset
    ?>

    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="usuario">   
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php
       }else{//en resumen mostras la barra de busqueda si usuario y si esta vacia y si no mostras la otra lista
    ?>

    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="usuario"> 
                <input type="hidden" name="eliminar_buscador" value="usuario">
                <p>Estas buscando <strong><?php echo $_SESSION['busqueda_usuario'] ?></strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>


    <?php
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
        $url="index.php?vistas=user_search&page=";
        $registro=5;//aca defini cuantas registros se van a generar por pagina
        $busqueda=$_SESSION['busqueda_usuario'];//aca es donde podemos buscar

        require_once "./php/usuari_lista.php";
        }
     ?>
    
</div>