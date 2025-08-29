<?php
/**
 * @link https://vcode-studio.com/
 *
 * @package Vcode-studio
 */
get_header();
$posts = getWhatsOn();
$sang_sliders = get_posts([
    "post_type"=>["post_exhibition","post_program"],
    "post_status"=>"publish",
    "meta_query"=>[
        [
            "key"=>"permanent",
            "value"=>1
        ]
    ]
]);
?>
<style>
    .content_wrap {
        margin-bottom:0px;
    }
</style>
<div class="what-fadeout"></div>
<div class="what row gap-0">
    <?php foreach($posts as $i=>$post):
        $desc = get_field("desc",$post);
        ?>
    <?php if(getDateState($post)['slug'] == 'before'): ?>
    <a href="<?= get_permalink($post) ?>" class="item-2" style="background-image:url(<?= _acf("thumb_what_come")["sizes"]["full"] ?? _acf("thumb",$post)["sizes"]["large"] ?>)">
        <div class="wrap">
            <div class="row gap-18 w-limit">
                <div class="flex gap-8 middle">
                    <h5 class="bold">Comming Up</h5>
                    <?= comp("tags",["post"=>$post,"class"=>"ghost small bold"]) ?>
                </div>
                <div class="line-w"></div>
                <h5 class="right">
                    <?= $post->post_title ?>
                </h5>
            </div>
        </div>
    </a>
    <?php else: ?>
    <div class="item">
        <div class="text_wrap">
            <div class="text row gap-1r">
                <?= comp("tags",["post"=>$post,"class"=>"ghost small"]) ?>
                <h3><?= $post->post_title ?></h3>
                <?php
                if($desc):
                $desc = wp_strip_all_tags($desc);
                $desc = explode(" ",$desc);
                if(count($desc) > 30) {
                    $desc = array_slice($desc,0,30);
                    $desc = implode(" ",$desc);
                    $desc .= " ...";
                } else {
                    $desc = implode(" ",$desc);
                }
                ?>
                <div class="desc">
                    <?= $desc ?>
                </div>
                <?php endif; ?>
                <div class="col gap-24 links" style="margin-top:.5em">
                    <a href="<?= get_permalink($post) ?>" class="bold">상세정보</a>
                    <?php if((getDateState($post)["slug"] != "end" )&& ($f = get_field("book",$post))): ?>
                    <a href="<?= $f ?>" class="bold">예매하기</a>
                    <?php endif; ?>
                </div>
                <div class="flex-auto">
                </div>
                <?php if(($f = get_field("relative-program",$post)) && count($f)): ?>
                <div class="row gap-8 relative-programs">
                    <p class="bold">관련 프로그램</p>
                    <div class="sliced-bottom invert board-small row gap-0">
                        <?php foreach($f as $item_): ?>
                        <a href="<?= get_permalink($item_) ?>" class="col gap-18 middle caption">
                            <div class="board-title middle">
                                <?= $item_->post_title ?>
                            </div>
                            <div class="arrow">
                                <?= icon("arrow/right_w","fit-height") ?>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="thumb">
        <?= img(_acf("thumb_what",$post),"whats_on_thumb",_acf("thumb",$post)) ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php
        if(count($sang_sliders) > 0):
    ?>
    <div class="item-ext">
        <div class="w-limit row gap-18">
            <div class="flex">
                <h3 class="bold flex-auto">
                    상설 전시와 프로그램
                </h3>
                <div class="flex right middle flex-none pc">
                    <!-- <div class="flex gap-12">
                        <div class="button clean" click="sliders['gall'].slideNext()">
                            <?= icon("arrow/left_w") ?>
                        </div>
                        <div class="button clean" click="sliders['gall'].slidePrev()">
                            <?= icon("arrow/right_w") ?>
                        </div>
                    </div> -->
                    <?php
                        if(count($sang_sliders) >= 3):
                    ?>
                    <p class="light">
                        <span class="idx" target="sang">01</span> / <?= sprintf("%02d",count($sang_sliders)) ?>
                    </p>
                    <?php
                        endif;
                    ?>
                </div>
            </div>
            <div class="gallery-chip swiper swiper-container" data-name="sang" data-slidesPerView="3" data-loop="<?= (count($sang_sliders) < 3)?"0":"1" ?>" data-name="gall">
                <div class="swiper-wrapper">
                    <?php foreach($sang_sliders as $i=>$item): ?>
                    <a href="<?= get_permalink($item) ?>" class="item swiper-slide static">
                        <div class="thumb">
                            <img src="<?= _acf("thumb",$item)["sizes"]["large"] ?>"/>
                        </div>
                        <div class="row gap-12 text">
                            <?= comp("tags",["class"=>"ghost small","post"=>$item]) ?>
                            <h5 class="bold title"><?= get_the_title($item) ?></h5>
                            <p class="single-line right">
                                <?= icon("arrow/right_w") ?>
                            </p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
        endif;
    ?>
</div>
<?php
get_footer();