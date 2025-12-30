<?php
get_header();

// 로그인 체크
if (!is_user_logged_in()) {
    // 로그인하지 않은 경우 로그인 페이지로 리다이렉트
    $current_url = home_url(add_query_arg(null, null));
    $login_url = home_url('/login?redirect_to=' . urlencode($current_url));
    wp_redirect($login_url);
    exit;
}

// URL 파라미터에서 프로그램 ID 가져오기
$program_id = intval($_GET['program_id'] ?? 0);

// 프로그램 정보 가져오기
$program = null;
$product = null;
$order_item = null;
$orderer = null;

if ($program_id) {
    $program = get_post($program_id);
    if ($program && $program->post_type === 'post_program') {
        // ACF 필드에서 상품 정보 가져오기
        $product_price = intval(get_field('product_price', $program_id) ?? 0);
        $product_purchasable = get_field('product_purchasable', $program_id) ?? false;
        $product_stock = intval(get_field('product_stock', $program_id) ?? 0); // 수강인원 제한
        
        // 현재 신청자 수 가져오기
        $current_applicants = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
        
        // 프로그램 정보 설정
        $thumb = get_field('thumb', $program_id);
        $schedule = get_field('schedule', $program_id);
        
        // 날짜 범위 가져오기
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
        
        // 썸네일 이미지 URL 가져오기
        $image_url = getImg('empty.svg');
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
        
        // product_id는 프로그램 ID 기반으로 생성
        $product_id = $program_id;
        
        $order_item = [
            'title' => $product_title,
            'schedule' => $date_range,
            'image' => $image_url,
            'quantity' => 1,
            'price' => $product_price,
            'program_id' => $program_id,
            'product_id' => $product_id,
            'product_purchasable' => $product_purchasable,
            'product_stock' => $product_stock,
            'current_applicants' => $current_applicants
        ];
        
        // 판매 가능 여부 확인
        if (!$product_purchasable) {
            ?>
            <div class="page-wrap" style="padding: 2rem; text-align: center;">
                <h2>유효하지 않은 접근입니다</h2>
                <p>현재 신청할 수 없는 상품입니다.</p>
                <a href="javascript:history.back();" class="button" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 2rem; background-color: #000; color: #fff; text-decoration: none;">이전 페이지로 돌아가기</a>
            </div>
            <?php
            get_footer();
            exit;
        }
    }
}

// 재고 확인 (수강인원 제한이 있고 초과된 경우) - 수량 1개 기준으로 체크 (실제 결제 시 수량 고려)
if ($order_item && isset($order_item['product_stock']) && $order_item['product_stock'] > 0) {
    if ($order_item['current_applicants'] >= $order_item['product_stock']) {
        ?>
        <div class="page-wrap" style="padding: 2rem; text-align: center;">
            <h2>수강인원이 초과되어 마감되었습니다</h2>
            <p>현재 신청자 수: <?= $order_item['current_applicants'] ?>명 / 제한 인원: <?= $order_item['product_stock'] ?>명</p>
            <a href="javascript:history.back();" class="button" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 2rem; background-color: #000; color: #fff; text-decoration: none;">이전 페이지로 돌아가기</a>
        </div>
        <?php
        get_footer();
        exit;
    }
}

// 상품 정보가 없으면 에러 메시지 표시 및 리다이렉트
if (!$order_item || empty($order_item['title']) || $order_item['price'] < 0) {
    ?>
    <div class="page-wrap" style="padding: 2rem; text-align: center;">
        <h2>상품 정보를 찾을 수 없습니다</h2>
        <p>연동된 상품 정보가 없거나 잘못된 접근입니다.</p>
        <a href="<?= home_url() ?>" class="button" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 2rem; background-color: #000; color: #fff; text-decoration: none;">홈으로 돌아가기</a>
    </div>
    <?php
    get_footer();
    exit;
}

// 현재 로그인한 사용자 정보 가져오기 (이미 로그인 체크 완료)
$current_user = wp_get_current_user();
$user_meta = get_user_meta($current_user->ID);

// 회원 정보 가져오기
$orderer = [
    'name' => $current_user->display_name ?: ($user_meta['first_name'][0] ?? '') . ' ' . ($user_meta['last_name'][0] ?? ''),
    'email' => $current_user->user_email,
    'phone' => $user_meta['billing_phone'][0] ?? $user_meta['phone'][0] ?? ''
];

// 이름이 비어있으면 사용자명 사용
if (empty($orderer['name']) || trim($orderer['name']) === '') {
    $orderer['name'] = $current_user->user_login;
}

// 메인페이에서 지원하는 결제수단만 표시 (CARD|ACCT|VACCT|HPP|CULT)
$payment_methods = [
    ['id' => 'card', 'name' => '신용카드'],
    ['id' => 'transfer', 'name' => '실시간', 'subname' => '계좌이체'],
    ['id' => 'bank', 'name' => '가상계좌'],
    ['id' => 'mobile', 'name' => '휴대폰 결제'],
];

$total_price = $order_item['price'] * $order_item['quantity'];
?>
<div class="page-wrap order-page-wrap flex flex-col gap-32">
    <!-- 상단 제목 -->
    <div class="flex gap-6 mb-0" style="margin-bottom: 0;">
        <h3 class="bold" style="font-size: 1.75rem; line-height: 2.625rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">주문자 정보</h3>
    </div>
    
    <!-- 메인 컨텐츠 영역 -->
    <div class="flex items-stretch w-full relative order-main-container" style="gap: 0; padding: 1.875rem 0; display: flex; flex-direction: row;">
        <!-- 좌측 메인 컬럼 -->
        <div class="flex flex-col order-left-column" style="flex: 1 1 0; min-width: 0; gap: 2.5rem; padding-right: 1.25rem;">
            <!-- 주문자 정보 섹션 -->
            <div class="flex flex-col" style="gap: 1.25rem;">
                <h4 class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">주문자 정보</h4>
                <div class="flex flex-col" style="gap: 0.625rem;">
                    <div class="flex items-center justify-between" style="width: 100%; height: 1.875rem;">
                        <span class="bold" style="font-size: 1rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">이름</span>
                        <span class="bold" style="font-size: 1rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;"><?= esc_html($orderer['name']) ?></span>
                    </div>
                    <div class="flex items-center justify-between" style="width: 100%; height: 1.5rem;">
                        <span class="bold" style="font-size: 1rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">이메일</span>
                        <span class="bold" style="font-size: 1rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;"><?= esc_html($orderer['email']) ?></span>
                    </div>
                    <div class="flex items-center justify-between" style="width: 100%; height: 1.5rem;">
                        <span class="bold" style="font-size: 1rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">휴대폰</span>
                        <span class="bold" style="font-size: 1rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;"><?= esc_html($orderer['phone']) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- 구분선 -->
            <div style="height: 0.0625rem; background-color: rgba(0,0,0,0.1); width: 100%;"></div>
            
            <!-- 주문 내역 섹션 -->
            <div class="flex flex-col" style="gap: 1.25rem;">
                <h4 class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">주문 내역</h4>
                <div class="flex items-start" style="gap: 2rem;">
                    <div class="relative shrink-0" style="width: 12.5rem; height: 12.5rem;">
                        <img src="<?= esc_url($order_item['image']) ?>" alt="<?= esc_attr($order_item['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;" />
                    </div>
                    <div class="flex flex-col justify-between flex-1 order-item-details" style="min-height: 12.5rem;">
                        <div class="flex flex-col" style="gap: 0.25rem;">
                            <h4 class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;"><?= esc_html($order_item['title']) ?></h4>
                            <p style="font-size: 1rem; line-height: 1.75rem; font-weight: 400; letter-spacing: -0.0125rem; color: #000;"><?= esc_html($order_item['schedule']) ?></p>
                        </div>
                        <div class="flex items-center justify-center order-quantity-control" style="background-color: #ececec; height: 1.5rem; border-radius: 0.125rem; width: 3.9375rem; gap: 0.625rem; padding: 0.0625rem 0;">
                            <button type="button" class="quantity-decrease" style="background: none; border: none; cursor: pointer; font-size: 1rem; line-height: 1.75rem; font-weight: 700; color: #000; padding: 0; display: flex; align-items: center; justify-content: center;">-</button>
                            <span class="quantity-value" style="font-size: 1rem; line-height: 1.75rem; font-weight: 500; color: #000; min-width: 1rem; text-align: center;"><?= $order_item['quantity'] ?></span>
                            <button type="button" class="quantity-increase" style="background: none; border: none; cursor: pointer; font-size: 0.875rem; line-height: 1.5rem; font-weight: 700; color: #000; padding: 0; display: flex; align-items: center; justify-content: center;">+</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 구분선 -->
            <div style="height: 0.0625rem; background-color: rgba(0,0,0,0.1); width: 100%;"></div>
            
            <!-- 결제 수단 선택 섹션 (유료 상품일 때만 표시) -->
            <?php if ($total_price > 0): ?>
            <div class="flex flex-col" style="gap: 1.25rem;">
                <h4 class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">결제 수단</h4>
                <div class="flex flex-wrap payment-methods-container" style="gap: 0; width: 45.625rem; max-width: 100%;">
                    <?php foreach ($payment_methods as $index => $method): ?>
                    <label class="relative cursor-pointer bg-white border border-solid flex items-end justify-center payment-method-label" 
                           style="width: 6.5rem; height: 6.5rem; border-color: #777777; overflow: hidden; transition: all 0.2s; padding-bottom: 0.59375rem;">
                        <input type="radio" name="payment_method" value="<?= esc_attr($method['id']) ?>" class="absolute opacity-0 w-0 h-0" <?= $index === 0 ? 'checked' : '' ?> />
                        <div class="text-center" style="font-size: 0.875rem; line-height: 1.25em; letter-spacing: -0.0175rem; color: #000; font-weight: 400;">
                            <?php if (isset($method['subname'])): ?>
                                <p style="margin-bottom: 0; line-height: 1.25em;"><?= esc_html($method['name']) ?></p>
                                <p style="line-height: 1.25em;"><?= esc_html($method['subname']) ?></p>
                            <?php else: ?>
                                <p style="line-height: 1.25em;"><?= esc_html($method['name']) ?></p>
                            <?php endif; ?>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- 구분선 -->
            <div style="height: 0.0625rem; background-color: rgba(0,0,0,0.1); width: 100%;"></div>
            
            <!-- 총 결제 예상 금액 -->
            <div class="flex items-center justify-between" style="width: 100%;">
                <h4 class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">총 결제 예상 금액</h4>
                <h4 class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;"><?= $total_price > 0 ? number_format($total_price) . '원' : '무료' ?></h4>
            </div>
            
            <!-- 취소하기/결제하기 버튼 (모바일용) -->
            <div class="flex gap-5 w-full mobile-buttons" style="gap: 1.25rem; display: none;">
                <button class="button flex-1 bg-black text-white rounded" style="padding: 0.125rem 4.6875rem; font-size: 0.875rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; border-radius: 0.25rem;">취소하기</button>
                <button class="button flex-1 bg-black text-white rounded payment-submit-btn" style="padding: 0.125rem 4.6875rem; font-size: 0.875rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; border-radius: 0.25rem;"><?= $total_price > 0 ? '결제하기' : '신청하기' ?></button>
            </div>
        </div>
        
        <!-- 우측 패널 래퍼 -->
        <div class="flex flex-col order-right-wrapper" style="width: 17.5rem; flex-shrink: 0; align-self: stretch;">
            <!-- 우측 고정 박스 -->
            <div class="flex flex-col shrink-0 sticky-payment-box" style="width: 100%; gap: 1.25rem; padding-left: 1.25rem; align-self: flex-start;">
                <div class="flex items-start justify-between" style="width: 100%;">
                    <span class="bold" style="font-size: 1rem; line-height: 1.75rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">결제 금액</span>
                    <span class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;"><?= $total_price > 0 ? number_format($total_price) . '원' : '무료' ?></span>
                </div>
                <button class="button w-full bg-black text-white rounded payment-submit-btn" style="padding: 0.125rem 4.6875rem; font-size: 0.875rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; border-radius: 0.25rem;"><?= $total_price > 0 ? '결제하기' : '신청하기' ?></button>
            </div>
        </div>
    </div>

    <!-- 환불 규정 섹션 -->
    <div class="flex flex-col refund-policy-section" style="gap: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <div class="flex flex-col" style="gap: 0.75rem;">
            <h4 class="bold" style="font-size: 1.25rem; line-height: 1.875rem; font-weight: 700; color: #000;">정규과정</h4>
            <ul style="list-style: none; padding: 0; margin: 0; font-size: 1rem; line-height: 1.625rem; color: #333;">
                <li>개강 전 전액 환불</li>
                <li>개강 후 3주 이전 70% 환불</li>
                <li>개강 후 4주 이후 환불 불가</li>
            </ul>
        </div>
        
        <div class="flex flex-col" style="gap: 0.75rem;">
            <h4 class="bold" style="font-size: 1.25rem; line-height: 1.875rem; font-weight: 700; color: #000;">단기과정(6주 이내)</h4>
            <ul style="list-style: none; padding: 0; margin: 0; font-size: 1rem; line-height: 1.625rem; color: #333;">
                <li>개강 전 전액 환불</li>
                <li>개강 후 2주 이전 70% 환불</li>
                <li>개강 후 3주 이후 환불 불가</li>
            </ul>
        </div>
    </div>
</div>

<style>
/* page-wrap 좌우 패딩 */
.order-page-wrap {
    padding-left: 1rem !important;
    padding-right: 1rem !important;
    max-width: 75rem;
    margin: 0 auto;
}

/* 우측 스티키 박스 */
.order-right-wrapper {
    align-self: stretch;
}

.sticky-payment-box {
    position: relative;
    align-self: flex-start;
}

.sticky-payment-box.sticky {
    position: fixed;
    top: 7.5rem;
}

/* PC에서 좌우 패널 간격 조정 */
@media (min-width: 1025px) {
    .order-main-container {
        gap: 0 !important;
    }
    
    .order-left-column {
        padding-right: 1.25rem !important; /* 20px */
    }
    
    .sticky-payment-box {
        padding-left: 1.25rem !important; /* 20px */
    }
    
    /* 총 간격: 20px + 20px = 40px */
}

/* 결제 수단 라디오 버튼 선택 스타일 */
.payment-method-label:has(input[name="payment_method"]:checked) {
    border-color: #000 !important;
    background-color: #000 !important;
}
.payment-method-label:has(input[name="payment_method"]:checked) > div {
    color: #fff !important;
}
.payment-method-label:has(input[name="payment_method"]:checked) > div p {
    color: #fff !important;
}

/* 라디오 버튼 호버 효과 */
.payment-method-label:hover {
    border-color: #000;
}

/* PC에서 결제 수단 보더 중첩 */
@media (min-width: 1025px) {
    .payment-method-label:not(:first-child) {
        margin-left: -0.0625rem;
    }
}

/* 모바일 반응형 */
@media (max-width: 1024px) {
    .order-main-container {
        flex-direction: column !important;
        gap: 1.5rem !important;
    }
    
    .order-left-column {
        width: 100% !important;
        flex: none !important;
        padding-right: 0 !important;
    }
    
    .order-right-wrapper {
        width: 100% !important;
    }
    
    .sticky-payment-box {
        position: relative !important;
        top: 0 !important;
        width: 100% !important;
        padding-left: 0 !important;
    }
    
    .sticky-payment-box.sticky {
        position: relative !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
    }
    
    .mobile-buttons {
        display: flex !important;
    }
    
    .page-wrap .flex.flex-wrap {
        gap: 0.25rem !important;
        width: 100% !important;
    }
    
    .page-wrap .payment-method-label {
        width: calc(50% - 0.125rem) !important;
        height: 5rem !important;
    }
    
    .page-wrap h3 {
        font-size: 1.5rem !important;
        line-height: 2.25rem !important;
    }
    
    .page-wrap h4 {
        font-size: 1.25rem !important;
        line-height: 1.875rem !important;
    }
    
    .page-wrap .flex.items-start[style*="gap: 2rem"] {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .page-wrap .flex.items-start[style*="gap: 2rem"] > div:first-child {
        width: 100% !important;
        max-width: 12.5rem !important;
        aspect-ratio: 1 !important;
        height: auto !important;
    }
    
    .page-wrap .flex.items-start[style*="gap: 2rem"] > div:last-child {
        min-height: 0 !important;
    }
    
    .order-item-details {
        gap: 1rem !important;
    }
    
    .order-quantity-control {
        margin-top: 1rem !important;
    }
}

@media (max-width: 765px) {
    .order-page-wrap {
        padding: 1.5rem 1rem !important;
    }
    
    .order-page-wrap > div:last-child {
        padding: 0 !important;
    }
}

/* 수량 조절 버튼 호버 효과 */
.quantity-decrease:hover,
.quantity-increase:hover {
    opacity: 0.6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const decreaseBtn = document.querySelector('.quantity-decrease');
    const increaseBtn = document.querySelector('.quantity-increase');
    const quantityValue = document.querySelector('.quantity-value');
    
    if (!decreaseBtn || !increaseBtn || !quantityValue) return;
    
    let quantity = parseInt(quantityValue.textContent) || 1;
    const pricePerItem = <?= $order_item['price'] ?>;
    
    function updateQuantity(newQuantity) {
        if (newQuantity < 1) return;
        quantity = newQuantity;
        quantityValue.textContent = quantity;
        
        // 총 결제 예상 금액 업데이트
        const totalPrice = pricePerItem * quantity;
        const displayPrice = totalPrice > 0 ? totalPrice.toLocaleString('ko-KR') + '원' : '무료';
        
        // 좌측 총 결제 예상 금액 업데이트
        const leftTotalElements = document.querySelectorAll('.order-left-column h4.bold');
        leftTotalElements.forEach(el => {
            if (el.textContent.includes('총 결제 예상 금액')) {
                const nextSibling = el.nextElementSibling;
                if (nextSibling && nextSibling.classList.contains('bold')) {
                    nextSibling.textContent = displayPrice;
                }
            }
        });
        
        // 우측 결제 금액 업데이트
        const rightPaymentBox = document.querySelector('.sticky-payment-box');
        if (rightPaymentBox) {
            const rightAmount = rightPaymentBox.querySelector('span.bold:last-child');
            if (rightAmount) {
                rightAmount.textContent = displayPrice;
            }
            
            // 버튼 텍스트 업데이트
            const submitButton = rightPaymentBox.querySelector('.payment-submit-btn');
            if (submitButton) {
                submitButton.textContent = totalPrice > 0 ? '결제하기' : '신청하기';
            }
        }
        
        // 모바일 버튼 텍스트 업데이트
        const mobileButtons = document.querySelectorAll('.mobile-buttons .payment-submit-btn');
        mobileButtons.forEach(btn => {
            btn.textContent = totalPrice > 0 ? '결제하기' : '신청하기';
        });
    }
    
    decreaseBtn.addEventListener('click', function(e) {
        e.preventDefault();
        updateQuantity(quantity - 1);
    });
    
    increaseBtn.addEventListener('click', function(e) {
        e.preventDefault();
        updateQuantity(quantity + 1);
    });
    
    // 결제하기/신청하기 버튼 이벤트
    function initPaymentButton() {
        const paymentButtons = document.querySelectorAll('.payment-submit-btn');
        
        paymentButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const currentQuantity = parseInt(quantityValue.textContent) || 1;
                const totalPrice = pricePerItem * currentQuantity;
                
                // 무료 상품인 경우 바로 신청 처리
                if (totalPrice === 0) {
                    if (!confirm('신청하시겠습니까?')) {
                        return;
                    }
                    
                    button.disabled = true;
                    button.textContent = '처리 중...';
                    
                    const freeOrderData = {
                        action: 'mainpay_free_order',
                        program_id: <?= $order_item['program_id'] ?? 0 ?>,
                        quantity: currentQuantity,
                        buyer_name: '<?= esc_js($orderer['name']) ?>',
                        buyer_email: '<?= esc_js($orderer['email']) ?>',
                        buyer_tel: '<?= esc_js($orderer['phone']) ?>'
                    };
                    
                    fetch('<?= admin_url('admin-ajax.php') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams(freeOrderData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('신청이 완료되었습니다.');
                            window.location.href = '<?= home_url('/payment-detail?order_id=' . ($order_item['program_id'] ?? '') . '&status=success&free=1') ?>';
                        } else {
                            alert('신청 실패: ' + (data.data?.message || '알 수 없는 오류가 발생했습니다.'));
                            button.disabled = false;
                            button.textContent = '신청하기';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('처리 중 오류가 발생했습니다: ' + error.message);
                        button.disabled = false;
                        button.textContent = '신청하기';
                    });
                    return;
                }
                
                // 유료 상품인 경우 결제 프로세스 진행
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                if (totalPrice > 0 && !paymentMethod) {
                    alert('결제 수단을 선택해주세요.');
                    return;
                }

                // 메인페이 paymethod 매핑 (CARD|ACCT|VACCT|HPP|CULT 중 하나)
                const paymethodMap = {
                    'card': 'CARD',        // 신용카드
                    'transfer': 'ACCT',    // 계좌이체
                    'bank': 'VACCT',       // 가상계좌
                    'mobile': 'HPP',       // 휴대폰 결제
                };
                const paymethod = paymethodMap[paymentMethod.value] || 'CARD';
                
                const paymentData = {
                    action: 'mainpay_payment_ready',
                    amount: totalPrice,
                    paymethod,
                    buyer_name: '<?= esc_js($orderer['name']) ?>',
                    buyer_email: '<?= esc_js($orderer['email']) ?>',
                    buyer_tel: '<?= esc_js($orderer['phone']) ?>',
                    program_id: <?= $order_item['program_id'] ?? 0 ?>,
                    quantity: currentQuantity
                };
                
                // 버튼 비활성화
                button.disabled = true;
                button.textContent = '처리 중...';
                
                // AJAX 요청
                fetch('<?= admin_url('admin-ajax.php') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(paymentData)
                })
                .then(response => {
                    // Content-Type 확인
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Non-JSON response:', text);
                            throw new Error('서버에서 JSON이 아닌 응답을 받았습니다.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // 결제창으로 리다이렉트 (팝업 대신 현재 창에서 이동)
                        window.location.href = data.data.payUrl;
                    } else {
                        alert('결제 준비 실패: ' + (data.data?.message || '알 수 없는 오류가 발생했습니다.'));
                        button.disabled = false;
                        button.textContent = '결제하기';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('결제 처리 중 오류가 발생했습니다: ' + error.message);
                    button.disabled = false;
                    button.textContent = '결제하기';
                });
            });
        });
    }
    
    // 취소하기 버튼 이벤트
    function initCancelButton() {
        const cancelButtons = document.querySelectorAll('.mobile-buttons button:first-child');
        
        cancelButtons.forEach(function(button) {
            if (button.textContent.includes('취소하기')) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('주문을 취소하시겠습니까?')) {
                        window.location.href = '<?= home_url() ?>';
                    }
                });
            }
        });
    }
    
    initPaymentButton();
    initCancelButton();
    
    // 우측 패널 스티키 처리
    const stickyBox = document.querySelector('.sticky-payment-box');
    const rightWrapper = document.querySelector('.order-right-wrapper');
    const mainContainer = document.querySelector('.order-main-container');
    
    if (stickyBox && rightWrapper && mainContainer && window.innerWidth > 1024) {
        let originalLeft = null;
        let originalWidth = null;
        const stickyTop = 120; // 7.5rem = 120px
        
        function updateOriginalPosition() {
            if (window.innerWidth > 1024) {
                const rect = stickyBox.getBoundingClientRect();
                originalLeft = rect.left;
                originalWidth = rect.width;
            }
        }
        
        function handleScroll() {
            if (window.innerWidth <= 1024) {
                stickyBox.classList.remove('sticky');
                stickyBox.style.top = '';
                stickyBox.style.left = '';
                stickyBox.style.width = '';
                return;
            }
            
            if (originalLeft === null || originalWidth === null) {
                updateOriginalPosition();
            }
            
            const wrapperRect = rightWrapper.getBoundingClientRect();
            const wrapperBottom = wrapperRect.bottom;
            const wrapperTop = wrapperRect.top;
            const stickyBoxHeight = stickyBox.offsetHeight;
            
            // 래퍼 상단이 stickyTop 이하이고, 래퍼 하단이 뷰포트 안에 있을 때만 스티키 활성화
            if (wrapperTop <= stickyTop && wrapperBottom > stickyTop + stickyBoxHeight) {
                stickyBox.classList.add('sticky');
                if (originalLeft !== null && originalWidth !== null) {
                    stickyBox.style.left = originalLeft + 'px';
                    stickyBox.style.width = originalWidth + 'px';
                }
            } else {
                // 래퍼 하단에 도달하거나 래퍼가 뷰포트를 벗어나면 스티키 해제
                stickyBox.classList.remove('sticky');
                stickyBox.style.top = '';
                stickyBox.style.left = '';
                stickyBox.style.width = '';
            }
        }
        
        // 초기 위치 저장
        window.addEventListener('load', updateOriginalPosition);
        setTimeout(updateOriginalPosition, 100); // DOM이 완전히 렌더링된 후
        
        window.addEventListener('scroll', handleScroll);
        window.addEventListener('resize', function() {
            updateOriginalPosition();
            handleScroll();
        });
        
        handleScroll();
    }
});
</script>

<?php get_footer(); ?>
