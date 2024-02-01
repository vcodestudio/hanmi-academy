<?php
get_header();
?>
<div class="row gap-32 page-wrap">
    <?= temp("lab-header") ?>
    <div class="row gap-16">
        <h4><?= get_the_title() ?></h4>
        <p>
        <?= get_the_content() ?>
        </p>
        <div class="col-2 gap-40">
            <?= comp("search") ?>
        </div>
    </div>
    <div class="lab row gap-40">
        <?php
        $posts = new WP_Query([
            'post_type'=>'lab_1',
            'post_status'=>'publish',
            'posts_per_page'=>5,
            'paged'=>$_GET['pages'] ?? 1,
            's'=>$_GET['search'] ?? ''
        ]);
        if($posts->have_posts()):
        while($posts->have_posts()):$posts->the_post();
        ?>
        <a href="<?= get_permalink() ?>" class="item col-2 gap-24">
            <div class="thumb">
                <img <?= acf_img_set(_acf("thumb"),"medium") ?>/>
            </div>
            <div class="row gap-8">
                <p class="light">
                    <?= get_the_title() ?>
                </p>
                <h4 class="b">
                    <?= ($f=_acf("sub_title"))?$f:get_the_title() ?>
                </h4>
                <p class="light">
                <?= mb_substr(_acf("desc"),0,150,"utf-8") ?> ...
                </p>
            </div>
        </a>
        <?php endwhile; else:?>
        <?= comp("no-search") ?>
        <?php endif; ?>
    </div>
    <div>
    <?= comp('pagination') ?>
    </div>
</div>
<?php get_footer(); ?>