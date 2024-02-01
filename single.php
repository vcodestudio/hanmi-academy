<?php
get_header();
$post = get_post();
if(($p=DIR."/singles/single-".$post->post_type.".php") && file_exists($p)) {
    require $p;
}
get_footer();
?>