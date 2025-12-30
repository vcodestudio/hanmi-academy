<?php
get_header();

require_once(get_stylesheet_directory() . '/src/mainpay/config.php');
require_once(get_stylesheet_directory() . '/src/mainpay/utils.php');

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

// approvalUrl로 전달받은 파라미터
pintLog("APPROVAL-INCOMING: " . print_r($_REQUEST, TRUE), $logPath);

// Mainpay는 aid를 refNo로, authToken을 authCode로 보내는 경우가 있으므로 이에 대응
$refNo = sanitize_text_field($_REQUEST['refNo'] ?? $_REQUEST['aid'] ?? '');
$mbrRefNo = sanitize_text_field($_REQUEST['mbrRefNo'] ?? '');
$authCode = sanitize_text_field($_REQUEST['authCode'] ?? $_REQUEST['authToken'] ?? '');
$resultCode = sanitize_text_field($_REQUEST['resultCode'] ?? '');
$resultMessage = sanitize_text_field($_REQUEST['resultMessage'] ?? '');
$amount = intval($_REQUEST['amount'] ?? 0);
$paymethod = sanitize_text_field($_REQUEST['paymethod'] ?? '');

// 세션에서 주문 정보 가져오기
$orderInfo = $_SESSION['mainpay_order'] ?? null;
$program_id = isset($orderInfo['program_id']) ? intval($orderInfo['program_id']) : 0;

// 실제 주문 페이지 URL 생성 (프로그램 ID가 있으면 포함)
$order_page_url = $program_id > 0 ? home_url('/order?program_id=' . $program_id) : home_url('/order');

// amount가 POST로 전달되지 않으면 세션에서 가져오기
if ($amount <= 0 && isset($orderInfo['amount'])) {
    $amount = intval($orderInfo['amount']);
}

// paymethod가 POST로 전달되지 않으면 세션에서 가져오기
if (empty($paymethod) && isset($orderInfo['paymethod'])) {
    $paymethod = $orderInfo['paymethod'];
}

// 필수 파라미터 체크
if (empty($refNo) || empty($mbrRefNo) || empty($authCode)) {
    ?>
    <div class="page-wrap" style="padding: 2rem; text-align: center;">
        <h2>결제 승인 실패</h2>
        <p>필수 정보가 누락되었습니다. (refNo, mbrRefNo, authCode)</p>
        <a href="<?= esc_url($order_page_url) ?>" class="button" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 2rem; background-color: #000; color: #fff; text-decoration: none;">주문 페이지로 돌아가기</a>
    </div>
    <?php
    get_footer();
    exit;
}

// amount가 0이면 무료결제로 처리 (이 페이지는 유료결제 승인 페이지이므로 잘못된 접근)
if ($amount <= 0) {
    ?>
    <div class="page-wrap" style="padding: 2rem; text-align: center;">
        <h2>결제 승인 실패</h2>
        <p>결제 금액이 올바르지 않습니다. (amount: <?= $amount ?>)</p>
        <p style="color: #666; margin-top: 0.5rem;">무료 상품은 별도의 신청 프로세스를 통해 처리됩니다.</p>
        <a href="<?= esc_url($order_page_url) ?>" class="button" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 2rem; background-color: #000; color: #fff; text-decoration: none;">주문 페이지로 돌아가기</a>
    </div>
    <?php
    get_footer();
    exit;
}

// 결제 승인 API 호출
$timestamp = makeTimestamp();

// 보안: 요청받은 amount 대신 세션에 저장된 원래 주문 금액 사용
if (isset($orderInfo['amount']) && intval($orderInfo['amount']) > 0) {
    $amount = intval($orderInfo['amount']);
}

$signature = makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp);

$parameters = array(
    'mbrNo' => $mbrNo, // mchtId 대신 mbrNo 사용 시도
    'aid' => $refNo,   // refNo 대신 aid 사용
    'mbrRefNo' => $mbrRefNo,
    'authToken' => $authCode, // authCode 대신 authToken 사용
    'amount' => $amount,
    'timestamp' => $timestamp,
    'signature' => $signature
);

$apiUrl = $API_BASE . "/v1/payment/pay";
pintLog("APPROVAL-API: " . $apiUrl, $logPath);
pintLog("PARAM: " . print_r($parameters, TRUE), $logPath);

// 망취소 처리를 포함한 API 호출
$result = "";
try {
    $result = httpPost($apiUrl, $parameters);
    
    // 응답이 없거나 CURL 에러가 발생한 경우 예외 발생
    $checkObj = json_decode($result);
    if (!$result || (isset($checkObj->resultCode) && $checkObj->resultCode == "500")) {
        throw new Exception("승인 API 응답 수신 실패 또는 통신 오류");
    }
} catch (Exception $e) {
    pintLog("PAY-API-ERROR: " . $e->getMessage(), $logPath);
    
    // 망취소 API 호출 (승인 요청 중 통신 오류 발생 시)
    $netCancelUrl = $API_BASE . "/v1/payment/net-cancel";
    pintLog("NET-CANCEL-CALL: " . $netCancelUrl, $logPath);
    $cancelResult = httpPost($netCancelUrl, $parameters);
    pintLog("NET-CANCEL-RESULT: " . $cancelResult, $logPath);
    
    // 사용자에게는 실패로 응답
    $result = json_encode(array(
        'resultCode' => 'FAIL',
        'resultMessage' => '결제 처리 중 네트워크 오류가 발생하여 자동 취소되었습니다. 다시 시도해주세요.'
    ));
}

pintLog("RESPONSE: " . $result, $logPath);
$obj = json_decode($result);

if ($obj && isset($obj->resultCode) && $obj->resultCode == "200") {
    // 결제 성공
    // 주문 정보를 세션에 저장 (실제로는 DB에 저장해야 함)
    // paymethod 우선순위: POST 파라미터 > 세션 > 기본값
    if (empty($paymethod)) {
        $paymethod = $orderInfo['paymethod'] ?? 'CARD';
    }
    
    // 결제 수단 한글명 매핑
    $paymethodNames = array(
        'CARD' => '신용카드',
        'ACCT' => '실시간 계좌이체',
        'VACCT' => '가상계좌',
        'HPP' => '휴대폰 결제',
        'CULT' => '문화상품권'
    );
    $paymethodName = $paymethodNames[$paymethod] ?? '신용카드';
    
    // 가상계좌(VACCT)인 경우 상태를 'waiting'으로 설정
    $status = ($paymethod === 'VACCT') ? 'waiting' : 'success';
    
    // 신청자 수 증가 (결제 완료 또는 가상계좌 발급 시)
    $program_id = $orderInfo['program_id'] ?? 0;
    $quantity = intval($orderInfo['quantity'] ?? 1);
    if ($program_id > 0 && $quantity > 0) {
        $current_count = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
        update_post_meta($program_id, 'program_applicants_count', $current_count + $quantity);
    }
    
    $_SESSION['mainpay_payment_result'] = array(
        'refNo' => $obj->data->refNo ?? $refNo,
        'mbrRefNo' => $mbrRefNo,
        'amount' => $amount,
        'tranDate' => $obj->data->tranDate ?? date('YmdHis'),
        'cardNo' => $obj->data->cardNo ?? '',
        'cardCode' => $obj->data->cardCode ?? '',
        'paymethod' => $paymethod,
        'paymethodName' => $paymethodName,
        'installment' => $obj->data->installment ?? 0,
        'status' => $status,
        // 가상계좌 추가 정보
        'bankCode' => $obj->data->bankCode ?? '',
        'accountNo' => $obj->data->accountNo ?? '',
        'accountCloseDate' => $obj->data->accountCloseDate ?? '',
    );
    
    // 주문 정보에 user_id 추가
    $orderInfo['user_id'] = get_current_user_id();
    
    // 주문을 DB에 저장
    require_once(get_stylesheet_directory() . '/src/php/ajax.php');
    $order_post_id = create_order_post($orderInfo, $_SESSION['mainpay_payment_result']);
    if ($order_post_id) {
        $_SESSION['mainpay_order_post_id'] = $order_post_id;
    }
    
    // 결제 완료 페이지로 리다이렉트
    wp_redirect(home_url('/payment-detail?order_id=' . $mbrRefNo . '&status=success'));
    exit;
} else {
    // 결제 실패
    $errorMessage = isset($obj->resultMessage) ? $obj->resultMessage : '결제 승인에 실패했습니다.';
    ?>
    <div class="page-wrap" style="padding: 2rem; text-align: center;">
        <h2>결제 승인 실패</h2>
        <p><?= esc_html($errorMessage) ?></p>
        <a href="<?= esc_url($order_page_url) ?>" class="button" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 2rem; background-color: #000; color: #fff; text-decoration: none;">주문 페이지로 돌아가기</a>
    </div>
    <?php
}

get_footer();
?>
