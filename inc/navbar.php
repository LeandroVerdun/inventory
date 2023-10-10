<nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="index.php?vistas=home">
      <img src="./img/redengel7.1.png" width="65" height="28">
    </a>

    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      
        <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">Ususarios</a>

            <div class="navbar-dropdown">
                <a class="navbar-item" href="index.php?vistas=user_new">Nuevo</a>
                <a class="navbar-item" href="index.php?vistas=user_list">Lista</a>
                <a class="navbar-item" href="index.php?vistas=user_search">Buscar</a>
            </div>
        </div>
        <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">Categorias</a>

            <div class="navbar-dropdown">
                <a class="navbar-item" href="index.php?vistas=category_new">Nueva</a>
                <a class="navbar-item" href="index.php?vistas=category_list">Lista</a>
                <a class="navbar-item" href="index.php?vistas=category_search">Buscar</a>
            </div>
        </div>
        <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">Productos</a>

            <div class="navbar-dropdown">
                <a class="navbar-item" href="index.php?vistas=product_new">Nuevo</a>
                <a class="navbar-item" href="index.php?vistas=product_list">Lista</a>
                <a class="navbar-item" href="index.php?vistas=product_category">Por Catetgoria</a>
                <a class="navbar-item" href="index.php?vistas=product_search">Buscar</a>
            </div>
        </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <a href="index.php?vistas=user_update&user_id_up=<?php echo
          $_SESSION['id']; ?>" class="button is-primary is-rounded">
            Mi cuenta  
          </a>
          <a href="index.php?vistas=logout" class="button is-link is-rounded">
            Salir
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>