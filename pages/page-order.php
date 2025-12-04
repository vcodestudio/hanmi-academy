<?php
get_header();

// 더미 데이터
$orderer = [
    'name' => '김한미',
    'email' => 'abc1234@abc.com',
    'phone' => '010 1234 5678'
];

$order_item = [
    'title' => '큐레이터가 알려주는 미술작품 보는 법',
    'schedule' => '매주 수요일 · 오후 3시',
    'image' => getImg('empty.svg'),
    'quantity' => 1,
    'price' => 50000
];

$payment_methods = [
    ['id' => 'kakao', 'name' => '카카오페이', 'subname' => '(KAKAO PAY)'],
    ['id' => 'payco', 'name' => '페이코', 'subname' => '(PAYCO)'],
    ['id' => 'bank', 'name' => '무통장 입금'],
    ['id' => 'card', 'name' => '신용카드'],
    ['id' => 'transfer', 'name' => '실시간', 'subname' => '계좌이체'],
    ['id' => 'naver', 'name' => '네이버페이', 'subname' => '(NAVER PAY)'],
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
            
            <!-- 결제 수단 선택 섹션 -->
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
            
            <!-- 구분선 -->
            <div style="height: 0.0625rem; background-color: rgba(0,0,0,0.1); width: 100%;"></div>
            
            <!-- 총 결제 예상 금액 -->
            <div class="flex items-center justify-between" style="width: 100%;">
                <h4 class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">총 결제 예상 금액</h4>
                <h4 class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;"><?= number_format($total_price) ?>원</h4>
            </div>
            
            <!-- 취소하기/결제하기 버튼 (모바일용) -->
            <div class="flex gap-5 w-full mobile-buttons" style="gap: 1.25rem; display: none;">
                <button class="button flex-1 bg-black text-white rounded" style="padding: 0.125rem 4.6875rem; font-size: 0.875rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; border-radius: 0.25rem;">취소하기</button>
                <button class="button flex-1 bg-black text-white rounded" style="padding: 0.125rem 4.6875rem; font-size: 0.875rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; border-radius: 0.25rem;">결제하기</button>
            </div>
        </div>
        
        <!-- 우측 패널 래퍼 -->
        <div class="flex flex-col order-right-wrapper" style="width: 17.5rem; flex-shrink: 0; align-self: stretch;">
            <!-- 우측 고정 박스 -->
            <div class="flex flex-col shrink-0 sticky-payment-box" style="width: 100%; gap: 1.25rem; padding-left: 1.25rem; align-self: flex-start;">
                <div class="flex items-start justify-between" style="width: 100%;">
                    <span class="bold" style="font-size: 1rem; line-height: 1.75rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;">결제 금액</span>
                    <span class="bold" style="font-size: 1.5rem; line-height: 2.25rem; font-weight: 700; letter-spacing: -0.0125rem; color: #000;"><?= number_format($total_price) ?>원</span>
                </div>
                <button class="button w-full bg-black text-white rounded" style="padding: 0.125rem 4.6875rem; font-size: 0.875rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.0125rem; border-radius: 0.25rem;">결제하기</button>
            </div>
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
        const formattedPrice = totalPrice.toLocaleString('ko-KR');
        
        // 좌측 총 결제 예상 금액 업데이트
        const leftTotalElements = document.querySelectorAll('.order-left-column h4.bold');
        leftTotalElements.forEach(el => {
            if (el.textContent.includes('총 결제 예상 금액')) {
                const nextSibling = el.nextElementSibling;
                if (nextSibling && nextSibling.classList.contains('bold')) {
                    nextSibling.textContent = formattedPrice + '원';
                }
            }
        });
        
        // 우측 결제 금액 업데이트
        const rightPaymentBox = document.querySelector('.sticky-payment-box');
        if (rightPaymentBox) {
            const rightAmount = rightPaymentBox.querySelector('span.bold:last-child');
            if (rightAmount) {
                rightAmount.textContent = formattedPrice + '원';
            }
        }
    }
    
    decreaseBtn.addEventListener('click', function(e) {
        e.preventDefault();
        updateQuantity(quantity - 1);
    });
    
    increaseBtn.addEventListener('click', function(e) {
        e.preventDefault();
        updateQuantity(quantity + 1);
    });
    
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
