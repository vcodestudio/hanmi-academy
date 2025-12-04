<?php
// 더미 데이터
$orders = [
    [
        'date' => '2025.11.26',
        'title' => '큐레이터가 알려주는 미술작품 보는 법',
        'schedule' => '매주 수요일 · 오후 3시',
        'image' => function_exists('getImg') ? getImg('empty.svg') : (SRC . '/imgs/system/empty.svg'),
        'price' => 50000,
        'quantity' => 1,
        'order_id' => 'ORD-001'
    ],
    [
        'date' => '2025.11.26',
        'title' => '큐레이터가 알려주는 미술작품 보는 법',
        'schedule' => '매주 수요일 · 오후 3시',
        'image' => function_exists('getImg') ? getImg('empty.svg') : (SRC . '/imgs/system/empty.svg'),
        'price' => 50000,
        'quantity' => 1,
        'order_id' => 'ORD-002'
    ],
];
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
                                <div class="payment-history-item-price"><?= number_format($order['price']) ?>원 / <?= $order['quantity'] ?>매</div>
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
