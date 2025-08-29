<?php
get_header();
$slug = $_GET["category"] ?? "a";
$cat = get_term_by("slug", $slug, "publish_category");
$posts = new WP_Query([
    "post_type"=>"post_book",
    "post_status"=>"publish",
    "tax_query"=>[
        [
        "taxonomy"=>"publish_category",
        "field"=>"slug",
        "terms"=>$cat->slug
        ]
    ],
    "posts_per_page"=>16,
    "paged"=>$_GET["pages"] ?? 1
]);
?>
<div class="row gap-32 page-wrap">
    <div>
        <?= temp("search-header",["label"=>$cat->name,"count"=>$cat->count]) ?>
    </div>
    <div class="publish-list col-4 gap-24">
        <?php
            while($posts->have_posts()):$posts->the_post();
        ?>
        <a class="item row gap-1r" href="<?= get_permalink() ?>">
            <div class="thumb">
                <div>
                    <img <?= acf_img_set(_acf("thumb"),"large") ?> />
                </div>
            </div>
            <div class="row gap-8">
                <h4><?= get_the_title() ?></h4>
                <h6 class="light"><?= _acf("author",) ?></h6>
                <h6 class="light fade"><?= _acf("publish") ?> 발행</h6>
            </div>
        </a> <?php endwhile; ?>
    </div>
    <div>
        <?= comp('pagination') ?>
    </div>
</div>
<?php get_footer(); ?>