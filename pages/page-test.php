<?php
    get_header();
?>
<?php
    global $wp_post_types;
?>
<?php
    var_dump(
        get_term_by("slug", "photo", "category")->term_id
    );
?>
<?php
    get_footer();
?>