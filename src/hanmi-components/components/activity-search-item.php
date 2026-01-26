<?php
/**
 * 활동사진 검색 결과 아이템 컴포넌트
 */
$post_id = get_the_ID();
$gallery = _acf("gallery");
$thumb_url = '';

// 갤러리에서 첫 번째 이미지 가져오기
if (is_array($gallery) && count($gallery) > 0) {
    $first_img = $gallery[0];
    if (is_numeric($first_img)) {
        // ID인 경우
        $thumb_url = wp_get_attachment_image_url($first_img, 'medium');
    } elseif (is_array($first_img)) {
        // 배열인 경우
        $thumb_url = $first_img["sizes"]["medium"] ?? $first_img["sizes"]["thumb"] ?? $first_img["url"] ?? '';
    }
}

// 썸네일이 없으면 기본 이미지
if (!$thumb_url) {
    $thumb_url = getImg();
}

// 날짜 정보
$start_date = _acf("start");
$end_date = _acf("end");
?>
<a href="<?= esc_url(get_permalink($post_id)) ?>" class="activity-item" style="display: block; text-decoration: none; color: inherit;">
    <div class="thumb" style="aspect-ratio: 1; overflow: hidden; border-radius: 4px; margin-bottom: 12px;">
        <img src="<?= esc_url($thumb_url) ?>" alt="<?= esc_attr(get_the_title()) ?>" style="width: 100%; height: 100%; object-fit: cover;" />
    </div>
    <div class="info">
        <h6 style="margin: 0 0 4px 0; font-size: 14px;"><?= get_the_title() ?></h6>
        <?php if ($start_date): ?>
        <p class="date fade" style="margin: 0; font-size: 12px; color: #888;"><?= esc_html($start_date) ?><?= $end_date ? ' - ' . esc_html($end_date) : '' ?></p>
        <?php endif; ?>
    </div>
</a>
