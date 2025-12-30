<?php
get_header();

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

// 결제창이 닫혔을 때 처리
// 세션에서 주문 정보 가져오기
$order_info = $_SESSION['mainpay_order'] ?? null;
$program_id = $order_info['program_id'] ?? 0;
$redirect_url = $program_id > 0 ? home_url('/order?program_id=' . $program_id) : home_url('/order');
?>
<div class="page-wrap" style="padding: 2rem; text-align: center;">
    <h2>결제가 취소되었습니다</h2>
    <p>결제창을 닫으셨습니다.</p>
    <p style="margin-top: 0.5rem; color: #666; font-size: 0.875rem;">잠시 후 자동으로 주문 페이지로 이동합니다.</p>
    <a href="<?= esc_url($redirect_url) ?>" class="button" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 2rem; background-color: #000; color: #fff; text-decoration: none;">주문 페이지로 돌아가기</a>
</div>

<script>
// 부모 창이 있으면 닫기
if (window.opener) {
    // 부모 창으로 리다이렉트 URL 전달
    setTimeout(function() {
        window.opener.location.href = '<?= esc_js($redirect_url) ?>';
        window.close();
    }, 2000);
} else {
    // 부모 창이 없으면 현재 창에서 리다이렉트
    setTimeout(function() {
        window.location.href = '<?= esc_js($redirect_url) ?>';
    }, 2000);
}
</script>

<?php
get_footer();
?>
