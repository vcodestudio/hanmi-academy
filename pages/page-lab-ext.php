<?php
get_header();
?>
<div class="row gap-32 page-wrap">
    <?= temp("lab-header") ?>
    <div class="row gap-16">
        <h4><?= get_the_title() ?></h4>
        <div class="col-2 gap-40">
            <?= comp("search") ?>
        </div>
    </div>
    <div class="gallery-square">
        <?php
        $posts = new WP_Query([
            'post_type'=>'lab_3',
            'post_status'=>'publish',
            'posts_per_page'=>5,
            'paged'=>$_GET['pages'] ?? 1,
            's'=>$_GET['search'] ?? ''
        ]);
        if($posts->have_posts()):
        while($posts->have_posts()):$posts->the_post();
        ?>
        <a href="<?= get_permalink() ?>" class="item">
            <div class="thumb">
                <div class="img" style="background-image:url(<?= _acf("thumb")["sizes"]["large"] ?? getImg("sample.png") ?>)"></div>
            </div>
            <div>
                <h4>
                <?= get_the_title() ?>
                </h4>
            </div>
        </a>
        <?php endwhile;else: ?>
            <?= comp("no-search") ?>
        <?php endif; ?>
    </div>
    <div>
    <?= comp('pagination') ?>
    </div>
</div>
<?php get_footer(); ?>