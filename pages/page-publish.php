<?php
get_header();
?>
<div class="row gap-32 page-wrap">
    <h3><?= get_the_title() ?></h3>
    <div>
        <?= comp("tab_submenu") ?>
    </div>
    <div class="col gap-24">
        <h4>출판사업</h4>
        <p>
        가현문화재단에서는 예술적, 학술적 및 역사적 가치가 있는 사진을 국내외에 널리 소개하고, 사진과 관련된 시각 문화에 대한 연구 성과를 공유하고자 전시뿐만 아니라 출판 사업에도 많은 노력을 기울여왔습니다. 지금까지 가현문화재단에서 발간한 출판물은 사진 도입 초기의 한국 근대 사진부터 한국 사진의 토대를 만든 사진가들의 사진과 동시대의 가장 젊은 작가들까지, 한국 사진의 중요한 부분들을 아우르며 조명하고 있습니다. 그리고《Camera Work》 총서, 한국근대사진 시리즈, 특별전 도록 등 다양하게 발간되는 도록과 사진집은 사진에 대한 전문적인 이해를 바탕으로, 원작을 최대한 가깝게 느낄 수 있도록 정성을 들여 가장 좋은 품질로 선보입니다.
        </p>
    </div>
    <hr/>
    <div class="row gap-24">
        <h4>신간</h4>
        <div class="swiper swiper-container publish-slider" data-slidesPerView="2" data-mslidesPerView="1.5" data-loop="1">
            <div class="swiper-wrapper">
                <?php
                $posts = new WP_Query([
                    "post_type"=>"post_book",
                    "post_status"=>"publish",
                    "posts_per_page"=>5,
                    "order"=>"ASC"
                ]);
                while($posts->have_posts()):$posts->the_post();
                ?>
                <a href="<?= get_permalink() ?>" class="swiper-slide col-2 gap-1r item">
                    <div class="thumb">
                        <img <?= acf_img_set(_acf("thumb"),"medium") ?>/>
                    </div>
                    <div class="row gap-8">
                        <h4><?= get_the_title() ?></h4>
                        <h6 class="light"><?= _acf("author") ?></h6>
                        <h6 class="light text-sub"><?= _acf("publish") ?> 발행</h6>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</div>
<?php get_footer(); ?>