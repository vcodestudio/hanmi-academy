<?php
/**
 * 이미지 컴포넌트 (캐싱 적용)
 * WordPress의 이미지 관련 함수 호출을 캐싱하여 성능 최적화
 */
static $img_data_cache = [];

$img_id = $arg['id'] ?? 0;
$img_size = $arg['size'] ?? 'large';
$cache_key = $img_id . '_' . $img_size;

if (!isset($img_data_cache[$cache_key])) {
    $img_data_cache[$cache_key] = [
        'url' => wp_get_attachment_image_url($img_id, $img_size),
        'srcset' => wp_get_attachment_image_srcset($img_id, $img_size),
        'sizes' => wp_get_attachment_image_sizes($img_id, $img_size),
    ];
}

$cached = $img_data_cache[$cache_key];
?>
<img src="<?php echo esc_url($cached['url']); ?>"
     srcset="<?php echo esc_attr($cached['srcset']); ?>"
     sizes="<?php echo esc_attr($cached['sizes']); ?>"
     />