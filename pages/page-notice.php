<?php
get_header();
?>
<div class="row gap-32 page-wrap">
    <h1><?= get_the_title() ?></h1>
    <div class="board sliced-bottom">
        <?php
        $posts = new WP_Query([
            "post_type"=>"post_notice",
            "post_status"=>"publish",
            "posts_per_page"=>10,
            "paged"=>$_GET["page"] ?? 1
        ]);
        while($posts->have_posts()):$posts->the_post();
        ?>
            <?= comp("board-item",[
                "link"=>get_permalink(),
                "title"=>get_the_title(),
                "date"=>get_the_date("Y. m. d."),
                "author"=>get_the_author_meta("display_name")
            ]) ?>
        <?php endwhile; ?>
    </div>
    <div>
    <?= comp('pagination',[
        'current'=>1,
        'max_page'=>20
    ]) ?>
    </div>
</div>
<?php get_footer(); ?>