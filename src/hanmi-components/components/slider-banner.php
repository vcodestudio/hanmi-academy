<?php
    if($arg["imgs"] ?? false):
    $objectFit = $arg["objectFit"] ?? 'contain'; // 기본값: contain
    $objectFitClass = 'object-fit-' . $objectFit;
?>
<style>
/* 배너 슬라이더 이미지 스타일 */
.banner .swiper-wrapper {
    background: transparent !important;
}
.banner .swiper-slide {
    background: transparent !important;
    position: relative;
}
.banner .swiper-slide img {
    object-fit: <?= $objectFit ?> !important;
    background-color: transparent !important;
    width: 100% !important;
    max-height: 100%;
    margin: 0 auto;
    display: block;
}
@media (max-width: 765px) {
    .banner .swiper-slide img {
        max-height: 400px !important;
        height: auto !important;
    }
}
@media (max-width: 650px) {
    .banner .swiper-slide img {
        max-height: 300px !important;
        height: auto !important;
    }
}

/* 배너 캡션 스타일 */
.banner-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 12px 16px;
    font-size: 14px;
    line-height: 1.4;
    z-index: 10;
}
.banner-caption p {
    margin: 0;
    font-weight: 400;
}
@media (max-width: 765px) {
    .banner-caption {
        padding: 8px 12px;
        font-size: 13px;
    }
}
</style>
<?php
    $args = $arg["imgs"] ? $arg["imgs"] : [];
    $forceSlider = $arg["forceSlider"] ?? false; // 강제로 슬라이더 모드 사용
    $isGallery = is_array($args) && (count($args) > 1 || $forceSlider);
    $hidePagination = $arg["hidePagination"] ?? false;
    $showBullets = $arg["showBullets"] ?? false; // 불릿 가시성 제어 (기본값: false)
    $imgCount = is_array($args) ? count($args) : 0;
    // forceSlider가 true이고 이미지가 있을 때 슬라이더가 작동하도록 설정
    $sliderDataAttrs = '';
    if ($forceSlider && $imgCount > 0) {
        $sliderDataAttrs = 'data-slidesperview="1" data-spacebetween="0"';
        // 이미지가 2개 이상일 때만 loop 활성화
        if ($imgCount > 1) {
            $sliderDataAttrs .= ' data-loop="1"';
        }
    }
?>
<div class="swiper banner swiper-container" <?= $isGallery ? 'gallery' : '' ?> <?= $sliderDataAttrs ?> data-name="banner">
    <div class="swiper-wrapper">
        <?php foreach($args as $index => $item): 
            // ID인 경우와 배열인 경우 모두 처리 (하위 호환성 유지)
            $img_id = is_array($item) ? ($item['id'] ?? $item['ID'] ?? 0) : (is_numeric($item) ? (int)$item : 0);
            
            if ($img_id) {
                // ID로 필요한 정보만 가져오기 (성능 최적화)
                $src = wp_get_attachment_image_url($img_id, 'large') ?: wp_get_attachment_image_url($img_id, 'full') ?: getImg("empty.svg");
                $caption = wp_get_attachment_caption($img_id) ?: "";
            } else if (is_array($item)) {
                // 기존 배열 형식 지원 (하위 호환성)
                $src = $item["sizes"]["large"] ?? ($item["sizes"]["detail_slider"] ?? ($item["sizes"]["full"] ?? ($item["url"] ?? getImg("empty.svg"))));
                $caption = $item["caption"] ?? "";
            } else {
                continue;
            }
            
            // 전시 상세 페이지의 메인 슬라이더는 첫 2개 이미지를 즉시 로드하여 부드러운 전환 보장
            // 나머지는 lazy loading으로 초기 로딩 시간 단축
            $loading_attr = $index < 2 ? '' : 'loading="lazy"';
        ?>
        <div class="swiper-slide">
            <img src="<?= esc_url($src) ?>" <?= $isGallery ? '' : 'zoom' ?> alt="<?= esc_attr($caption) ?>" <?= $loading_attr ?> />
            <?php if ($caption): ?>
                <div class="banner-caption" style="display: none;">
                    <p><?= esc_html($caption) ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <?php if(!$hidePagination && $showBullets): ?>
    <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
        <?php
            endif;
        ?>