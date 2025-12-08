<?php
get_header();
$sliders = function_exists('get_field') ? get_field("main_slider","option") : null;
$sliders = $sliders?$sliders:[];
?>
<style>
    .content_wrap {
        margin-bottom: 0px;
    }
</style>
<div class="index">
    <?php
        if(count($sliders) > 0):
            $first_item = $sliders[0];
            $main_image = _acf("thumb_main_slider", $first_item->ID) ?: _acf("thumb", $first_item->ID);
            if($main_image):
    ?>
    <div class="main-banner-image" style="width: 100%; height: 640px; position: relative; overflow: hidden;">
        <?php
            $img_src = is_array($main_image) ? ($main_image["sizes"]["large"] ?? $main_image["url"]) : $main_image;
        ?>
        <img src="<?= esc_url($img_src) ?>" alt="<?= esc_attr(get_the_title($first_item)) ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;" />
    </div>
    <?php
            endif;
        endif;
    ?>
    <?php
    // program section
    $posts = new WP_Query([
        'post_type'=>'post_program',
        'posts_per_page'=>3,
        'post_status'=>'publish'
    ]);
    if($posts->have_posts()):
    ?>
    <div>
        <div class="w-limit row gap-24">
            <div class="s-col-2 middle">
                <div class="flex middle">
                    <h3 class="single-line">
                        프로그램
                    </h3>
                </div>
                <div class="flex middle right">
                    <a href="<?= getPage("program")->permalink ?? "/" ?>" class="text-sub">
                        <?= icon("arrow/right") ?>
                    </a>
                </div>
            </div>
            <div class="gallery-grid m-hori_scroll [&>*]:basis-[240px]">
                <?=
            comp("program-item",["posts"=>$posts]);
            ?>
            </div>
        </div>
    </div>
    <?php
    wp_reset_postdata();
        endif;
    ?>
    <?php
        $posts = new WP_Query([
            'post_type'=>'post_exhibition',
            'posts_per_page'=>3,
            'post_status'=>'publish'
        ]);
        if($posts->have_posts()):
    ?>
    <div>
        <div class="w-limit row gap-24">
            <div class="s-col-2 middle">
                <div class="flex middle">
                    <h3 class="single-line">
                        전시
                    </h3>
                </div>
                <div class="flex middle right">
                    <a href="<?= getPage("exhibition")->permalink ?? "/" ?>" class="text-sub">
                        <?= icon("arrow/right") ?>
                    </a>
                </div>
            </div>
            <div class="gallery-grid m-hori_scroll [&>*]:basis-[240px]">
                <?=
            comp("exhibition-item",["posts"=>$posts]);
            ?>
            </div>
        </div>
    </div>
    <?php
    wp_reset_postdata(  );
        endif;
    ?>

    <?php
            $posts = new WP_Query([
                'post_type'=>'post_activity',
                'posts_per_page'=>3,
                'post_status'=>'publish'
            ]);
            if($posts->have_posts()):
    ?>
<!-- 활동 -->
<div class="row gap-24">
    <div class="w-limit row gap-24">
        <div class="s-col-2 middle">
            <div class="flex middle">
                <h3 class="single-line">
                    활동
                </h3>
            </div>
            <div class="flex middle right">
                <a href="<?= getPage("activity")->permalink ?? "/" ?>" class="text-sub">
                    <?= icon("arrow/right") ?>
                </a>
            </div>
        </div>
    </div>
    <div class="swiper main_activity" data-slidesperview="6.5" data-mslidesperview="3.5" data-loop="1" fade>
        <div class="swiper-wrapper flex middle">
            <?php
            $acts = get_posts(["post_type"=>"post_activity","post_status"=>"publish"]);
        for($i=1;$i<6;$i++): ?>
            <a href="<?= getPage("activity")->permalink ?? "/" ?>" class="swiper-slide">
                <?php
                    if(isset($acts[$i-1])):
                ?>
                <img src="<?= _acf("gallery",$acts[$i-1]->ID)[0]["sizes"]["medium"] ?>" />
                <?php
                    else:
                ?>
                <img src="<?= getImg("academy/image-{$i}.png") ?>" />
                <?php
                    endif;
                ?>
            </a>
        <?php endfor; ?>
        </div>
    </div>
</div>
<?php
    endif;
?>
</div>

</div>

<?php
get_footer();