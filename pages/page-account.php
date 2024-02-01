<?php
    if(is_user_logged_in()) {
        //mypage
        wp_redirect("/");
    } else {
        wp_redirect(getPage("account-create")->permalink);
        exit;
    }
?>