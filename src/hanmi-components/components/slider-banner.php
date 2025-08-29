<?php
    if($arg["imgs"] ?? false):
?>
<div class="swiper banner swiper-container">
    <div class="swiper-wrapper">
        <?php
        $args = $arg["imgs"]?$arg["imgs"]:[];
        foreach($args as $item):
        ?>
        <div class="swiper-slide">
            <img src="<?= $item["sizes"]["detail_slider"] ?? $item["sizes"]["large"] ?? getImg("empty.svg") ?>" zoom />
        </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>
        <?php
            endif;
        ?>