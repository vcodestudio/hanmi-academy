<?php
get_header();

// 접근 권한 체크: 비로그인 유저가 세션 정보도 없는 경우 로그인 페이지로 리다이렉트
if (!is_user_logged_in() && empty($_SESSION['mainpay_payment_result']) && empty($_SESSION['mainpay_order_post_id'])) {
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    wp_redirect(home_url('/login?redirect_to=' . urlencode($current_url)));
    exit;
}

// 세션 시작 (안전하게)
if (session_status() === PHP_SESSION_NONE) {
    // 세션 디렉토리 확인 및 생성
    $session_dir = sys_get_temp_dir() . '/sessions';
    if (!is_dir($session_dir)) {
        @mkdir($session_dir, 0755, true);
    }
    if (is_dir($session_dir) && is_writable($session_dir)) {
        ini_set('session.save_path', $session_dir);
    }
    @session_start();
}

// 세션에서 결제 정보 가져오기 (fallback용)
$payment_result = $_SESSION['mainpay_payment_result'] ?? null;
$order_info = $_SESSION['mainpay_order'] ?? null;

// URL 파라미터에서 주문 ID 가져오기
$order_id = $_GET['order_id'] ?? ($order_info['mbrRefNo'] ?? 'ORD-001');
$order_status = $_GET['status'] ?? ($payment_result['status'] ?? 'pending');

// DB에서 주문 정보 가져오기 (우선순위)
$order_post = null;
$order_post_id = $_SESSION['mainpay_order_post_id'] ?? null;

// mbrRefNo로 주문 검색
if (!$order_post_id && !empty($order_id)) {
    $existing_orders = get_posts(array(
        'post_type' => 'post_order',
        'meta_query' => array(
            array(
                'key' => 'order_mbr_ref_no',
                'value' => $order_id,
                'compare' => '='
            )
        ),
        'posts_per_page' => 1,
        'post_status' => 'any'
    ));
    
    if (!empty($existing_orders)) {
        $order_post_id = $existing_orders[0]->ID;
    }
}

// DB에서 주문 정보 가져오기
if ($order_post_id) {
    $order_post = get_post($order_post_id);
    
    // 권한 체크: 관리자가 아니고, 주문 작성자도 아니면 접근 차단
    if ($order_post && !current_user_can('manage_options')) {
        $current_user_id = get_current_user_id();
        if ((int)$order_post->post_author !== $current_user_id) {
            // 세션에 해당 주문 정보가 있는 경우는 예외적으로 허용 (방금 결제 완료 후 리다이렉트된 경우 등)
            $session_order_id = $_SESSION['mainpay_order_post_id'] ?? null;
            if (!$session_order_id || (int)$session_order_id !== (int)$order_post_id) {
                wp_die('접근 권한이 없습니다. 본인의 주문 내역만 확인하실 수 있습니다.', '접근 권한 없음', array('response' => 403));
            }
        }
    }
    
    if ($order_post && $order_post->post_type === 'post_order') {
        // DB에서 주문 정보 가져오기
        $order_info = array(
            'mbrRefNo' => get_field('order_mbr_ref_no', $order_post_id),
            'refNo' => get_field('order_ref_no', $order_post_id),
            'amount' => get_field('order_amount', $order_post_id),
            'paymethod' => get_field('order_paymethod', $order_post_id),
            'buyerName' => get_field('order_buyer_name', $order_post_id),
            'buyerEmail' => get_field('order_buyer_email', $order_post_id),
            'buyerTel' => get_field('order_buyer_tel', $order_post_id),
            'program_id' => get_field('order_program_id', $order_post_id),
            'product_id' => get_field('order_product_id', $order_post_id),
            'goodsName' => get_field('order_goods_name', $order_post_id),
            'quantity' => get_field('order_quantity', $order_post_id) ?: 1,
        );
        
        $payment_result = array(
            'refNo' => get_field('order_ref_no', $order_post_id),
            'mbrRefNo' => get_field('order_mbr_ref_no', $order_post_id),
            'amount' => get_field('order_amount', $order_post_id),
            'tranDate' => get_field('order_tran_date', $order_post_id),
            'cardNo' => get_field('order_card_no', $order_post_id),
            'cardCode' => get_field('order_card_code', $order_post_id),
            'paymethod' => get_field('order_paymethod', $order_post_id),
            'paymethodName' => get_field('order_paymethod_name', $order_post_id),
            'installment' => get_field('order_installment', $order_post_id) ?: 0,
            'status' => get_field('order_status', $order_post_id) ?: 'pending',
            'bankCode' => get_field('order_bank_code', $order_post_id),
            'accountNo' => get_field('order_account_no', $order_post_id),
            'accountCloseDate' => get_field('order_account_close_date', $order_post_id),
        );
        
        $order_status = $payment_result['status'];
    }
}

// 결제 상태 확인
$is_cancelled = ($order_status === 'cancelled' || ($payment_result['status'] ?? '') === 'cancelled');
$is_success = ($order_status === 'success' || ($payment_result['status'] ?? '') === 'success');
$is_waiting = ($order_status === 'waiting' || ($payment_result['status'] ?? '') === 'waiting');
$is_refund_requested = ($order_status === 'refund_requested');
$is_refunded = ($order_status === 'refunded');

// 실제 상품 정보 가져오기
$program_id = $order_info['program_id'] ?? 0;
// product_id는 프로그램 ID 기반으로 생성
$product_id = $order_info['product_id'] ?? $program_id;
$program = null;

if ($program_id) {
    $program = get_post($program_id);
    if ($program && $program->post_type === 'post_program') {
        $thumb = get_field('thumb', $program_id);
        $schedule = get_field('schedule', $program_id);
        $start_date = get_field('start', $program_id);
        $end_date = get_field('end', $program_id);
        
        $date_range = '';
        if ($start_date) {
            $date_range = $start_date;
            if ($end_date && $end_date !== $start_date) {
                $date_range .= ' ~ ' . $end_date;
            }
        } elseif ($schedule) {
            $date_range = $schedule;
        }
        
        $image_url = function_exists('getImg') ? getImg('empty.svg') : (SRC . '/imgs/system/empty.svg');
        if ($thumb) {
            if (is_array($thumb) && isset($thumb['url'])) {
                $image_url = $thumb['url'];
            } elseif (is_numeric($thumb)) {
                $image_url = wp_get_attachment_image_url($thumb, 'large');
            }
        } elseif (has_post_thumbnail($program_id)) {
            $image_url = get_the_post_thumbnail_url($program_id, 'large');
        }
        
        // 상품명은 포스트 제목 사용
        $product_title = get_the_title($program_id);
        
        $tran_date = $payment_result['tranDate'] ?? '';
        $order_date = '';
        if ($tran_date) {
            // YmdHis 형식 파싱
            if (strlen($tran_date) >= 8) {
                $year = substr($tran_date, 0, 4);
                $month = substr($tran_date, 4, 2);
                $day = substr($tran_date, 6, 2);
                $order_date = $year . '.' . $month . '.' . $day;
            }
        }
        if (empty($order_date)) {
            $order_date = date('Y.m.d');
        }
        
        $order = [
            'date' => $order_date,
            'title' => $product_title,
            'schedule' => $date_range,
            'image' => $image_url,
            'price' => $order_info['amount'] ?? 0,
            'quantity' => intval($order_info['quantity'] ?? 1),
        ];
    }
}

// 주문 정보가 없으면 세션 정보 사용
if (!isset($order)) {
    $tran_date = $payment_result['tranDate'] ?? '';
    $order_date = '';
    if ($tran_date) {
        // YmdHis 형식 파싱
        if (strlen($tran_date) >= 8) {
            $year = substr($tran_date, 0, 4);
            $month = substr($tran_date, 4, 2);
            $day = substr($tran_date, 6, 2);
            $order_date = $year . '.' . $month . '.' . $day;
        }
    }
    if (empty($order_date)) {
        $order_date = date('Y.m.d');
    }
    
    $order = [
        'date' => $order_date,
        'title' => $order_info['goodsName'] ?? '상품 정보 없음',
        'schedule' => '',
        'image' => function_exists('getImg') ? getImg('empty.svg') : (SRC . '/imgs/system/empty.svg'),
        'price' => $order_info['amount'] ?? 0,
        'quantity' => intval($order_info['quantity'] ?? 1),
    ];
}

$buyer = [
    'name' => $order_info['buyerName'] ?? '고객',
    'email' => $order_info['buyerEmail'] ?? '',
    'phone' => $order_info['buyerTel'] ?? ''
];

// 결제 수단 정보 가져오기
$paymethodName = $payment_result['paymethodName'] ?? '신용카드';
if (empty($paymethodName) && isset($payment_result['paymethod'])) {
    $paymethodNames = array(
        'CARD' => '신용카드',
        'ACCT' => '실시간 계좌이체',
        'VACCT' => '가상계좌',
        'HPP' => '휴대폰 결제',
        'CULT' => '문화상품권'
    );
    $paymethodName = $paymethodNames[$payment_result['paymethod']] ?? '신용카드';
}

$payment_amount = intval($payment_result['amount'] ?? $order_info['amount'] ?? 0);
$is_free_order = ($payment_amount === 0 || ($payment_result['paymethod'] ?? '') === 'FREE');

// 거래일시 포맷팅
$tran_date = $payment_result['tranDate'] ?? '';
$payment_date = '';
if ($tran_date) {
    // YmdHis 형식 파싱
    if (strlen($tran_date) >= 14) {
        $year = substr($tran_date, 0, 4);
        $month = substr($tran_date, 4, 2);
        $day = substr($tran_date, 6, 2);
        $hour = substr($tran_date, 8, 2);
        $minute = substr($tran_date, 10, 2);
        $payment_date = $year . '.' . $month . '.' . $day . ' ' . $hour . ':' . $minute;
    } elseif (strlen($tran_date) >= 8) {
        $year = substr($tran_date, 0, 4);
        $month = substr($tran_date, 4, 2);
        $day = substr($tran_date, 6, 2);
        $payment_date = $year . '.' . $month . '.' . $day;
    }
}
if (empty($payment_date)) {
    $payment_date = date('Y.m.d H:i');
}

$payment = [
    'amount' => $payment_amount,
    'method' => $paymethodName,
    'date' => $payment_date,
    'ref_no' => $payment_result['refNo'] ?? '',
    'paymethod' => $payment_result['paymethod'] ?? 'CARD',
    'is_free' => $is_free_order
];

// 은행 코드 매핑
$bank_names = array(
    '01' => '한국은행', '02' => '산업은행', '03' => '기업은행', '04' => 'KB국민은행',
    '05' => '하나은행', '06' => '신한은행', '07' => '우리은행', '08' => 'NH농협은행',
    '09' => '케이뱅크', '10' => '카카오뱅크', '11' => '토스뱅크', '12' => 'SC제일은행',
    '13' => '대구은행', '14' => '부산은행', '15' => '광주은행', '16' => '전북은행',
    '17' => '제주은행', '18' => '경남은행', '19' => '새마을금고', '20' => '신협',
    '21' => '수협은행', '22' => '저축은행중앙회', '23' => '우체국'
);
$payment_result['bankName'] = $bank_names[$payment_result['bankCode'] ?? ''] ?? '은행';
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
    display: flex;
    align-items: center;
    gap: 27px;
}

.payment-detail-order-status {
    font-family: 'Pretendard', sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    white-space: pre;
    color: #000000;
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
    
    /* 모바일에서 환불신청 버튼 위치 조정 */
    .payment-detail-payment-date-row {
        padding-right: 0;
    }
    
    @media (max-width: 765px) {
        .payment-detail-payment-date-row {
            flex-direction: column;
            align-items: flex-start;
            height: auto;
            gap: 12px;
            padding-right: 0;
        }
        
        .payment-detail-payment-date-row .payment-detail-refund-button-wrapper {
            position: static !important;
            transform: none !important;
            width: 100%;
        }
        
        .payment-detail-payment-date-row .payment-detail-refund-button-wrapper button {
            width: 100%;
        }
    }
}

/* 상품 클릭 시 효과 제거 */
/* .payment-detail-order-image:hover,
.payment-detail-order-info a:hover {
    opacity: 0.7;
    transition: opacity 0.2s;
} */
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
                <?php $product_url = $program_id ? get_permalink($program_id) : '#'; ?>
                <a href="<?= esc_url($product_url) ?>" class="payment-detail-order-image">
                    <img src="<?= esc_url($order['image']) ?>" alt="<?= esc_attr($order['title']) ?>" />
                </a>
                
                <div class="payment-detail-order-content">
                    <div class="payment-detail-order-details">
                        <div class="payment-detail-order-info">
                            <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-start; width: 100%;">
                                <a href="<?= esc_url($product_url) ?>" style="text-decoration: none;">
                                    <p class="payment-detail-order-title"><?= esc_html($order['title']) ?></p>
                                </a>
                                <div class="payment-detail-order-schedule"><?= esc_html($order['schedule']) ?></div>
                            </div>
                            <?php
                            // 주문 상태 라벨
                            $status_labels = array(
                                'success' => '결제 완료',
                                'cancelled' => '취소됨',
                                'refunded' => '환불 완료',
                                'refund_requested' => '환불 요청',
                                'pending' => '대기중',
                                'waiting' => '입금 대기'
                            );
                            $status_label = $status_labels[$order_status] ?? '대기중';
                            $status_class = $order_status ?? 'pending';
                            
                            // 취소됨 또는 환불 완료일 때 취소선 적용
                            $is_cancelled_or_refunded = ($order_status === 'cancelled' || $order_status === 'refunded');
                            $price_text = $order['price'] > 0 ? number_format($order['price']) . '원' : '무료';
                            $quantity_text = $order['quantity'] . '매';
                            ?>
                            <div class="payment-detail-order-price">
                                <span style="<?= $is_cancelled_or_refunded ? 'text-decoration: line-through;' : '' ?>"><?= esc_html($price_text) ?> / <?= esc_html($quantity_text) ?></span>
                                <span class="payment-detail-order-status <?= esc_attr($status_class) ?>"><?= esc_html($status_label) ?></span>
                            </div>
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
                        <p><?= $payment['amount'] > 0 ? number_format($payment['amount']) . '원' : '무료' ?></p>
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
                
                <?php if ($payment['paymethod'] === 'VACCT'): ?>
                <!-- 가상계좌 정보 -->
                <div class="payment-detail-info-item payment-detail-info-item-name-24">
                    <div class="payment-detail-info-item-name">
                        <p>입금계좌</p>
                    </div>
                    <div class="payment-detail-info-item-value">
                        <p><?= esc_html($payment_result['accountNo'] ?? '') ?> (<?= esc_html($payment_result['bankName'] ?? '은행') ?>)</p>
                    </div>
                </div>
                <div class="payment-detail-info-item payment-detail-info-item-name-24">
                    <div class="payment-detail-info-item-name">
                        <p>입금기한</p>
                    </div>
                    <div class="payment-detail-info-item-value">
                        <?php 
                        $closeDate = $payment_result['accountCloseDate'] ?? '';
                        if (strlen($closeDate) >= 6) {
                            $year = '20' . substr($closeDate, 0, 2);
                            $month = substr($closeDate, 2, 2);
                            $day = substr($closeDate, 4, 2);
                            $closeDateFormatted = $year . '.' . $month . '.' . $day;
                        } else {
                            $closeDateFormatted = $closeDate;
                        }
                        ?>
                        <p><?= esc_html($closeDateFormatted) ?>까지</p>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- 결제일시 -->
                <div class="payment-detail-info-item payment-detail-info-item-name-24-center payment-detail-payment-date-row" style="position: relative;">
                    <div class="payment-detail-info-item-name">
                        <p>결제일시</p>
                    </div>
                    <div class="payment-detail-info-item-value">
                        <p><?= esc_html($payment['date']) ?></p>
                    </div>
                    
                    <!-- 환불 신청 버튼 (결제 완료 상태일 때만 표시, 환불 신청 상태 제외) - Figma 디자인 기준 오른쪽 정렬 -->
                    <?php
                    // 오직 '결제 완료' 상태일 때만 버튼 노출
                    if ($order_status === 'success'):
                        // 1. 유료 주문: 환불신청 버튼 (무료 주문 제외)
                        if (!empty($payment['ref_no']) && !$payment['is_free']):
                    ?>
                    <div class="payment-detail-refund-button-wrapper" style="position: absolute; top: 50%; right: 0; transform: translateY(-50%);">
                        <button id="refund-request" style="padding: 0.75rem 2rem; background-color: #000000; border: 1px solid #000000; font-size: 1rem; font-weight: 700; color: #FFFFFF; white-space: nowrap; border-radius: 4px;">환불신청</button>
                    </div>
                    <?php 
                        endif;
                    endif; 
                    ?>
                </div>
            </div>
        </div>
        
        <!-- 결제 상태 표시 -->
        <?php if ($is_cancelled): ?>
        <div class="payment-status" style="margin-top: 2rem; padding: 1rem; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 0.25rem;">
            <p style="margin: 0; font-size: 1rem; font-weight: 700; color: #856404;">결제가 취소되었습니다.</p>
            <?php if (isset($payment_result['cancelDate'])): ?>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #856404;">취소일시: <?= date('Y.m.d H:i', strtotime($payment_result['cancelDate'])) ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- 환불 신청 상태 안내 -->
        <?php if ($is_refund_requested): ?>
        <div class="payment-status" style="margin-top: 2rem; padding: 1rem; background-color: #e7f3ff; border: 1px solid #2196F3; border-radius: 0.25rem;">
            <p style="margin: 0; font-size: 1rem; font-weight: 700; color: #1976D2;">환불 신청이 접수되었습니다.</p>
            <?php
            $refund_request_date = get_field('order_refund_request_date', $order_post_id);
            $refund_request_amount = get_field('order_refund_request_amount', $order_post_id);
            $refund_request_reason = get_field('order_refund_request_reason', $order_post_id);
            if ($refund_request_date):
                $year = substr($refund_request_date, 0, 4);
                $month = substr($refund_request_date, 4, 2);
                $day = substr($refund_request_date, 6, 2);
                $hour = strlen($refund_request_date) >= 10 ? substr($refund_request_date, 8, 2) : '';
                $minute = strlen($refund_request_date) >= 12 ? substr($refund_request_date, 10, 2) : '';
                $formatted_date = $year . '.' . $month . '.' . $day;
                if ($hour && $minute) {
                    $formatted_date .= ' ' . $hour . ':' . $minute;
                }
            ?>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #1976D2;">신청일시: <?= esc_html($formatted_date) ?></p>
            <?php if ($refund_request_amount): ?>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #1976D2;">신청 금액: <?= number_format($refund_request_amount) ?>원</p>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 환불 신청 버튼 이벤트
    const refundRequestButton = document.getElementById('refund-request');
    
    if (refundRequestButton) {
        refundRequestButton.addEventListener('click', function() {
            // 환불 정보 조회
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'get_refund_info',
                    order_id: '<?= esc_js($order_id) ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const refundInfo = data.data;
                    
                    // 정책에 따른 환불 계산 결과가 있으면 무조건 신청 팝업 노출 (온라인 가능 여부와 상관없이)
                    // 단, 아예 환불이 불가능한 기간일 때만 불가 팝업 노출
                    if (!refundInfo.can_refund) {
                        showRefundNotAvailablePopup(refundInfo.reason);
                    } else {
                        // 환불 신청 팝업 (온라인 환불 가능 여부에 따라 버튼 이름/메시지 조정)
                        showRefundRequestPopup(refundInfo);
                    }
                } else {
                    alert('환불 정보 조회 실패: ' + (data.data?.message || '알 수 없는 오류'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('처리 중 오류가 발생했습니다.');
            });
        });
    }
    
    // 환불 신청 팝업 표시 (Figma 디자인 기준)
    function showRefundRequestPopup(refundInfo) {
        const popup = document.createElement('div');
        popup.id = 'refund-request-popup';
        popup.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 10000; display: flex; align-items: center; justify-content: center;';
        
        const popupContent = document.createElement('div');
        popupContent.style.cssText = 'background: #FFFFFF; padding: 32px; border-radius: 8px; max-width: 480px; width: 90%; position: relative;';
        
        // X 버튼 (원본 close 아이콘 사용)
        const closeBtn = document.createElement('button');
        const closeIconUrl = '<?= esc_js(SRC_MODULE . "/imgs/icons/close.svg") ?>';
        closeBtn.innerHTML = `<img src="${closeIconUrl}" alt="닫기" style="width: 24px; height: 24px; display: block;" />`;
        closeBtn.style.cssText = 'position: absolute; top: 16px; right: 16px; background: none; border: none; padding: 0; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;';
        closeBtn.addEventListener('click', function() {
            document.body.removeChild(popup);
        });
        
        const title = document.createElement('h2');
        title.textContent = '환불신청';
        title.style.cssText = 'font-family: "Pretendard Variable", sans-serif; font-weight: 700; font-size: 20px; line-height: 28px; color: #000000; margin: 0 0 24px 0;';
        
        // 메시지 영역 (Figma 디자인 기준)
        const messageDiv = document.createElement('div');
        messageDiv.style.cssText = 'margin-bottom: 24px; text-align: center;';
        messageDiv.innerHTML = `
            <p style="font-family: "Pretendard Variable", sans-serif; font-size: 16px; line-height: 24px; color: #000000; margin: 0 0 8px 0;">
                확인을 누르면 환불 절차가 진행됩니다.
            </p>
            <p style="font-family: "Pretendard Variable", sans-serif; font-size: 16px; line-height: 24px; color: #000000; margin: 0;">
                환불을 신청하시겠습니까?
            </p>
        `;
        
        // 환불 사유 입력란 (일반 환불 팝업에만 표시)
        const reasonLabel = document.createElement('label');
        reasonLabel.textContent = '환불 사유 (선택사항)';
        reasonLabel.style.cssText = 'display: block; font-family: "Pretendard Variable", sans-serif; font-size: 14px; line-height: 20px; color: #000000; margin-bottom: 8px; text-align: left;';
        
        const reasonInput = document.createElement('textarea');
        reasonInput.id = 'refund-reason-input';
        reasonInput.placeholder = '환불 사유를 입력해주세요.';
        reasonInput.style.cssText = 'width: 100%; min-height: 80px; padding: 12px; border: 1px solid #E0E0E0; border-radius: 4px; font-family: "Pretendard Variable", sans-serif; font-size: 14px; line-height: 20px; resize: vertical; box-sizing: border-box; margin-bottom: 24px;';
        
        const confirmBtn = document.createElement('button');
        confirmBtn.textContent = '확인';
        confirmBtn.style.cssText = 'width: 100%; padding: 14px; background-color: #000000; color: #FFFFFF; border: none; border-radius: 4px; font-family: "Pretendard Variable", sans-serif; font-weight: 700; font-size: 16px; line-height: 24px;';
        confirmBtn.addEventListener('click', function() {
            const reason = reasonInput.value.trim();
            
            confirmBtn.disabled = true;
            confirmBtn.textContent = '처리 중...';
            
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'mainpay_refund_request',
                    order_id: '<?= esc_js($order_id) ?>',
                    refund_reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('환불 신청이 완료되었습니다.');
                    window.location.reload();
                } else {
                    alert('환불 신청 실패: ' + (data.data?.message || '알 수 없는 오류'));
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = '확인';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('처리 중 오류가 발생했습니다.');
                confirmBtn.disabled = false;
                confirmBtn.textContent = '확인';
            });
        });
        
        popupContent.appendChild(closeBtn);
        popupContent.appendChild(title);
        popupContent.appendChild(messageDiv);
        popupContent.appendChild(reasonLabel);
        popupContent.appendChild(reasonInput);
        popupContent.appendChild(confirmBtn);
        popup.appendChild(popupContent);
        
        document.body.appendChild(popup);
        
        // 팝업 외부 클릭 시 닫기
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                document.body.removeChild(popup);
            }
        });
    }
    
    // 환불 불가 안내 팝업 (Figma 디자인 기준)
    function showRefundNotAvailablePopup(reason) {
        const phoneNumber = '<?= esc_js(get_field("footer_phone_number", "option") ?: "02-722-1315") ?>';
        
        const popup = document.createElement('div');
        popup.id = 'refund-not-available-popup';
        popup.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 10000; display: flex; align-items: center; justify-content: center;';
        
        const popupContent = document.createElement('div');
        popupContent.style.cssText = 'background: #FFFFFF; padding: 32px; border-radius: 8px; max-width: 480px; width: 90%; position: relative;';
        
        // X 버튼 (원본 close 아이콘 사용)
        const closeBtn = document.createElement('button');
        const closeIconUrl = '<?= esc_js(SRC_MODULE . "/imgs/icons/close.svg") ?>';
        closeBtn.innerHTML = `<img src="${closeIconUrl}" alt="닫기" style="width: 24px; height: 24px; display: block;" />`;
        closeBtn.style.cssText = 'position: absolute; top: 16px; right: 16px; background: none; border: none; padding: 0; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;';
        closeBtn.addEventListener('click', function() {
            document.body.removeChild(popup);
        });
        
        const title = document.createElement('h2');
        title.textContent = '환불신청';
        title.style.cssText = 'font-family: "Pretendard Variable", sans-serif; font-weight: 700; font-size: 20px; line-height: 28px; color: #000000; margin: 0 0 24px 0;';
        
        // 메시지 영역 (Figma 디자인 기준)
        const message = document.createElement('div');
        message.style.cssText = 'margin-bottom: 24px; text-align: center;';
        message.innerHTML = `
            <p style="font-family: "Pretendard Variable", sans-serif; font-size: 16px; line-height: 24px; color: #000000; margin: 0 0 8px 0;">
                해당 상품은 환불 규정에 따라
            </p>
            <p style="font-family: "Pretendard Variable", sans-serif; font-size: 16px; line-height: 24px; color: #000000; margin: 0 0 8px 0;">
                웹사이트에서 환불을 진행하실 수 없습니다.
            </p>
            <p style="font-family: "Pretendard Variable", sans-serif; font-size: 16px; line-height: 24px; color: #000000; margin: 0 0 8px 0;">
                자세한 내용은 뮤지엄한미 아카데미(${phoneNumber})으로
            </p>
            <p style="font-family: "Pretendard Variable", sans-serif; font-size: 16px; line-height: 24px; color: #000000; margin: 0;">
                연락주시면 안내드리겠습니다.
            </p>
        `;
        
        const confirmBtn = document.createElement('button');
        confirmBtn.textContent = '확인';
        confirmBtn.style.cssText = 'width: 100%; padding: 14px; background-color: #000000; color: #FFFFFF; border: none; border-radius: 4px; font-family: "Pretendard Variable", sans-serif; font-weight: 700; font-size: 16px; line-height: 24px;';
        confirmBtn.addEventListener('click', function() {
            document.body.removeChild(popup);
        });
        
        popupContent.appendChild(closeBtn);
        popupContent.appendChild(title);
        popupContent.appendChild(message);
        popupContent.appendChild(confirmBtn);
        popup.appendChild(popupContent);
        
        document.body.appendChild(popup);
        
        // 팝업 외부 클릭 시 닫기
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                document.body.removeChild(popup);
            }
        });
    }
    
    // 무료 주문 신청취소 버튼 이벤트
    const cancelFreeOrderButton = document.getElementById('cancel-free-order');
    
    if (cancelFreeOrderButton) {
        cancelFreeOrderButton.addEventListener('click', function() {
            if (!confirm('신청을 취소하시겠습니까?')) {
                return;
            }
            
            cancelFreeOrderButton.disabled = true;
            cancelFreeOrderButton.textContent = '처리 중...';
            
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'cancel_free_order',
                    order_id: '<?= esc_js($order_id) ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('신청이 취소되었습니다.');
                    window.location.reload();
                } else {
                    alert('취소 실패: ' + (data.data?.message || '알 수 없는 오류가 발생했습니다.'));
                    cancelFreeOrderButton.disabled = false;
                    cancelFreeOrderButton.textContent = '신청취소';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('처리 중 오류가 발생했습니다.');
                cancelFreeOrderButton.disabled = false;
                cancelFreeOrderButton.textContent = '신청취소';
            });
        });
    }
});
</script>

<?php get_footer(); ?>
