<?php
    session_destroy();
    

    if(headers_sent()){
        echo "<script> window.location.href='index.php?vistas=login';</script>";
    }else{
        header("Location: index.php?vistas=login");
    }