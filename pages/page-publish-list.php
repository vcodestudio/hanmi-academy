<?php
get_header();
?>
<div class="row gap-32 page-wrap">
    <h3><?= get_the_title() ?></h3>
    <div>
    <?= comp("tab_submenu") ?>
    </div>
    <?php
    $cats = get_terms([
        "taxonomy"=>"publish_category",
        "hide_empty"=>TRUE,
    ]);
    foreach($cats as $key=>$value):
    ?>
    <div class="row gap-30">
        <?= temp("search-header",["label"=>$value->name,"count"=>$value->count,"link"=>getPage("publish-list-category")->permalink."?category=".$value->slug]) ?>
        <div class="publish-list m-scroll col-4 gap-8">
            <?php
            $posts = get_posts([
                "post_type"=>"post_book",
                "tax_query"=>[
                    [
                        "taxonomy"=>"publish_category",
                        "field"=>"slug",
                        "terms"=>$value->slug
                    ]
                    ],
                "post_status"=>"publish",
                "posts_per_page"=>4,
                ]);
            foreach($posts as $post):
            ?>
            <a class="item row gap-1r" href="<?= get_permalink($post) ?>">
                <div class="thumb">
                    <div>
                        <img <?= acf_img_set(_acf("thumb"),"large") ?>/>
                    </div>
                </div>
                <div class="row gap-8">
                    <h4><?= get_the_title($post) ?></h4>
                    <h6 class="light"><?= _acf("author",$post) ?></h6>
                    <h6 class="light fade"><?= _acf("publish",$post) ?> 발행</h6>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <hr/>
    <?php endforeach; ?>
</div>
<?php get_footer(); ?>