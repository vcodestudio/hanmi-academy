<?php
get_header();
$sliders = get_field("main_slider","option");
$sliders = $sliders?$sliders:[];
?>
<style>
    .content_wrap {
        margin-bottom: 0px;
    }
</style>
<div class="index">
    <?php
        $sl_autoplay=5000;
        if(count($sliders) > 0):
    ?>
    <div class="s-1 main-slider slider-wrap swiper swiper-container" data-name="main" data-slidesperview="1"
        data-auto="<?= (count($sliders) > 1)?"1":"0" ?>" data-effect="fade"
        data-loop="<?= (count($sliders) > 1)?"1":"0" ?>">
        <div class="swiper-wrapper">
            <?php
            foreach($sliders as $item): ?>
            <div class="swiper-slide" data-swiper-autoplay="<?= $sl_autoplay ?>">
                <?php
                    $video = _acf("video", $item->ID);
                    $poster = _acf("video_poster", $item->ID) ?: _acf("thumb_main_slider", $item->ID);
                    $video_url = is_array($video) ? ($video["url"] ?? "") : ($video ?: "");
                    if ($video_url):
                ?>
                <video class="banner-video" muted playsinline preload="metadata" poster="<?= esc_url(is_array($poster)?($poster["url"]??""):$poster) ?>">
                    <source src="<?= esc_url($video_url) ?>" type="video/mp4" />
                </video>
                <?php else: ?>
                <?= img(_acf("thumb_main_slider",$item->ID),"main-slider",_acf("thumb",$item->ID)) ?>
                <?php endif; ?>
            </div>
            <?php
            endforeach;
            ?>
        </div>
        <div class="w-limit text">
            <?php
                foreach($sliders as $idx => $item):
            ?>
            <div class="item row gap-8 <?= ($idx == 0)?'active':'' ?>">
                <?= comp("tags",["post"=>$item->ID,"class"=>"transparent small"]) ?>
                <h3 class="w"><?= get_the_title($item) ?></h3>
                <a href="<?= get_permalink($item) ?>" class="button w line">
                    자세히 알아보기
                </a>
            </div>
            <?php
                endforeach;
            ?>
        </div>
        <?php
            $maxsl = count($sliders);
            $curnum = min(1,$maxsl);
            if((count($sliders) > 1)):
        ?>
        <div class="slider-pagination">
            <p class="light">
                <span class="idx" target="main"><?= sprintf("%02d",$curnum) ?></span> of <?= sprintf("%02d",$maxsl) ?>
            </p>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <?php
            endif;
        ?>
    </div>
    <?php
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
            <div class="gallery-grid m-hori_scroll">
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
            <div class="gallery-grid m-hori_scroll">
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