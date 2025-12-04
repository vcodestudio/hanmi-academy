<?php
// 멤버쉽 탭 - WooCommerce Subscriptions 연동
if ( ! defined( 'ABSPATH' ) ) exit;

$user = (isset($arg) && is_array($arg) && isset($arg["user"])) ? $arg["user"] : wp_get_current_user();

// WooCommerce Subscriptions 플러그인이 활성화되어 있는지 확인
if (class_exists('WC_Subscriptions')) {
    // WooCommerce 구독 내역 가져오기
    $subscriptions = wcs_get_users_subscriptions($user->ID);
    
    if (!empty($subscriptions)) {
        ?>
        <div class="subscriptions-list">
            <?php foreach ($subscriptions as $subscription) : ?>
                <div class="subscription-item">
                    <h3>구독 #<?php echo esc_html($subscription->get_order_number()); ?></h3>
                    <p>상태: <?php echo esc_html(wcs_get_subscription_status_name($subscription->get_status())); ?></p>
                    <?php if ($subscription->get_date('next_payment')) : ?>
                        <p>다음 결제일: <?php echo esc_html($subscription->get_date_to_display('next_payment')); ?></p>
                    <?php endif; ?>
                    <p>총액: <?php echo wp_kses_post($subscription->get_formatted_order_total()); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    } else {
        ?>
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 24px; padding: 60px 0;">
            <p style="color: #888888; font-size: 16px; line-height: 24px;">활성 구독이 없습니다.</p>
        </div>
        <?php
    }
} else {
    // WooCommerce Subscriptions 플러그인이 없는 경우
    ?>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 24px; padding: 60px 0;">
        <p style="color: #888888; font-size: 16px; line-height: 24px;">멤버쉽 기능을 사용할 수 없습니다.</p>
    </div>
    <?php
}
?>

