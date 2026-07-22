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
            <div class="gallery-grid m-hori_scroll" data-mslidesperview="1.04">
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
            <div class="gallery-grid m-hori_scroll" data-mslidesperview="1.04">
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
    <div class="swiper main_activity" data-slidesperview="6.5" data-mslidesperview="2.15" data-mspacebetween="8" fade>
        <div class="swiper-wrapper flex middle">
            <?php
            // 발행된 활동사진 전체를 노출(기존: 5장 하드코딩 + loop로 반복 노출되던 문제 수정).
            // 각 슬라이드는 page-activity와 동일한 gallery 라이트박스 구조로, 클릭 시 목록 탭 이동이
            // 아니라 그 게시물의 이미지가 오버레이(footer .img_overlay.gall_overlay)로 바로 열린다.
            $acts = get_posts(["post_type"=>"post_activity","post_status"=>"publish","numberposts"=>-1]);
            foreach($acts as $act):
                $imgs = _acf("gallery", $act->ID);
                if(!$imgs || !is_array($imgs)) continue;
            ?>
            <div class="swiper-slide">
                <div class="thumb" gallery style="position: relative; cursor: pointer;">
                    <?php foreach($imgs as $idx=>$img):
                        $src = $img["sizes"]["medium"] ?? $img["url"] ?? "";
                        if(!$src) continue;
                        $cap = $img["caption"] ?? $img["alt"] ?? $act->post_title;
                    ?>
                    <img src="<?= esc_url($src) ?>" alt="<?= esc_attr($cap) ?>"<?= $idx === 0 ? "" : ' style="display:none;"' ?> />
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
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