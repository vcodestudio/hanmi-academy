<?php
get_header();
$main_video_pc = function_exists('get_field') ? get_field("main_video_pc","option") : null;
$main_video_mobile = function_exists('get_field') ? get_field("main_video_mobile","option") : null;
?>
<style>
    .content_wrap {
        margin-bottom: 0px;
    }
    .main-banner-video {
        width: 100%;
        position: relative;
        overflow: hidden;
    }
    .main-banner-video video {
        width: 100%;
        /* 높이는 JavaScript로 동적으로 설정됨 (헤더 높이 제외한 윈도우 높이) */
        object-fit: cover;
        display: block;
    }
    .main-banner-video .video-pc {
        display: block;
    }
    .main-banner-video .video-mobile {
        display: none;
    }
    @media (max-width: 768px) {
        .main-banner-video .video-pc {
            display: none;
        }
        .main-banner-video .video-mobile {
            display: block;
        }
    }
</style>
<div class="index">
    <?php
        if($main_video_pc || $main_video_mobile):
            $video_pc_url = $main_video_pc ? (is_array($main_video_pc) ? $main_video_pc['url'] : $main_video_pc) : null;
            $video_mobile_url = $main_video_mobile ? (is_array($main_video_mobile) ? $main_video_mobile['url'] : $main_video_mobile) : null;
    ?>
    <div class="main-banner-video">
        <?php if($video_pc_url): ?>
        <video 
            class="video-pc" 
            autoplay 
            muted 
            playsinline 
            loop
            style="width: 100%; object-fit: cover;"
        >
            <source src="<?= esc_url($video_pc_url) ?>" type="video/mp4">
        </video>
        <?php endif; ?>
        <?php if($video_mobile_url): ?>
        <video 
            class="video-mobile" 
            autoplay 
            muted 
            playsinline 
            loop
            style="width: 100%; object-fit: cover;"
        >
            <source src="<?= esc_url($video_mobile_url) ?>" type="video/mp4">
        </video>
        <?php elseif($video_pc_url): ?>
        <video 
            class="video-mobile" 
            autoplay 
            muted 
            playsinline 
            loop
            style="width: 100%; object-fit: cover;"
        >
            <source src="<?= esc_url($video_pc_url) ?>" type="video/mp4">
        </video>
        <?php endif; ?>
    </div>
    <?php endif; ?>
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
<!-- 갤러리 -->
<div class="row gap-24">
    <div class="w-limit row gap-24">
        <div class="s-col-2 middle">
            <div class="flex middle">
                <h3 class="single-line">
                    갤러리
                </h3>
            </div>
            <div class="flex middle right">
                <a href="<?= getPage("activity")->permalink ?? "/" ?>" class="text-sub">
                    <?= icon("arrow/right") ?>
                </a>
            </div>
        </div>
    </div>
    <div class="swiper main_activity" data-slidesperview="6.5" data-mslidesperview="2.2" data-loop="1" fade>
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