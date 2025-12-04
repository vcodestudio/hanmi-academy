<?php
get_header();

// 더미 데이터
$order_id = $_GET['order_id'] ?? 'ORD-001';
$order = [
    'date' => '2025.11.26',
    'title' => '큐레이터가 알려주는 미술작품 보는 법',
    'schedule' => '매주 수요일 · 오후 3시',
    'image' => function_exists('getImg') ? getImg('empty.svg') : (SRC . '/imgs/system/empty.svg'),
    'price' => 50000,
    'quantity' => 1,
];

$buyer = [
    'name' => '김한미',
    'email' => 'abc1234 @ abc.com',
    'phone' => '010 1234 5678'
];

$payment = [
    'amount' => 50000,
    'method' => '신용카드',
    'date' => '2025.11.26'
];
?>

<style>
.payment-detail-container {
    display: flex;
    flex-direction: column;
    gap: 32px;
    align-items: flex-start;
    width: 100%;
}

.payment-detail-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
    align-items: flex-start;
    width: 100%;
}

.payment-detail-order-date {
    font-family: 'Pretendard Variable', sans-serif;
    font-weight: 700;
    font-size: 16px;
    line-height: 24px;
    color: #000000;
    letter-spacing: -0.2px;
    white-space: pre;
}

.payment-detail-order-item {
    display: flex;
    gap: 24px;
    align-items: center;
    width: 100%;
}

.payment-detail-order-image {
    width: 170px;
    height: 170px;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.payment-detail-order-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: 50% 50%;
}

.payment-detail-order-content {
    flex: 1;
    display: flex;
    flex-direction: row;
    align-items: center;
    align-self: stretch;
}

.payment-detail-order-details {
    flex: 1;
    display: flex;
    height: 100%;
    align-items: center;
    justify-content: space-between;
    padding-right: 40px;
}

.payment-detail-order-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    align-items: flex-start;
    justify-content: space-between;
    letter-spacing: -0.2px;
}

.payment-detail-order-title {
    font-family: 'Pretendard Variable', sans-serif;
    font-weight: 700;
    font-size: 24px;
    line-height: 36px;
    color: #000000;
    min-width: 100%;
}

.payment-detail-order-schedule {
    font-family: 'Pretendard', sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: #888888;
    white-space: pre;
}

.payment-detail-order-price {
    font-family: 'Pretendard', sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: #888888;
    white-space: pre;
}

.payment-detail-divider {
    background: rgba(0, 0, 0, 0.1);
    height: 1px;
    width: 100%;
}

.payment-detail-section-title {
    font-family: 'Pretendard Variable', sans-serif;
    font-weight: 700;
    font-size: 16px;
    line-height: 24px;
    color: #000000;
    letter-spacing: -0.2px;
    white-space: pre;
}

.payment-detail-info-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
    width: 100%;
}

.payment-detail-info-item {
    display: flex;
    font-family: 'Pretendard Variable', sans-serif;
    font-weight: 700;
    font-size: 16px;
    line-height: 24px;
    color: #000000;
    text-align: center;
    letter-spacing: -0.2px;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

.payment-detail-info-item-name {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.payment-detail-info-item-value {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.payment-detail-info-item p {
    line-height: 24px;
    white-space: pre;
    text-align: left;
}

.payment-detail-info-item-name p {
    text-nowrap: true;
}

.payment-detail-info-item-value p {
    text-nowrap: true;
}

.payment-detail-info-item-name-30 {
    height: 30px;
    align-items: center;
}

.payment-detail-info-item-name-24 {
    height: 24px;
    align-items: flex-start;
}

.payment-detail-info-item-name-24-center {
    height: 24px;
    align-items: center;
}

@media (max-width: 765px) {
    .payment-detail-order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .payment-detail-order-image {
        width: 100%;
        max-width: 170px;
        height: auto;
        aspect-ratio: 1;
    }
    
    .payment-detail-order-content {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
    }
    
    .payment-detail-order-details {
        padding-right: 0;
        height: auto;
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .payment-detail-order-info {
        height: auto;
        width: 100%;
        gap: 8px;
    }
    
    .payment-detail-order-title {
        font-size: 20px;
        line-height: 30px;
    }
}
</style>

<div class="page-wrap row gap-32">
    <div class="row gap-24">
        <h3 class="bold">프로그램</h3>
    </div>
    
    <div class="payment-detail-container">
        <!-- 주문일자 및 주문 내역 -->
        <div class="payment-detail-section">
            <div class="payment-detail-order-date">주문일자 <?= esc_html($order['date']) ?></div>
            
            <div class="payment-detail-order-item">
                <div class="payment-detail-order-image">
                    <img src="<?= esc_url($order['image']) ?>" alt="<?= esc_attr($order['title']) ?>" />
                </div>
                
                <div class="payment-detail-order-content">
                    <div class="payment-detail-order-details">
                        <div class="payment-detail-order-info">
                            <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-start; width: 100%;">
                                <p class="payment-detail-order-title"><?= esc_html($order['title']) ?></p>
                                <div class="payment-detail-order-schedule"><?= esc_html($order['schedule']) ?></div>
                            </div>
                            <div class="payment-detail-order-price"><?= number_format($order['price']) ?>원 / <?= $order['quantity'] ?>매</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="payment-detail-divider"></div>
        
        <!-- 예매자 정보 -->
        <div class="payment-detail-section">
            <div class="payment-detail-section-title">예매자 정보</div>
            
            <div class="payment-detail-info-list">
                <!-- 이름 -->
                <div class="payment-detail-info-item payment-detail-info-item-name-30">
                    <div class="payment-detail-info-item-name">
                        <p>이름</p>
                    </div>
                    <div class="payment-detail-info-item-value">
                        <p><?= esc_html($buyer['name']) ?></p>
                    </div>
                </div>
                
                <!-- 이메일 -->
                <div class="payment-detail-info-item payment-detail-info-item-name-24">
                    <div class="payment-detail-info-item-name">
                        <p>이메일</p>
                    </div>
                    <div class="payment-detail-info-item-value">
                        <p><?= esc_html($buyer['email']) ?></p>
                    </div>
                </div>
                
                <!-- 휴대폰 -->
                <div class="payment-detail-info-item payment-detail-info-item-name-24-center">
                    <div class="payment-detail-info-item-name">
                        <p>휴대폰</p>
                    </div>
                    <div class="payment-detail-info-item-value">
                        <p><?= esc_html($buyer['phone']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="payment-detail-divider"></div>
        
        <!-- 결제 정보 -->
        <div class="payment-detail-section">
            <div class="payment-detail-section-title">결제 정보</div>
            
            <div class="payment-detail-info-list">
                <!-- 주문금액 -->
                <div class="payment-detail-info-item payment-detail-info-item-name-30">
                    <div class="payment-detail-info-item-name">
                        <p>주문금액</p>
                    </div>
                    <div class="payment-detail-info-item-value">
                        <p><?= number_format($payment['amount']) ?>원</p>
                    </div>
                </div>
                
                <!-- 결제수단 -->
                <div class="payment-detail-info-item payment-detail-info-item-name-24">
                    <div class="payment-detail-info-item-name">
                        <p>결제수단</p>
                    </div>
                    <div class="payment-detail-info-item-value">
                        <p><?= esc_html($payment['method']) ?></p>
                    </div>
                </div>
                
                <!-- 결제일시 -->
                <div class="payment-detail-info-item payment-detail-info-item-name-24-center">
                    <div class="payment-detail-info-item-name">
                        <p>결제일시</p>
                    </div>
                    <div class="payment-detail-info-item-value">
                        <p><?= esc_html($payment['date']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
