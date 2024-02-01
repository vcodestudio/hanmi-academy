<?php
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title'    => '테마 설정',
            'menu_title'    => '테마 설정',
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
    }
?>