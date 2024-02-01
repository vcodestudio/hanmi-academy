<?php
    if(current_user_can("delete_users")) {
        $cont = "";
        ob_start();
        require_once get_stylesheet_directory()."/pages/manual/academy.html";
        $cont = ob_get_contents();
        ob_end_clean();
        $cont = str_replace("./_app",get_stylesheet_directory_uri()."/pages/manual/_app",$cont);
        echo $cont;
    } else {
        wp_redirect("/");
    }
?>