<?php
$post = get_post();
$path = DIR."/pages/page-".$post->post_name.".php";
if(file_exists($path)) {
    require $path;
} else {
    get_header();
    ?>
    <div class="row gap-32 page-wrap">
        <h1 class="title">
            <?= get_the_title() ?>
        </h1>
        <div class="row gap-24">
            <?php
            the_content();
            ?>
        </div>
    </div>
    <?php
    get_footer();
}
?>