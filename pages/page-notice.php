<?php
get_header();
$query = $_GET;
$args = [
    "post_type" => "post_notice",
    "post_status" => "publish",
    "posts_per_page" => 10,
    "paged" => $_GET["pages"] ?? 1,
];
$posts = new WP_Query($args);
?>
<div class="row gap-32 page-wrap">
    <div class="flex gap-8 middle">
        <h3><?= get_the_title() ?></h3>
        <span class="text-sub light"><?= $posts->found_posts ?></span>
    </div>
    <?php if ($posts->have_posts()): ?>
    <div class="board sliced-bottom">
        <?php while($posts->have_posts()): $posts->the_post(); ?>
            <?= comp("board-item",[
                "link" => get_permalink(),
                "title" => get_the_title(),
                "date" => get_the_date("Y. m. d."),
                "author" => get_the_author_meta("display_name")
            ]) ?>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="row gap-32 center middle">
        <br />
        <br />
        <h2>공지사항이 없습니다.</h2>
        <br />
    </div>
    <?php endif; ?>
    <div>
        <?= comp("pagination", ["query" => $posts]) ?>
    </div>
</div>
<?php get_footer(); ?>