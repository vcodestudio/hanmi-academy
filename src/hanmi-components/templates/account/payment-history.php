<?php
// 현재 로그인한 사용자 ID
$current_user_id = get_current_user_id();

// DB에서 사용자별 주문 목록 가져오기
$orders = array();

if ($current_user_id > 0) {
    $order_posts = get_posts(array(
        'post_type' => 'post_order',
        'meta_query' => array(
            array(
                'key' => 'order_user_id',
                'value' => $current_user_id,
                'compare' => '='
            )
        ),
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish'
    ));
    
    foreach ($order_posts as $order_post) {
        $order_post_id = $order_post->ID;
        $program_id = get_field('order_program_id', $order_post_id);
        $tran_date = get_field('order_tran_date', $order_post_id);
        
        // 날짜 포맷팅
        $order_date = '';
        if ($tran_date) {
            if (strlen($tran_date) >= 8) {
                $year = substr($tran_date, 0, 4);
                $month = substr($tran_date, 4, 2);
                $day = substr($tran_date, 6, 2);
                $order_date = $year . '.' . $month . '.' . $day;
            }
        }
        if (empty($order_date)) {
            $order_date = get_the_date('Y.m.d', $order_post_id);
        }
        
        // 프로그램 정보 가져오기
        $program_title = get_field('order_goods_name', $order_post_id);
        $schedule = '';
        $image_url = function_exists('getImg') ? getImg('empty.svg') : (SRC . '/imgs/system/empty.svg');
        
        if ($program_id) {
            $program = get_post($program_id);
            if ($program) {
                if (empty($program_title)) {
                    $program_title = get_the_title($program_id);
                }
                
                $start_date = get_field('start', $program_id);
                $end_date = get_field('end', $program_id);
                $schedule_field = get_field('schedule', $program_id);
                
                if ($start_date) {
                    $schedule = $start_date;
                    if ($end_date && $end_date !== $start_date) {
                        $schedule .= ' ~ ' . $end_date;
                    }
                } elseif ($schedule_field) {
                    $schedule = $schedule_field;
                }
                
                // 이미지 가져오기
                $thumb = get_field('thumb', $program_id);
                if ($thumb) {
                    if (is_array($thumb) && isset($thumb['url'])) {
                        $image_url = $thumb['url'];
                    } elseif (is_numeric($thumb)) {
                        $image_url = wp_get_attachment_image_url($thumb, 'large');
                    }
                } elseif (has_post_thumbnail($program_id)) {
                    $image_url = get_the_post_thumbnail_url($program_id, 'large');
                }
            }
        }
        
        // 주문 상태 가져오기
        $order_status = get_field('order_status', $order_post_id) ?: 'pending';
        
        $orders[] = array(
            'date' => $order_date,
            'title' => $program_title ?: '상품 정보 없음',
            'schedule' => $schedule,
            'image' => $image_url,
            'price' => intval(get_field('order_amount', $order_post_id) ?: 0),
            'quantity' => intval(get_field('order_quantity', $order_post_id) ?: 1),
            'order_id' => get_field('order_mbr_ref_no', $order_post_id) ?: $order_post_id,
            'status' => $order_status
        );
    }
}
?>

<style>
.payment-history-list {
    display: flex;
    flex-direction: column;
    gap: 24px;
    align-items: flex-start;
    width: 100%;
}

.payment-history-item {
    display: flex;
    flex-direction: column;
    gap: 24px;
    align-items: flex-start;
    width: 100%;
}

.payment-history-item-date {
    font-family: 'Pretendard Variable', sans-serif;
    font-weight: 700;
    font-size: 16px;
    line-height: 24px;
    color: #000000;
    letter-spacing: -0.2px;
    white-space: pre;
}

.payment-history-item-content {
    display: flex;
    gap: 24px;
    align-items: center;
    width: 100%;
}

.payment-history-item-image {
    width: 170px;
    height: 170px;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.payment-history-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: 50% 50%;
}

.payment-history-item-details {
    flex: 1;
    display: flex;
    flex-direction: row;
    align-items: center;
    align-self: stretch;
}

.payment-history-item-info-wrapper {
    flex: 1;
    display: flex;
    height: 100%;
    align-items: center;
    justify-content: space-between;
    padding-right: 40px;
}

.payment-history-item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    align-items: flex-start;
    justify-content: space-between;
    letter-spacing: -0.2px;
}

.payment-history-item-title {
    font-family: 'Pretendard Variable', sans-serif;
    font-weight: 700;
    font-size: 24px;
    line-height: 36px;
    color: #000000;
    min-width: 100%;
}

.payment-history-item-schedule {
    font-family: 'Pretendard', sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: #888888;
    white-space: pre;
}

.payment-history-item-price {
    font-family: 'Pretendard', sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: #888888;
    white-space: pre;
    display: flex;
    align-items: center;
    gap: 27px;
}

.payment-history-item-status {
    font-family: 'Pretendard', sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    white-space: pre;
    color: #000000;
}

.payment-history-detail-button {
    background: #000000;
    border-radius: 4px;
    height: 50px;
    padding: 2px 75px;
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: center;
    width: 120px;
    flex-shrink: 0;
    text-decoration: none;
    cursor: pointer;
}

.payment-history-detail-button-text {
    font-family: 'Pretendard Variable', sans-serif;
    font-weight: 700;
    font-size: 14px;
    line-height: 24px;
    color: #FFFFFF;
    letter-spacing: -0.2px;
    white-space: pre;
}

.payment-history-divider {
    background: rgba(0, 0, 0, 0.1);
    height: 1px;
    width: 100%;
}

@media (max-width: 765px) {
    .payment-history-item-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .payment-history-item-image {
        width: 100%;
        max-width: 170px;
        height: auto;
        aspect-ratio: 1;
    }
    
    .payment-history-item-details {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
    }
    
    .payment-history-item-info-wrapper {
        padding-right: 0;
        height: auto;
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .payment-history-item-info {
        height: auto;
        width: 100%;
        gap: 8px;
    }
    
    .payment-history-item-title {
        font-size: 20px;
        line-height: 30px;
    }
    
    .payment-history-detail-button {
        width: 100%;
        margin-top: 8px;
    }
}
</style>

<?php if ( !empty($orders) ) : ?>
    <div class="payment-history-list">
        <?php foreach ($orders as $index => $order): ?>
            <div class="payment-history-item">
                <!-- 주문일자 -->
                <div class="payment-history-item-date">주문일자 <?= esc_html($order['date']) ?></div>
                
                <!-- 주문 내역 -->
                <div class="payment-history-item-content">
                    <div class="payment-history-item-image">
                        <img src="<?= esc_url($order['image']) ?>" alt="<?= esc_attr($order['title']) ?>" />
                    </div>
                    
                    <div class="payment-history-item-details">
                        <div class="payment-history-item-info-wrapper">
                            <div class="payment-history-item-info">
                                <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-start; width: 100%;">
                                    <p class="payment-history-item-title"><?= esc_html($order['title']) ?></p>
                                    <div class="payment-history-item-schedule"><?= esc_html($order['schedule']) ?></div>
                                </div>
                                <?php
                                // 주문 상태 라벨
                                $status_labels = array(
                                    'success' => '결제 완료',
                                    'cancelled' => '취소됨',
                                    'refunded' => '환불 완료',
                                    'pending' => '대기중'
                                );
                                $status_label = $status_labels[$order['status']] ?? '대기중';
                                $status_class = $order['status'] ?? 'pending';
                                $price_text = $order['price'] > 0 ? number_format($order['price']) . '원' : '무료';
                                $quantity_text = $order['quantity'] . '매';
                                
                                // 취소됨 또는 환불 완료일 때 취소선 적용
                                $is_cancelled_or_refunded = ($order['status'] === 'cancelled' || $order['status'] === 'refunded');
                                ?>
                                <div class="payment-history-item-price">
                                    <span style="<?= $is_cancelled_or_refunded ? 'text-decoration: line-through;' : '' ?>"><?= esc_html($price_text) ?> / <?= esc_html($quantity_text) ?></span>
                                    <span class="payment-history-item-status <?= esc_attr($status_class) ?>"><?= esc_html($status_label) ?></span>
                                </div>
                            </div>
                            <a href="/payment-detail?order_id=<?= esc_attr($order['order_id']) ?>" class="payment-history-detail-button">
                                <div class="payment-history-detail-button-text">결제상세</div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if ($index < count($orders) - 1): ?>
                <div class="payment-history-divider"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 24px; padding: 60px 0;">
        <p style="color: #888888; font-size: 16px; line-height: 24px;">결제 내역이 없습니다.</p>
    </div>
<?php endif; ?>
