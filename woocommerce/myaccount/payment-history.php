<?php
/**
 * Payment History
 *
 * Shows payment history on the account page.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

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
        'date' => '2025.11.20',
        'title' => '다른 프로그램 제목',
        'schedule' => '매주 금요일 · 오후 2시',
        'image' => function_exists('getImg') ? getImg('empty.svg') : (SRC . '/imgs/system/empty.svg'),
        'price' => 30000,
        'quantity' => 2,
        'order_id' => 'ORD-002'
    ],
];
?>

<?php if ( !empty($orders) ) : ?>
    <div class="tw-flex tw-flex-col tw-gap-[24px]">
        <?php foreach ($orders as $order): ?>
            <div class="tw-flex tw-flex-col tw-gap-[24px]">
                <div class="bold tw-text-[16px] tw-leading-[24px]">주문일자 <?= esc_html($order['date']) ?></div>
                
                <div class="tw-flex tw-gap-[24px] m:tw-gap-[16px] tw-items-center tw-w-full m:tw-flex-col m:tw-items-start">
                    <div class="tw-w-[170px] m:tw-w-full m:tw-max-w-[170px] tw-h-[170px] m:tw-h-auto m:tw-aspect-square tw-shrink-0 tw-relative tw-overflow-hidden">
                        <img src="<?= esc_url($order['image']) ?>" alt="<?= esc_attr($order['title']) ?>" class="tw-w-full tw-h-full tw-object-cover" />
                    </div>
                    
                    <div class="tw-flex tw-flex-row m:tw-flex-col tw-items-center m:tw-items-start tw-flex-1 tw-self-stretch m:tw-w-full">
                        <div class="tw-flex tw-flex-col tw-h-full m:tw-h-auto tw-justify-between tw-flex-1 tw-min-h-0 tw-pr-[40px] m:tw-pr-0 m:tw-gap-[8px]">
                            <div class="tw-flex tw-flex-col tw-gap-[8px]">
                                <h4 class="bold tw-text-[24px] m:tw-text-[20px] tw-leading-[36px] m:tw-leading-[30px]"><?= esc_html($order['title']) ?></h4>
                                <p class="text-sub tw-text-[16px] tw-leading-[24px]"><?= esc_html($order['schedule']) ?></p>
                            </div>
                            <p class="text-sub tw-text-[16px] tw-leading-[24px]"><?= number_format($order['price']) ?>원 / <?= $order['quantity'] ?>매</p>
                        </div>
                    </div>
                    
                    <div class="tw-shrink-0 m:tw-w-full">
                        <a href="/payment-detail?order_id=<?= esc_attr($order['order_id']) ?>" class="button m:tw-w-full m:tw-text-center">결제상세</a>
                    </div>
                </div>
                
                <div class="tw-h-px tw-bg-black/10"></div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-[24px] tw-py-[60px]">
        <p class="text-sub">결제 내역이 없습니다.</p>
    </div>
<?php endif; ?>

