<?php
$post_id = get_the_ID();
$post_type = get_post_type($post_id);

// 썸네일 가져오기 (ACF thumb 또는 featured image)
$thumb = _acf("thumb");
$thumb_url = '';
if (is_array($thumb) && isset($thumb["sizes"]["large"])) {
    $thumb_url = $thumb["sizes"]["large"];
} elseif (is_numeric($thumb)) {
    // ID인 경우
    $thumb_url = wp_get_attachment_image_url($thumb, 'large');
} elseif (has_post_thumbnail($post_id)) {
    $thumb_url = get_the_post_thumbnail_url($post_id, 'large');
}
if (!$thumb_url) {
    $thumb_url = getImg();
}

// 갤러리 가져오기 (활동사진 등 thumb이 없는 경우)
if (!$thumb_url || $thumb_url === getImg()) {
    $gallery = _acf("gallery");
    if (is_array($gallery) && count($gallery) > 0) {
        $first_img = $gallery[0];
        if (is_numeric($first_img)) {
            $thumb_url = wp_get_attachment_image_url($first_img, 'large');
        } elseif (is_array($first_img) && isset($first_img["sizes"]["large"])) {
            $thumb_url = $first_img["sizes"]["large"];
        }
    }
}

// 장소 정보
$location = _acf("location");
$location_name = '';
if (is_object($location) && isset($location->name)) {
    $location_name = $location->name;
} elseif (is_array($location) && isset($location['name'])) {
    $location_name = $location['name'];
}

// 포스트 타입 라벨 (프론트엔드용으로 변환)
$post_type_obj = get_post_type_object($post_type);
$type_label = $post_type_obj->labels->singular_name ?? $post_type_obj->label ?? '';
// 관리자용 라벨 변환
$type_label_map = [
    '전시관리' => '전시',
    '프로그램관리' => '프로그램',
];
if (isset($type_label_map[$type_label])) {
    $type_label = $type_label_map[$type_label];
}

// 날짜 정보
$start_date = _acf("start");
$end_date = _acf("end");
$has_date = !empty($start_date) || !empty($end_date);
?>
<a href="<?= esc_url(get_permalink($post_id)) ?>" class="item flex gap-24 reg">
<div class="flex-none">
    <img src="<?= esc_url($thumb_url) ?>" alt="<?= esc_attr(get_the_title()) ?>"/>
</div>
<div class="flex-auto flex middle">
    <div class="row gap-16 single-line">
        <?php if ($location_name || $type_label): ?>
        <div class="divider">
            <?php if ($location_name): ?>
            <h6><?= esc_html($location_name) ?></h6>
            <?php endif; ?>
            <?php if ($type_label): ?>
            <h6><?= esc_html($type_label) ?></h6>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <h4><?= get_the_title() ?></h4>
        <?php if ($has_date): ?>
        <p class="fade">
            <?= esc_html($start_date) ?><?= $end_date ? ' - ' . esc_html($end_date) : '' ?>
        </p>
        <?php endif; ?>
    </div>
</div>
</a>