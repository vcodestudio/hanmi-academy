<?php
/**
 * Template Name: 결제 결과 통지 (notiUrl)
 * Mainpay 결제 결과(특히 가상계좌 입금)를 처리하는 페이지입니다.
 */

// WordPress 로드 (필요한 경우)
if (!defined('ABSPATH')) {
    exit;
}

require_once(get_stylesheet_directory() . '/src/mainpay/config.php');
require_once(get_stylesheet_directory() . '/src/mainpay/utils.php');

// 로그 기록
pintLog("NOTI-INCOMING: " . print_r($_REQUEST, TRUE), $logPath);

$resultCode = sanitize_text_field($_REQUEST['resultCode'] ?? '');
$resultMessage = sanitize_text_field($_REQUEST['resultMessage'] ?? '');
$mbrRefNo = sanitize_text_field($_REQUEST['mbrRefNo'] ?? '');
$refNo = sanitize_text_field($_REQUEST['refNo'] ?? '');
$amount = intval($_REQUEST['amount'] ?? 0);
$paymethod = sanitize_text_field($_REQUEST['paymethod'] ?? '');

// 필수 정보 체크
if (empty($mbrRefNo) || empty($resultCode)) {
    pintLog("NOTI-ERROR: Missing essential fields (mbrRefNo, resultCode)", $logPath);
    echo "FAIL: Missing essential fields";
    exit;
}

// 주문 찾기
$existing_orders = get_posts(array(
    'post_type' => 'post_order',
    'meta_query' => array(
        array(
            'key' => 'order_mbr_ref_no',
            'value' => $mbrRefNo,
            'compare' => '='
        )
    ),
    'posts_per_page' => 1,
    'post_status' => 'any'
));

if (empty($existing_orders)) {
    pintLog("NOTI-ERROR: Order not found ($mbrRefNo)", $logPath);
    echo "FAIL: Order not found";
    exit;
}

$order_post_id = $existing_orders[0]->ID;

// 결과 처리
if ($resultCode === '200') {
    // 입금 완료/결제 성공 처리
    update_field('order_status', 'success', $order_post_id);
    update_field('order_ref_no', $refNo, $order_post_id);
    
    // 만약 거래일시(tranDate) 정보가 오면 업데이트
    $tranDate = sanitize_text_field($_REQUEST['tranDate'] ?? '');
    if ($tranDate) {
        update_field('order_tran_date', $tranDate, $order_post_id);
    }
    
    pintLog("NOTI-SUCCESS: Order $mbrRefNo updated to success", $logPath);
    echo "OK";
} else {
    // 결제 실패 또는 취소 처리 (상황에 따라 다름)
    // 가상계좌의 경우 만료 등 실패 코드가 올 수 있음
    pintLog("NOTI-INFO: Payment failed or cancelled for $mbrRefNo (Code: $resultCode, Msg: $resultMessage)", $logPath);
    echo "OK"; // 응답은 OK로 주어야 PG사가 재전송을 안함
}

exit;
