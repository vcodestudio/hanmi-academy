# 메인페이 API 연동 가이드

## 목차

1. [개요](#개요)
2. [연동 절차](#연동-절차)
3. [기본 정보](#기본-정보)
4. [결제창 연동](#결제창-연동)
5. [결제 API 연동](#결제-api-연동)
6. [프로젝트 연동 솔루션](#프로젝트-연동-솔루션)
7. [공통코드](#공통코드)
8. [에러 처리](#에러-처리)
9. [샘플 코드](#샘플-코드)

---

## 개요

### 메인페이 소개

메인페이는 온라인 결제 서비스를 제공하는 PG(Payment Gateway) 사업자입니다. 본 문서는 메인페이 API를 사용하여 결제 시스템을 연동하는 방법을 설명합니다.

### 연동 방식 개요

메인페이는 두 가지 연동 방식을 제공합니다:

1. **결제창 연동**: PG사가 제공하는 결제창을 사용하는 방식
   - PC/모바일 웹사이트 및 앱에서 사용 가능
   - 신용카드, 계좌이체, 가상계좌, 휴대폰 결제 지원
   - 간편결제(카카오페이, 네이버페이, 페이코 등) 지원

2. **결제 API 연동**: 직접 API를 호출하여 결제를 처리하는 방식
   - 신용카드 수기 결제
   - 정기 결제
   - 결제 취소/환불
   - 가상계좌 발급 및 입금통보

---

## 연동 절차

메인페이 연동은 다음과 같은 절차로 진행됩니다:

1. **결제데모 테스트**
   - 결제데모 메뉴에서 메인페이를 둘러보고 결제 연동 방식을 선택
   - 결제창 연동에 관한 화면 구성 및 프로세스 파악

2. **개발 환경 설정**
   - 테스트 서버 정보 확인
   - 가맹점번호(mbrNo) 및 API Key 발급
   - 서버 방화벽 설정

3. **API 연동 개발**
   - 결제 준비 API 호출
   - 결제화면 요청 및 처리
   - 결제 승인 API 호출
   - 결제 취소/환불 API 구현

4. **테스트**
   - 테스트 서버에서 결제 테스트
   - 각 결제 수단별 테스트

5. **운영 전환**
   - 실서버 정보로 변경
   - 최종 테스트 후 운영 시작

---

## 기본 정보

### 호스트 정보

#### 테스트 서버
- **API 서버**: `test-api-std.mainpay.co.kr`
- **Relay 서버**: `test-relay.mainpay.co.kr`
- **Base URL**: `https://test-relay.mainpay.co.kr`

#### 실서버 (REAL)
- **API 서버**: `api-std.mainpay.co.kr`
- **Relay 서버**: `relay.mainpay.co.kr`
- **Base URL**: `https://relay.mainpay.co.kr`
- **IP 주소**: `211.43.193.87`

### 테스트 정보

테스트 서버 호출을 위한 정보입니다. (임의 변경될 수 있습니다.)

| 항목명 | 설명 | 값 |
|--------|------|-----|
| `mbrNo` | 가맹점번호 (상점 아이디) | `100011` |
| `apiKey` | 가맹점번호의 apiKey(암호키) | `U1FVQVJELTEwMDAxMTIwMTgwNDA2MDkyNTMyMTA1MjM0` |

**주의사항:**
- ※ 실제 결제처리 되지 않습니다.
- ※ 카카오페이의 경우 실결제되나 1시간 마다 취소 처리 됩니다.

### 서버 방화벽

#### 결제창 서버 방화벽
- 당사 결제창 서버는 **443(HTTPS)포트**에 한해 **ANY**로 오픈되어 있습니다.

**테스트 서버:**
- `test-api-std.mainpay.co.kr`
- `test-relay.mainpay.co.kr`

**REAL 서버:**
- `api-std.mainpay.co.kr`
- `relay.mainpay.co.kr`
- IP: `211.43.193.87`
  - 신용노티
  - 현금영수증 노티
  - 실시간 가상계좌 입금노티
  - 고정형 가상계좌 입금반복노티

### 인증 방법 (Signature 생성)

결제 위변조 방지를 위해 모든 API 호출 시 Signature를 생성하여 전달해야 합니다.

#### Signature 생성 방법

```php
function makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp) {
    $message = $mbrNo . "|" . $mbrRefNo . "|" . $amount . "|" . $apiKey . "|" . $timestamp;
    return hash("sha256", $message);
}
```

**파라미터 설명:**
- `mbrNo`: 가맹점번호
- `mbrRefNo`: 가맹점 주문번호 (중복되지 않는 고유 번호)
- `amount`: 결제금액 (숫자만)
- `apiKey`: 가맹점 API Key
- `timestamp`: 타임스탬프 (YYYYMMDDHHMMSS + 4자리 랜덤 숫자)

**타임스탬프 생성 예제:**
```php
function makeTimestamp() {
    date_default_timezone_set('Asia/Seoul');
    return date_create('now')->format('YmdHis') . generateRandomString(4);
}

function generateRandomString($length = 4) {
    return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)))), 1, $length);
}
```

---

## 결제창 연동

### 개요

결제창 연동은 PG사가 제공하는 결제창을 사용하여 결제를 처리하는 방식입니다. PC/모바일 웹사이트 및 앱에서 사용 가능하며, 신용카드, 계좌이체, 가상계좌, 휴대폰 결제 및 간편결제를 지원합니다.

### 결제 프로세스

```
1. 결제 준비 API 호출 (/v1/payment/ready)
   ↓
2. 결제화면 요청 (결제창 팝업 또는 리다이렉트)
   ↓
3. 사용자 결제 정보 입력
   ↓
4. approvalUrl로 결제 결과 전달
   ↓
5. 결제 승인 API 호출 (/v1/payment/pay)
   ↓
6. 결제 완료
```

### 1. 결제 준비 API

결제정보를 사전에 PG서버에 전달하고, 결제화면 요청을 위한 URL을 응답받습니다.

#### URL
- **HOST**: `https://api-std.mainpay.co.kr` (실서버)
- **HOST**: `https://test-api-std.mainpay.co.kr` (테스트)
- **POST**: `/v1/payment/ready`
- **Content-type**: `application/x-www-form-urlencoded; charset=utf-8`

#### 요청 파라미터

| 변수명 | 타입 | 필수 | 설명 |
|--------|------|------|------|
| `mbrNo` | String | 필수 | 가맹점번호 |
| `mbrRefNo` | String | 필수 | 가맹점 주문번호 (중복되지 않는 고유 번호) |
| `amount` | Number | 필수 | 결제금액 (숫자만) |
| `timestamp` | String | 필수 | 타임스탬프 (YYYYMMDDHHMMSS + 4자리 랜덤) |
| `signature` | String | 필수 | Signature 값 |
| `goodsName` | String | 선택 | 상품명 |
| `buyerName` | String | 선택 | 구매자명 |
| `buyerEmail` | String | 선택 | 구매자 이메일 |
| `buyerTel` | String | 선택 | 구매자 전화번호 |
| `approvalUrl` | String | 필수 | 결제 승인 후 이동할 URL |
| `closeUrl` | String | 필수 | 결제창 닫기 후 이동할 URL |
| `notiUrl` | String | 선택 | 결제결과 통지 URL |

#### 응답 예제

```json
{
  "resultCode": "200",
  "resultMessage": "성공",
  "data": {
    "refNo": "거래번호",
    "tranDate": "거래일자",
    "payUrl": "결제화면 요청 URL"
  }
}
```

### 2. 결제화면 요청

결제 준비 API에서 받은 `payUrl`을 사용하여 결제창을 호출합니다.

#### PC 웹사이트
```javascript
// 팝업 방식
window.open(payUrl, 'payment', 'width=500,height=700');

// 리다이렉트 방식
window.location.href = payUrl;
```

#### 모바일 웹/앱
```javascript
// 리다이렉트 방식
window.location.href = payUrl;
```

### 3. approvalUrl 처리

결제 완료 후 PG사에서 `approvalUrl`로 결제 결과를 POST 방식으로 전달합니다.

#### 전달되는 파라미터

| 변수명 | 설명 |
|--------|------|
| `refNo` | 거래번호 |
| `mbrRefNo` | 가맹점 주문번호 |
| `resultCode` | 결과코드 |
| `resultMessage` | 결과메시지 |
| `authCode` | 인증코드 (결제 승인 시 필요) |
| `cardNo` | 카드번호 (마스킹 처리) |
| `cardCode` | 카드사 코드 |
| `installment` | 할부개월 |
| `amount` | 결제금액 |

### 4. closeUrl 처리

사용자가 결제창을 닫을 경우 `closeUrl`로 이동합니다.

### 5. 결제 승인 API

`approvalUrl`을 통해 전달받은 인증결과와 주문정보를 이용해 승인API를 호출합니다.

#### URL
- **HOST**: `https://api-std.mainpay.co.kr` (실서버)
- **HOST**: `https://test-api-std.mainpay.co.kr` (테스트)
- **POST**: `/v1/payment/pay`
- **Content-type**: `application/x-www-form-urlencoded; charset=utf-8`

#### 요청 파라미터

| 변수명 | 타입 | 필수 | 설명 |
|--------|------|------|------|
| `mchtId` | String | 필수 | 가맹점번호 |
| `refNo` | String | 필수 | 거래번호 (approvalUrl에서 받은 값) |
| `mbrRefNo` | String | 필수 | 가맹점 주문번호 |
| `authCode` | String | 필수 | 인증코드 (approvalUrl에서 받은 값) |
| `amount` | Number | 필수 | 결제금액 |
| `timestamp` | String | 필수 | 타임스탬프 |
| `signature` | String | 필수 | Signature 값 |

#### 응답 예제

```json
{
  "resultCode": "200",
  "resultMessage": "성공",
  "data": {
    "refNo": "거래번호",
    "mbrRefNo": "가맹점 주문번호",
    "tranDate": "거래일자",
    "amount": 10000,
    "cardNo": "1234-****-****-5678",
    "cardCode": "카드사 코드",
    "installment": 0
  }
}
```

### 6. 가상계좌 입금통보

가상계좌 지불수단을 사용할 경우에만 구현합니다. 입금이 완료되면 `notiUrl`로 입금통보가 전달됩니다.

**참고**: 결제 API 연동 > 가상계좌 > 가상계좌 입금통보 가이드를 참조하세요.

---

## 결제 API 연동

### 개요

결제 API 연동은 직접 API를 호출하여 결제를 처리하는 방식입니다. 신용카드 수기 결제, 정기 결제, 결제 취소/환불 등을 지원합니다.

### 1. 신용카드 수기 결제

카드번호, 유효기간, CVC 등을 직접 입력받아 결제를 처리합니다.

**참고**: 결제 API 연동 > 신용카드 수기 결제 가이드를 참조하세요.

### 2. 결제 취소

#### 전액 취소

전체 결제 금액을 취소합니다.

**참고**: 결제 API 연동 > 결제 취소 > 전액 취소 가이드를 참조하세요.

#### 부분 취소

일부 금액만 취소합니다.

**참고**: 결제 API 연동 > 결제 취소 > 부분 취소 가이드를 참조하세요.

### 3. 결제 환불

#### 환불등록

환불을 등록합니다.

**참고**: 결제 API 연동 > 결제 환불 > 환불등록 가이드를 참조하세요.

#### 환불상태 조회

등록한 환불의 상태를 조회합니다.

**참고**: 결제 API 연동 > 결제 환불 > 환불상태 조회 가이드를 참조하세요.

#### 환불등록 취소

등록한 환불을 취소합니다.

**참고**: 결제 API 연동 > 결제 환불 > 환불등록 취소 가이드를 참조하세요.

---

## 프로젝트 연동 솔루션

### 프로젝트 구조 분석

#### 기존 결제 관련 파일

1. **`pages/page-order.php`** - 주문/결제 페이지
   - 결제 수단 선택 (카카오페이, 페이코, 무통장 입금, 신용카드, 계좌이체, 네이버페이, 휴대폰 결제)
   - 결제하기 버튼
   - 취소하기 버튼 (기능 미구현)

2. **`pages/page-payment-detail.php`** - 결제 상세 페이지
   - 주문 정보 표시
   - 예매자 정보 표시
   - 결제 정보 표시

3. **`pages/page-test-pay.php`** - 테스트 결제 페이지
   - 메인페이 API 호출 테스트

#### 메인페이 관련 파일

1. **`src/mainpay/config.php`** - 설정 파일
   - 테스트 환경 설정
   - 가맹점번호 및 API Key

2. **`src/mainpay/utils.php`** - 유틸리티 함수
   - `httpPost()`: HTTP POST 요청
   - `makeSignature()`: Signature 생성
   - `makeTimestamp()`: 타임스탬프 생성
   - `makeMbrRefNo()`: 가맹점 주문번호 생성
   - `pintLog()`: 로그 기록

3. **`src/mainpay/call_api.php`** - API 호출 예제
   - 결제 준비 API 호출 예제

### 연동 솔루션

#### 1. 결제 페이지 (`page-order.php`) 연동

**연동 포인트:**
- 결제 수단 선택 후 "결제하기" 버튼 클릭 시
- 메인페이 결제 준비 API 호출
- 결제창 팝업 또는 리다이렉트
- approvalUrl에서 결제 승인 처리
- 결제 완료 후 `page-payment-detail.php`로 이동

**구현 방법:**

1. **AJAX 핸들러 추가** (`src/php/ajax.php`)

```php
// 결제 준비
function mainpay_payment_ready() {
    require_once(get_stylesheet_directory() . '/src/mainpay/config.php');
    require_once(get_stylesheet_directory() . '/src/mainpay/utils.php');
    
    $mbrRefNo = makeMbrRefNo($mbrNo);
    $timestamp = makeTimestamp();
    $amount = intval($_POST['amount']);
    $goodsName = sanitize_text_field($_POST['goods_name']);
    $buyerName = sanitize_text_field($_POST['buyer_name']);
    $buyerEmail = sanitize_email($_POST['buyer_email']);
    $buyerTel = sanitize_text_field($_POST['buyer_tel']);
    
    $signature = makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp);
    
    $approvalUrl = home_url('/payment-approval');
    $closeUrl = home_url('/payment-close');
    
    $parameters = array(
        'mbrNo' => $mbrNo,
        'mbrRefNo' => $mbrRefNo,
        'amount' => $amount,
        'timestamp' => $timestamp,
        'signature' => $signature,
        'goodsName' => $goodsName,
        'buyerName' => $buyerName,
        'buyerEmail' => $buyerEmail,
        'buyerTel' => $buyerTel,
        'approvalUrl' => $approvalUrl,
        'closeUrl' => $closeUrl
    );
    
    $apiUrl = $API_BASE . "/v1/payment/ready";
    $result = httpPost($apiUrl, $parameters);
    $obj = json_decode($result);
    
    if ($obj->resultCode == "200") {
        wp_send_json_success(array(
            'payUrl' => $obj->data->payUrl,
            'refNo' => $obj->data->refNo,
            'mbrRefNo' => $mbrRefNo
        ));
    } else {
        wp_send_json_error(array(
            'message' => $obj->resultMessage
        ));
    }
}
wp_ajax('mainpay_payment_ready');
```

2. **결제 승인 처리 페이지 생성** (`pages/page-payment-approval.php`)

```php
<?php
get_header();

require_once(get_stylesheet_directory() . '/src/mainpay/config.php');
require_once(get_stylesheet_directory() . '/src/mainpay/utils.php');

$refNo = sanitize_text_field($_POST['refNo']);
$mbrRefNo = sanitize_text_field($_POST['mbrRefNo']);
$authCode = sanitize_text_field($_POST['authCode']);
$amount = intval($_POST['amount']);

$timestamp = makeTimestamp();
$signature = makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp);

$parameters = array(
    'mchtId' => $mbrNo,
    'refNo' => $refNo,
    'mbrRefNo' => $mbrRefNo,
    'authCode' => $authCode,
    'amount' => $amount,
    'timestamp' => $timestamp,
    'signature' => $signature
);

$apiUrl = $API_BASE . "/v1/payment/pay";
$result = httpPost($apiUrl, $parameters);
$obj = json_decode($result);

if ($obj->resultCode == "200") {
    // 결제 성공
    // 주문 정보를 DB에 저장
    // page-payment-detail.php로 리다이렉트
    wp_redirect(home_url('/payment-detail?order_id=' . $mbrRefNo));
    exit;
} else {
    // 결제 실패
    echo '<div class="error">결제 승인 실패: ' . esc_html($obj->resultMessage) . '</div>';
}

get_footer();
?>
```

3. **JavaScript 추가** (`page-order.php`에 추가)

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const paymentButton = document.querySelector('.sticky-payment-box button, .mobile-buttons button:last-child');
    
    if (paymentButton) {
        paymentButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                alert('결제 수단을 선택해주세요.');
                return;
            }
            
            // 결제 정보 수집
            const paymentData = {
                action: 'mainpay_payment_ready',
                amount: <?= $total_price ?>,
                goods_name: '<?= esc_js($order_item['title']) ?>',
                buyer_name: '<?= esc_js($orderer['name']) ?>',
                buyer_email: '<?= esc_js($orderer['email']) ?>',
                buyer_tel: '<?= esc_js($orderer['phone']) ?>'
            };
            
            // AJAX 요청
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(paymentData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 결제창 열기
                    window.open(data.data.payUrl, 'payment', 'width=500,height=700');
                } else {
                    alert('결제 준비 실패: ' + data.data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('결제 처리 중 오류가 발생했습니다.');
            });
        });
    }
});
```

#### 2. 취소/환불 기능 구현

**연동 포인트:**
- `page-payment-detail.php`에 취소/환불 버튼 추가
- 취소: 메인페이 결제 취소 API 호출
- 환불: 메인페이 환불 API 호출
- AJAX로 처리하여 페이지 새로고침 없이 처리

**구현 방법:**

1. **취소/환불 AJAX 핸들러 추가** (`src/php/ajax.php`)

```php
// 결제 취소
function mainpay_payment_cancel() {
    require_once(get_stylesheet_directory() . '/src/mainpay/config.php');
    require_once(get_stylesheet_directory() . '/src/mainpay/utils.php');
    
    $refNo = sanitize_text_field($_POST['ref_no']);
    $mbrRefNo = sanitize_text_field($_POST['mbr_ref_no']);
    $amount = intval($_POST['amount']);
    $cancelReason = sanitize_text_field($_POST['cancel_reason']);
    
    $timestamp = makeTimestamp();
    $signature = makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp);
    
    $parameters = array(
        'mchtId' => $mbrNo,
        'refNo' => $refNo,
        'mbrRefNo' => $mbrRefNo,
        'cancelAmount' => $amount,
        'cancelReason' => $cancelReason,
        'timestamp' => $timestamp,
        'signature' => $signature
    );
    
    $apiUrl = $API_BASE . "/v1/payment/cancel";
    $result = httpPost($apiUrl, $parameters);
    $obj = json_decode($result);
    
    if ($obj->resultCode == "200") {
        wp_send_json_success(array(
            'message' => '결제가 취소되었습니다.'
        ));
    } else {
        wp_send_json_error(array(
            'message' => $obj->resultMessage
        ));
    }
}
wp_ajax('mainpay_payment_cancel');
```

2. **결제 상세 페이지에 취소/환불 버튼 추가** (`page-payment-detail.php`)

```php
<!-- 결제 정보 섹션 하단에 추가 -->
<div class="payment-actions" style="margin-top: 2rem; display: flex; gap: 1rem;">
    <button id="cancel-payment" class="button" style="padding: 0.75rem 2rem; background-color: #f0f0f0; border: 1px solid #ccc; cursor: pointer;">결제 취소</button>
    <button id="refund-payment" class="button" style="padding: 0.75rem 2rem; background-color: #f0f0f0; border: 1px solid #ccc; cursor: pointer;">환불 요청</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cancelButton = document.getElementById('cancel-payment');
    const refundButton = document.getElementById('refund-payment');
    
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            if (!confirm('결제를 취소하시겠습니까?')) {
                return;
            }
            
            const cancelData = {
                action: 'mainpay_payment_cancel',
                ref_no: '<?= esc_js($payment['ref_no']) ?>',
                mbr_ref_no: '<?= esc_js($order_id) ?>',
                amount: <?= $payment['amount'] ?>,
                cancel_reason: '고객 요청'
            };
            
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(cancelData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.data.message);
                    location.reload();
                } else {
                    alert('취소 실패: ' + data.data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('처리 중 오류가 발생했습니다.');
            });
        });
    }
    
    // 환불 버튼 이벤트도 유사하게 구현
});
</script>
```

#### 3. 설정 파일 업데이트

**`src/mainpay/config.php`** 파일을 프로젝트에 맞게 업데이트:

```php
<?php
require('utils.php');

$logPath = get_stylesheet_directory() . "/src/app.log";

// 환경 설정 (테스트/실서버)
$is_test_mode = true; // 운영 시 false로 변경

if ($is_test_mode) {
    $API_BASE = "https://test-relay.mainpay.co.kr";
    $mbrNo = "100011";
    $apiKey = "U1FVQVJFLTEwMDAxMTIwMTgwNDA2MDkyNTMyMTA1MjM0";
} else {
    $API_BASE = "https://relay.mainpay.co.kr";
    $mbrNo = "실제_가맹점번호"; // 실제 가맹점번호로 변경 필요
    $apiKey = "실제_API_KEY"; // 실제 API Key로 변경 필요
}
?>
```

---

## 공통코드

### 카드사 코드

| 코드 | 카드사명 |
|------|----------|
| 01 | 비씨카드 |
| 02 | KB국민카드 |
| 03 | 하나카드 |
| 04 | 삼성카드 |
| 06 | 신한카드 |
| 07 | 현대카드 |
| 11 | 우리카드 |
| 12 | NH농협카드 |
| 14 | 롯데카드 |
| 15 | 씨티카드 |
| 16 | 카카오뱅크 |
| 17 | 케이뱅크 |
| 21 | 광주은행 |
| 22 | 전북은행 |
| 23 | 제주은행 |
| 24 | 신협 |
| 25 | 수협은행 |
| 26 | MG새마을금고 |
| 27 | 저축은행중앙회 |
| 31 | DGB대구은행 |
| 32 | 부산은행 |
| 33 | 경남은행 |
| 34 | KDB산업은행 |
| 35 | IBK기업은행 |
| 36 | KEB하나은행 |
| 37 | SC제일은행 |
| 38 | KB증권 |
| 39 | 미래에셋대우 |
| 41 | 우체국 |
| 42 | KEB하나카드 |
| 43 | 씨티은행 |
| 44 | 카카오뱅크 |
| 45 | 토스뱅크 |
| 46 | 신한은행 |
| 47 | KB국민은행 |
| 48 | 우리은행 |
| 49 | NH농협은행 |
| 50 | 케이뱅크 |
| 51 | 하나은행 |
| 52 | SC제일은행 |
| 53 | 대구은행 |
| 54 | 부산은행 |
| 55 | 광주은행 |
| 56 | 전북은행 |
| 57 | 제주은행 |
| 58 | 경남은행 |
| 59 | 새마을금고 |
| 60 | 신협 |
| 61 | 수협은행 |
| 62 | 저축은행중앙회 |
| 63 | 산업은행 |
| 64 | 기업은행 |
| 65 | 우체국 |
| 66 | MG새마을금고 |
| 67 | 카카오뱅크 |
| 68 | 토스뱅크 |
| 69 | 신한은행 |
| 70 | KB국민은행 |
| 71 | 우리은행 |
| 72 | NH농협은행 |
| 73 | 케이뱅크 |
| 74 | 하나은행 |
| 75 | SC제일은행 |
| 76 | 대구은행 |
| 77 | 부산은행 |
| 78 | 광주은행 |
| 79 | 전북은행 |
| 80 | 제주은행 |
| 81 | 경남은행 |
| 82 | 새마을금고 |
| 83 | 신협 |
| 84 | 수협은행 |
| 85 | 저축은행중앙회 |
| 86 | 산업은행 |
| 87 | 기업은행 |
| 88 | 우체국 |
| 89 | MG새마을금고 |
| 90 | 카카오뱅크 |
| 91 | 토스뱅크 |
| 92 | 신한은행 |
| 93 | KB국민은행 |
| 94 | 우리은행 |
| 95 | NH농협은행 |
| 96 | 케이뱅크 |
| 97 | 하나은행 |
| 98 | SC제일은행 |
| 99 | 대구은행 |

**참고**: 실제 카드사 코드는 메인페이 개발자 사이트의 공통코드 섹션에서 확인하세요.

### 은행사 코드

| 코드 | 은행명 |
|------|--------|
| 01 | 한국은행 |
| 02 | 산업은행 |
| 03 | 기업은행 |
| 04 | KB국민은행 |
| 05 | 하나은행 |
| 06 | 신한은행 |
| 07 | 우리은행 |
| 08 | NH농협은행 |
| 09 | 케이뱅크 |
| 10 | 카카오뱅크 |
| 11 | 토스뱅크 |
| 12 | SC제일은행 |
| 13 | 대구은행 |
| 14 | 부산은행 |
| 15 | 광주은행 |
| 16 | 전북은행 |
| 17 | 제주은행 |
| 18 | 경남은행 |
| 19 | 새마을금고 |
| 20 | 신협 |
| 21 | 수협은행 |
| 22 | 저축은행중앙회 |
| 23 | 우체국 |

**참고**: 실제 은행사 코드는 메인페이 개발자 사이트의 공통코드 섹션에서 확인하세요.

---

## 에러 처리

### 공통 에러 코드

| 에러코드 | 설명 |
|----------|------|
| 200 | 성공 |
| 400 | 잘못된 요청 |
| 401 | 인증 실패 |
| 403 | 권한 없음 |
| 404 | 리소스를 찾을 수 없음 |
| 500 | 서버 오류 |

### 결제 관련 에러 코드

| 에러코드 | 설명 |
|----------|------|
| 1001 | 결제 금액 오류 |
| 1002 | 가맹점번호 오류 |
| 1003 | 주문번호 중복 |
| 1004 | Signature 오류 |
| 1005 | 타임스탬프 오류 |
| 2001 | 결제 승인 실패 |
| 2002 | 카드 한도 초과 |
| 2003 | 카드 정지 |
| 3001 | 취소 실패 |
| 3002 | 이미 취소된 거래 |
| 3003 | 취소 가능 기간 초과 |

**참고**: 실제 에러 코드는 메인페이 개발자 사이트에서 확인하세요.

### 에러 처리 방법

```php
$result = httpPost($apiUrl, $parameters);
$obj = json_decode($result);

if ($obj->resultCode != "200") {
    // 에러 처리
    $errorMessage = $obj->resultMessage;
    pintLog("ERROR: " . $errorMessage, $logPath);
    
    // 사용자에게 에러 메시지 표시
    wp_send_json_error(array(
        'message' => $errorMessage,
        'code' => $obj->resultCode
    ));
}
```

---

## 샘플 코드

### PHP 샘플 코드

#### 결제 준비 API 호출

```php
<?php
require_once('config.php');
require_once('utils.php');

// 주문 정보
$mbrRefNo = makeMbrRefNo($mbrNo);
$timestamp = makeTimestamp();
$amount = 10000; // 결제금액
$goodsName = "테스트 상품";
$buyerName = "홍길동";
$buyerEmail = "test@example.com";
$buyerTel = "010-1234-5678";

// Signature 생성
$signature = makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp);

// 요청 파라미터
$parameters = array(
    'mbrNo' => $mbrNo,
    'mbrRefNo' => $mbrRefNo,
    'amount' => $amount,
    'timestamp' => $timestamp,
    'signature' => $signature,
    'goodsName' => $goodsName,
    'buyerName' => $buyerName,
    'buyerEmail' => $buyerEmail,
    'buyerTel' => $buyerTel,
    'approvalUrl' => 'https://yourdomain.com/payment-approval',
    'closeUrl' => 'https://yourdomain.com/payment-close'
);

// API 호출
$apiUrl = $API_BASE . "/v1/payment/ready";
$result = httpPost($apiUrl, $parameters);
$obj = json_decode($result);

if ($obj->resultCode == "200") {
    // 성공
    $payUrl = $obj->data->payUrl;
    $refNo = $obj->data->refNo;
    
    // 결제창 호출
    echo "<script>window.open('" . $payUrl . "', 'payment', 'width=500,height=700');</script>";
} else {
    // 실패
    echo "결제 준비 실패: " . $obj->resultMessage;
}
?>
```

#### 결제 승인 API 호출

```php
<?php
require_once('config.php');
require_once('utils.php');

// approvalUrl에서 받은 파라미터
$refNo = $_POST['refNo'];
$mbrRefNo = $_POST['mbrRefNo'];
$authCode = $_POST['authCode'];
$amount = intval($_POST['amount']);

// Signature 생성
$timestamp = makeTimestamp();
$signature = makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp);

// 요청 파라미터
$parameters = array(
    'mchtId' => $mbrNo,
    'refNo' => $refNo,
    'mbrRefNo' => $mbrRefNo,
    'authCode' => $authCode,
    'amount' => $amount,
    'timestamp' => $timestamp,
    'signature' => $signature
);

// API 호출
$apiUrl = $API_BASE . "/v1/payment/pay";
$result = httpPost($apiUrl, $parameters);
$obj = json_decode($result);

if ($obj->resultCode == "200") {
    // 결제 성공
    // 주문 정보를 DB에 저장
    // 결제 완료 페이지로 리다이렉트
    header('Location: /payment-success?order_id=' . $mbrRefNo);
} else {
    // 결제 실패
    echo "결제 승인 실패: " . $obj->resultMessage;
}
?>
```

#### 결제 취소 API 호출

```php
<?php
require_once('config.php');
require_once('utils.php');

// 취소 정보
$refNo = "거래번호";
$mbrRefNo = "가맹점주문번호";
$cancelAmount = 10000; // 취소 금액
$cancelReason = "고객 요청";

// Signature 생성
$timestamp = makeTimestamp();
$signature = makeSignature($mbrNo, $mbrRefNo, $cancelAmount, $apiKey, $timestamp);

// 요청 파라미터
$parameters = array(
    'mchtId' => $mbrNo,
    'refNo' => $refNo,
    'mbrRefNo' => $mbrRefNo,
    'cancelAmount' => $cancelAmount,
    'cancelReason' => $cancelReason,
    'timestamp' => $timestamp,
    'signature' => $signature
);

// API 호출
$apiUrl = $API_BASE . "/v1/payment/cancel";
$result = httpPost($apiUrl, $parameters);
$obj = json_decode($result);

if ($obj->resultCode == "200") {
    // 취소 성공
    echo "결제가 취소되었습니다.";
} else {
    // 취소 실패
    echo "취소 실패: " . $obj->resultMessage;
}
?>
```

### JavaScript 샘플 코드

#### 결제 준비 및 결제창 호출

```javascript
// 결제하기 버튼 클릭 이벤트
document.getElementById('payment-button').addEventListener('click', function() {
    // 결제 정보 수집
    const paymentData = {
        action: 'mainpay_payment_ready',
        amount: 10000,
        goods_name: '테스트 상품',
        buyer_name: '홍길동',
        buyer_email: 'test@example.com',
        buyer_tel: '010-1234-5678'
    };
    
    // AJAX 요청
    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(paymentData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 결제창 열기
            const payWindow = window.open(
                data.data.payUrl,
                'payment',
                'width=500,height=700,scrollbars=yes,resizable=yes'
            );
            
            // 결제창 닫기 감지
            const checkClosed = setInterval(function() {
                if (payWindow.closed) {
                    clearInterval(checkClosed);
                    // 결제 완료 여부 확인
                    location.reload();
                }
            }, 1000);
        } else {
            alert('결제 준비 실패: ' + data.data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('결제 처리 중 오류가 발생했습니다.');
    });
});
```

---

## 참고 자료

- 메인페이 개발자 사이트: https://developers.mainpay.co.kr
- 테스트 계정: merchant001 / apdlsvpdl0011!

---

## 문의

메인페이 기술지원팀에 문의하시기 바랍니다.
