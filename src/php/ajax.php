<?php
	function wp_ajax($function,$priv=false) {
		$out = "wp_ajax_{$function}";
		add_action( $out, $function);
		if(!$priv) {
            $out = "wp_ajax_nopriv_{$function}";
		    add_action( $out, $function);
        }
	}
	function wpse27856_set_content_type(){
        return "text/html";
    }
    //functions 
	function createEmailCert() {
		session_start();
        $_SESSION["email"] = $_POST["email"];
		$_SESSION["cert_numb"] = rand(100000,999999);
        $etc = (is_user_logged_in() || isset($_POST["pw_reset"]));

        if(!$etc && get_user_by_email($_SESSION["email"])) {
            wp_send_json([
                "data"=>"<span class='alert'>이미 등록된 이메일 입니다.</span>"
            ]);
            exit;
        }
        ob_start();
        ?>
        <div>
            <h2>뮤지엄한미 이메일 인증</h2>
            <p>요청하신 이메일 인증번호 입니다.</p>
            <br/>
            <p>
            <b>
                인증번호
            </b>
            </p>
            <h1><?= $_SESSION["cert_numb"] ?></h1>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );
        $mailto = wp_mail($_SESSION["email"], "[뮤지엄한미] 이메일 인증번호", $html, "From : 뮤지엄한미 <noreply@museumhanmi.or.kr>");
        if($mailto) {
            $args = [
                "data"=>"인증번호를 발송했습니다.",
                "mail"=>$_SESSION["email"]
            ];
            if(0 != strpos($_SERVER["HTTP_HOST"],".local")) $args["test"] = $_SESSION["cert_numb"];
            wp_send_json($args);
        } else {
            wp_send_json([
                "data"=>"<span class='alert'>올바른 이메일 주소를 입력해주세요.</span>"
            ]);
        }
	}
	wp_ajax("createEmailCert");
	
	function getNumber() {
		session_start();
		wp_send_json($_SESSION["cert_numb"] ?? "0");
	}
	wp_ajax("getNumber");
    function searchID() {
        $id = $_POST["id"] ?? "";
        $msg = "";
        if(username_exists($id)) {
            $msg = "<span class='alert'>이미 존재하는 아이디 입니다.</span>";
        } else {
            if(validate_username($id) && strlen($id) > 2)
                $msg = "계정을 생성할 수 있습니다.";
            else
                $msg = "<span class='alert'>아이디를 정확히 입력해주세요.(3글자 이상)</span>";
        }
        wp_send_json(["data"=>$msg]);
    }
    wp_ajax("searchID");

    function loadPanel() {
        $out = [
            "result"=>false,
            "title"=>"",
            "content"=>""
        ];
        $map = [
            "patron"=>"후원하기"
        ];
        if(isset($_POST["name"])) {
            $name = $_POST["name"];
            $out["title"] = $map[$_POST["name"]] ?? "";
            $path = DIR_SRC."/popup/{$name}.php";
            if(file_exists($path)) {
                ob_start();
                include $path;
                $out["content"] = ob_get_contents();
                ob_get_clean();
            }
            $out["result"]=true;
            // $post = get_posts([
            //     "name"=>$name,
            //     "post_type"=>"page"
            // ]);
            // if(!empty($post)) {
            //     the_content($post);
            // }
        } else {
            // echo "no forms exists";
        }
        wp_send_json($out);
    }
    wp_ajax("loadPanel");
    
    // 메인페이 결제 준비
    function mainpay_payment_ready() {
        // 에러 출력 방지 (JSON 응답을 위해)
        error_reporting(0);
        ini_set('display_errors', 0);
        
        try {
            require(get_stylesheet_directory() . '/src/mainpay/config.php');
            
            $mbrRefNo = makeMbrRefNo($mbrNo);
            $timestamp = makeTimestamp();
            $amount = intval($_POST['amount'] ?? 0);
            $paymethod_raw = sanitize_text_field($_POST['paymethod'] ?? '');
            $paymethod = strtoupper($paymethod_raw);
            $program_id = intval($_POST['program_id'] ?? 0);
            
            // 상품명은 포스트 제목 사용
            $goodsName = '';
            if ($program_id > 0) {
                $program = get_post($program_id);
                if ($program) {
                    $goodsName = get_the_title($program_id);
                }
            }
            if (empty($goodsName)) {
                $goodsName = sanitize_text_field($_POST['goods_name'] ?? '프로그램 신청');
            }
            
            $buyerName = sanitize_text_field($_POST['buyer_name'] ?? '');
            $buyerEmail = sanitize_email($_POST['buyer_email'] ?? '');
            $buyerTel = sanitize_text_field($_POST['buyer_tel'] ?? '');
            
            if ($amount <= 0) {
                wp_send_json_error(array('message' => '결제 금액이 올바르지 않습니다.'));
                return;
            }
            
            // 수량 가져오기
            $quantity = intval($_POST['quantity'] ?? 1);
            if ($quantity < 1) {
                $quantity = 1;
            }
            
            // 프로그램 정보 확인 및 재고 확인
            if ($program_id > 0) {
                $program = get_post($program_id);
                if ($program && $program->post_type === 'post_program') {
                    $product_purchasable = get_field('product_purchasable', $program_id) ?? false;
                    $product_stock = intval(get_field('product_stock', $program_id) ?? 0);
                    $current_applicants = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
                    
                    // 판매 가능 여부 확인
                    if (!$product_purchasable) {
                        wp_send_json_error(array('message' => '현재 신청할 수 없는 상품입니다.'));
                        return;
                    }
                    
                    // 재고 확인 (0이면 제한 없음) - 수량 고려
                    if ($product_stock > 0 && ($current_applicants + $quantity) > $product_stock) {
                        wp_send_json_error(array('message' => '수강인원이 초과되어 마감되었습니다. (신청 가능: ' . max(0, $product_stock - $current_applicants) . '명)'));
                        return;
                    }
                }
            }
            
            // 메인페이 허용 paymethod 값: CARD|ACCT|VACCT|HPP|CULT
            $allowedPaymethods = array('CARD', 'ACCT', 'VACCT', 'HPP', 'CULT');
            if (empty($paymethod) || !in_array($paymethod, $allowedPaymethods, true)) {
                wp_send_json_error(array('message' => '지원하지 않는 결제수단입니다. (허용값: CARD, ACCT, VACCT, HPP, CULT)'));
                return;
            }
            
            $signature = makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp);
            
            $approvalUrl = home_url('/payment-approval/');
            $closeUrl = home_url('/payment-close/');
            $notiUrl = home_url('/payment-noti/');
            
            $parameters = array(
                'version' => 'V001',
                'mbrNo' => $mbrNo,
                'mbrRefNo' => $mbrRefNo,
                'amount' => $amount,
                'paymethod' => $paymethod,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'goodsName' => $goodsName,
                'buyerName' => $buyerName,
                'buyerEmail' => $buyerEmail,
                'buyerTel' => $buyerTel,
                'customerName' => $buyerName,
                'customerEmail' => $buyerEmail,
                'customerTelNo' => $buyerTel,
                'LGD_BUYER' => $buyerName,
                'approvalUrl' => $approvalUrl,
                'closeUrl' => $closeUrl,
                'notiUrl' => $notiUrl
            );
            
            $apiUrl = $API_BASE . "/v1/payment/ready";
            pintLog("PAY-API: " . $apiUrl, $logPath);
            pintLog("PARAM: " . print_r($parameters, TRUE), $logPath);
            
            $result = httpPost($apiUrl, $parameters);
            pintLog("RESPONSE: " . $result, $logPath);
            
            $obj = json_decode($result);
            
            if ($obj && isset($obj->resultCode) && $obj->resultCode == "200") {
                // 세션에 주문 정보 저장 (안전하게)
                if (session_status() === PHP_SESSION_NONE) {
                    $session_dir = sys_get_temp_dir() . '/sessions';
                    if (!is_dir($session_dir)) {
                        @mkdir($session_dir, 0755, true);
                    }
                    if (is_dir($session_dir) && is_writable($session_dir)) {
                        ini_set('session.save_path', $session_dir);
                    }
                    @session_start();
                }
            // product_id는 프로그램 ID 기반으로 생성
            $product_id = $program_id;
            
            $_SESSION['mainpay_order'] = array(
                'mbrRefNo' => $mbrRefNo,
                'refNo' => $obj->data->refNo ?? '',
                'amount' => $amount,
                'paymethod' => $paymethod,
                'goodsName' => $goodsName,
                'buyerName' => $buyerName,
                'buyerEmail' => $buyerEmail,
                'buyerTel' => $buyerTel,
                'program_id' => $program_id,
                'product_id' => $product_id,
                'quantity' => $quantity
            );
                
                // 메인페이 API 응답에서 결제 URL 가져오기
                // PC/모바일 구분하여 URL 선택
                $is_mobile = wp_is_mobile();
                if ($is_mobile && isset($obj->data->nextMobileUrl)) {
                    $payUrl = $obj->data->nextMobileUrl;
                } elseif (isset($obj->data->nextPcUrl)) {
                    $payUrl = $obj->data->nextPcUrl;
                } elseif (isset($obj->data->payUrl)) {
                    // 기존 payUrl 필드가 있는 경우 (하위 호환)
                    $payUrl = $obj->data->payUrl;
                } else {
                    // 모든 URL이 없는 경우 에러
                    wp_send_json_error(array('message' => '결제 URL을 받을 수 없습니다.'));
                    return;
                }
                
                wp_send_json_success(array(
                    'payUrl' => $payUrl,
                    'refNo' => $obj->data->refNo ?? '',
                    'mbrRefNo' => $mbrRefNo
                ));
            } else {
                $errorMessage = isset($obj->resultMessage) ? $obj->resultMessage : '결제 준비에 실패했습니다.';
                wp_send_json_error(array('message' => $errorMessage));
            }
        } catch (Exception $e) {
            pintLog("EXCEPTION: " . $e->getMessage(), $logPath);
            wp_send_json_error(array('message' => '서버 오류가 발생했습니다: ' . $e->getMessage()));
        }
    }
    wp_ajax("mainpay_payment_ready");
    
    // 메인페이 신용카드 수기(KEY-IN) 결제
    function mainpay_card_keyin() {
        // 에러 출력 방지 (JSON 응답을 위해)
        error_reporting(0);
        ini_set('display_errors', 0);
        
        try {
            require(get_stylesheet_directory() . '/src/mainpay/config.php');
            
            // 필수 파라미터 검증
            $mbrRefNo = makeMbrRefNo($mbrNo);
            $cardNo = sanitize_text_field($_POST['card_no'] ?? '');
            $expd = sanitize_text_field($_POST['expd'] ?? ''); // YYMM 형식
            $amount = intval($_POST['amount'] ?? 0);
            $installment = intval($_POST['installment'] ?? 0);
            $goodsName = sanitize_text_field($_POST['goods_name'] ?? '프로그램 신청');
            $keyinAuthType = sanitize_text_field($_POST['keyin_auth_type'] ?? 'K'); // K: 비인증, O: 구인증
            $authType = sanitize_text_field($_POST['auth_type'] ?? '0'); // 0: 생년월일, 1: 사업자번호
            $regNo = sanitize_text_field($_POST['reg_no'] ?? '');
            $passwd = sanitize_text_field($_POST['passwd'] ?? '');
            
            // 선택 파라미터
            $customerName = sanitize_text_field($_POST['customer_name'] ?? '');
            $customerTelNo = sanitize_text_field($_POST['customer_tel_no'] ?? '');
            $customerEmail = sanitize_email($_POST['customer_email'] ?? '');
            $retailerCode = sanitize_text_field($_POST['retailer_code'] ?? '');
            
            $program_id = intval($_POST['program_id'] ?? 0);
            $quantity = intval($_POST['quantity'] ?? 1);
            
            // 유효성 검증
            if (empty($cardNo) || strlen($cardNo) != 16) {
                wp_send_json_error(array('message' => '카드번호는 16자리여야 합니다.'));
                return;
            }
            
            if (empty($expd) || strlen($expd) != 4) {
                wp_send_json_error(array('message' => '카드 유효기간은 YYMM 형식(4자리)이어야 합니다.'));
                return;
            }
            
            if ($amount <= 0) {
                wp_send_json_error(array('message' => '결제 금액이 올바르지 않습니다.'));
                return;
            }
            
            if ($installment < 0 || $installment > 24) {
                wp_send_json_error(array('message' => '할부개월은 0~24 사이여야 합니다.'));
                return;
            }
            
            // 구인증인 경우 필수 파라미터 검증
            if ($keyinAuthType === 'O') {
                if (empty($authType) || empty($regNo) || empty($passwd)) {
                    wp_send_json_error(array('message' => '구인증 사용 시 인증타입, 아이디, 비밀번호는 필수입니다.'));
                    return;
                }
                if (strlen($passwd) != 2) {
                    wp_send_json_error(array('message' => '카드 비밀번호는 앞 2자리여야 합니다.'));
                    return;
                }
            }
            
            // 상품명 설정
            if ($program_id > 0) {
                $program = get_post($program_id);
                if ($program) {
                    $goodsName = get_the_title($program_id);
                }
            }
            
            // 프로그램 정보 확인 및 재고 확인
            if ($program_id > 0) {
                $program = get_post($program_id);
                if ($program && $program->post_type === 'post_program') {
                    $product_purchasable = get_field('product_purchasable', $program_id) ?? false;
                    $product_stock = intval(get_field('product_stock', $program_id) ?? 0);
                    $current_applicants = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
                    
                    if (!$product_purchasable) {
                        wp_send_json_error(array('message' => '현재 신청할 수 없는 상품입니다.'));
                        return;
                    }
                    
                    if ($product_stock > 0 && ($current_applicants + $quantity) > $product_stock) {
                        wp_send_json_error(array('message' => '수강인원이 초과되어 마감되었습니다. (신청 가능: ' . max(0, $product_stock - $current_applicants) . '명)'));
                        return;
                    }
                }
            }
            
            // KEY-IN 결제 API 호출
            $result = cardKeyinPayment(
                $mbrNo,
                $mbrRefNo,
                $cardNo,
                $expd,
                $amount,
                $installment,
                $goodsName,
                $keyinAuthType,
                $authType,
                $regNo,
                $passwd,
                $apiKey,
                $logPath,
                $RELAY_BASE,
                $customerName,
                $customerTelNo,
                $customerEmail,
                $retailerCode
            );
            
            if ($result && isset($result->resultCode) && $result->resultCode == "200") {
                // 결제 성공
                // 세션에 주문 정보 저장
                if (session_status() === PHP_SESSION_NONE) {
                    $session_dir = sys_get_temp_dir() . '/sessions';
                    if (!is_dir($session_dir)) {
                        @mkdir($session_dir, 0755, true);
                    }
                    if (is_dir($session_dir) && is_writable($session_dir)) {
                        ini_set('session.save_path', $session_dir);
                    }
                    @session_start();
                }
                
                $product_id = $program_id;
                
                $_SESSION['mainpay_order'] = array(
                    'mbrRefNo' => $mbrRefNo,
                    'refNo' => $result->data->refNo ?? '',
                    'amount' => $amount,
                    'paymethod' => 'CARD',
                    'goodsName' => $goodsName,
                    'buyerName' => $customerName,
                    'buyerEmail' => $customerEmail,
                    'buyerTel' => $customerTelNo,
                    'program_id' => $program_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity
                );
                
                $_SESSION['mainpay_payment_result'] = array(
                    'refNo' => $result->data->refNo ?? '',
                    'mbrRefNo' => $mbrRefNo,
                    'amount' => $amount,
                    'tranDate' => $result->data->tranDate ?? '',
                    'tranTime' => $result->data->tranTime ?? '',
                    'installment' => $result->data->installment ?? $installment,
                    'applNo' => $result->data->applNo ?? '',
                    'issueCompanyNo' => $result->data->issueCompanyNo ?? '',
                    'issueCompanyName' => $result->data->issueCompanyName ?? '',
                    'issueCardName' => $result->data->issueCardName ?? '',
                    'acqCompanyNo' => $result->data->acqCompanyNo ?? '',
                    'acqCompanyName' => $result->data->acqCompanyName ?? '',
                    'payType' => $result->data->payType ?? 'K',
                    'paymethod' => 'CARD',
                    'paymethodName' => '신용카드',
                    'status' => 'success'
                );
                
                // 주문 정보에 user_id 추가
                $orderInfo = array(
                    'mbrRefNo' => $mbrRefNo,
                    'amount' => $amount,
                    'paymethod' => 'CARD',
                    'goodsName' => $goodsName,
                    'buyerName' => $customerName,
                    'buyerEmail' => $customerEmail,
                    'buyerTel' => $customerTelNo,
                    'program_id' => $program_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'user_id' => get_current_user_id()
                );
                
                // 주문을 DB에 저장
                $order_post_id = create_order_post($orderInfo, $_SESSION['mainpay_payment_result']);
                if ($order_post_id) {
                    $_SESSION['mainpay_order_post_id'] = $order_post_id;
                }
                
                // 신청자 수 증가
                if ($program_id > 0 && $quantity > 0) {
                    $current_count = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
                    update_post_meta($program_id, 'program_applicants_count', $current_count + $quantity);
                }
                
                wp_send_json_success(array(
                    'message' => '결제가 완료되었습니다.',
                    'refNo' => $result->data->refNo ?? '',
                    'mbrRefNo' => $mbrRefNo,
                    'tranDate' => $result->data->tranDate ?? '',
                    'tranTime' => $result->data->tranTime ?? '',
                    'applNo' => $result->data->applNo ?? '',
                    'amount' => $amount,
                    'redirect_url' => home_url('/payment-detail?order_id=' . $mbrRefNo . '&status=success')
                ));
            } else {
                $errorMessage = isset($result->resultMessage) ? $result->resultMessage : '결제 승인에 실패했습니다.';
                wp_send_json_error(array('message' => $errorMessage));
            }
        } catch (Exception $e) {
            pintLog("KEYIN-EXCEPTION: " . $e->getMessage(), $logPath);
            wp_send_json_error(array('message' => '서버 오류가 발생했습니다: ' . $e->getMessage()));
        }
    }
    wp_ajax("mainpay_card_keyin");
    
    // 메인페이 결제 취소
    function mainpay_payment_cancel() {
        require(get_stylesheet_directory() . '/src/mainpay/config.php');
        
        $refNo = sanitize_text_field($_POST['ref_no'] ?? '');
        $mbrRefNo = sanitize_text_field($_POST['mbr_ref_no'] ?? '');
        $amount = intval($_POST['amount'] ?? 0);
        $cancelReason = sanitize_text_field($_POST['cancel_reason'] ?? '고객 요청');
        
        if (empty($refNo) || empty($mbrRefNo) || $amount <= 0) {
            wp_send_json_error(array('message' => '필수 정보가 누락되었습니다.'));
            return;
        }
        
        $timestamp = makeTimestamp();
        $signature = makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp);
        
        // orgTranDate 포맷팅 (YYYYMMDD... -> YYMMDD)
        $tranDate = get_field('order_tran_date', $order_id); 
        $orgTranDate = !empty($tranDate) ? substr($tranDate, 2, 6) : '';
        $paymethod = get_field('order_paymethod', $order_id);

        $parameters = array(
            'mbrNo' => $mbrNo,
            'orgRefNo' => $refNo,
            'orgTranDate' => $orgTranDate,
            'paymethod' => $paymethod,
            'mbrRefNo' => $mbrRefNo,
            'cancelAmount' => $amount,
            'cancelReason' => $cancelReason,
            'timestamp' => $timestamp,
            'signature' => $signature
        );
        
        $apiUrl = $API_BASE . "/v1/payment/cancel";
        pintLog("CANCEL-API: " . $apiUrl, $logPath);
        pintLog("PARAM: " . print_r($parameters, TRUE), $logPath);
        
        $result = httpPost($apiUrl, $parameters);
        pintLog("RESPONSE: " . $result, $logPath);
        
        $obj = json_decode($result);
        
        if ($obj && isset($obj->resultCode) && $obj->resultCode == "200") {
            // 세션 업데이트 (결제 상태를 취소로 변경)
            if (!session_id()) {
                session_start();
            }
            if (isset($_SESSION['mainpay_payment_result'])) {
                $_SESSION['mainpay_payment_result']['status'] = 'cancelled';
                $_SESSION['mainpay_payment_result']['cancelDate'] = date('YmdHis');
            }
            
            wp_send_json_success(array('message' => '결제가 취소되었습니다.'));
        } else {
            $errorMessage = isset($obj->resultMessage) ? $obj->resultMessage : '취소 처리에 실패했습니다.';
            wp_send_json_error(array('message' => $errorMessage));
        }
    }
    wp_ajax("mainpay_payment_cancel", true); // 로그인 사용자만
    
    // 무료 주문 처리 (결제 프로세스 없이 바로 등록)
    function mainpay_free_order() {
        // #region agent log
        $log_file = get_stylesheet_directory() . '/.cursor/debug.log';
        $log_entry = json_encode(array(
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'A',
            'location' => 'ajax.php:332',
            'message' => 'mainpay_free_order 함수 진입',
            'data' => array('user_logged_in' => is_user_logged_in()),
            'timestamp' => time() * 1000
        )) . "\n";
        @file_put_contents($log_file, $log_entry, FILE_APPEND);
        // #endregion
        
        // 에러 출력 방지 (JSON 응답을 위해)
        error_reporting(0);
        ini_set('display_errors', 0);
        
        try {
            // 로그인 체크
            if (!is_user_logged_in()) {
                wp_send_json_error(array('message' => '로그인이 필요합니다.'));
                return;
            }
            
            $program_id = intval($_POST['program_id'] ?? 0);
            $quantity = intval($_POST['quantity'] ?? 1);
            $buyerName = sanitize_text_field($_POST['buyer_name'] ?? '');
            $buyerEmail = sanitize_email($_POST['buyer_email'] ?? '');
            $buyerTel = sanitize_text_field($_POST['buyer_tel'] ?? '');
            
            // #region agent log
            $log_entry = json_encode(array(
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'B',
                'location' => 'ajax.php:349',
                'message' => '주문 파라미터 수신',
                'data' => array(
                    'program_id' => $program_id,
                    'quantity' => $quantity,
                    'buyerName' => $buyerName,
                    'buyerEmail' => $buyerEmail,
                    'buyerTel' => $buyerTel
                ),
                'timestamp' => time() * 1000
            )) . "\n";
            @file_put_contents($log_file, $log_entry, FILE_APPEND);
            // #endregion
            
            if ($program_id <= 0) {
                wp_send_json_error(array('message' => '프로그램 정보가 올바르지 않습니다.'));
                return;
            }
            
            if ($quantity < 1) {
                $quantity = 1;
            }
            
            // 프로그램 정보 확인 및 재고 확인
            $program = get_post($program_id);
            if (!$program || $program->post_type !== 'post_program') {
                wp_send_json_error(array('message' => '프로그램을 찾을 수 없습니다.'));
                return;
            }
            
            $product_purchasable = get_field('product_purchasable', $program_id) ?? false;
            $product_stock = intval(get_field('product_stock', $program_id) ?? 0);
            $current_applicants = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
            
            // 판매 가능 여부 확인
            if (!$product_purchasable) {
                wp_send_json_error(array('message' => '현재 신청할 수 없는 상품입니다.'));
                return;
            }
            
            // 재고 확인 (0이면 제한 없음) - 수량 고려
            if ($product_stock > 0 && ($current_applicants + $quantity) > $product_stock) {
                wp_send_json_error(array('message' => '수강인원이 초과되어 마감되었습니다. (신청 가능: ' . max(0, $product_stock - $current_applicants) . '명)'));
                return;
            }
            
            // 신청자 수 증가
            update_post_meta($program_id, 'program_applicants_count', $current_applicants + $quantity);
            
            // 세션에 주문 정보 저장 (무료 주문) (안전하게)
            if (session_status() === PHP_SESSION_NONE) {
                $session_dir = sys_get_temp_dir() . '/sessions';
                if (!is_dir($session_dir)) {
                    @mkdir($session_dir, 0755, true);
                }
                if (is_dir($session_dir) && is_writable($session_dir)) {
                    ini_set('session.save_path', $session_dir);
                }
                @session_start();
            }
            
            $mbrRefNo = 'FREE-' . $program_id . '-' . time();
            
            $_SESSION['mainpay_order'] = array(
                'mbrRefNo' => $mbrRefNo,
                'refNo' => '',
                'amount' => 0,
                'paymethod' => 'FREE',
                'goodsName' => get_the_title($program_id),
                'buyerName' => $buyerName,
                'buyerEmail' => $buyerEmail,
                'buyerTel' => $buyerTel,
                'program_id' => $program_id,
                'product_id' => $program_id,
                'quantity' => $quantity
            );
            
            $_SESSION['mainpay_payment_result'] = array(
                'refNo' => '',
                'mbrRefNo' => $mbrRefNo,
                'amount' => 0,
                'tranDate' => date('YmdHis'),
                'cardNo' => '',
                'cardCode' => '',
                'paymethod' => 'FREE',
                'paymethodName' => '무료',
                'installment' => 0,
                'status' => 'success'
            );
            
            // 주문 정보에 user_id 추가
            $_SESSION['mainpay_order']['user_id'] = get_current_user_id();
            
            // #region agent log
            $log_entry = json_encode(array(
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'C',
                'location' => 'ajax.php:427',
                'message' => 'DB 저장 전 주문 정보',
                'data' => array(
                    'mbrRefNo' => $mbrRefNo,
                    'order_info' => $_SESSION['mainpay_order'],
                    'payment_result' => $_SESSION['mainpay_payment_result']
                ),
                'timestamp' => time() * 1000
            )) . "\n";
            @file_put_contents($log_file, $log_entry, FILE_APPEND);
            // #endregion
            
            // 주문을 DB에 저장
            $order_post_id = create_order_post($_SESSION['mainpay_order'], $_SESSION['mainpay_payment_result']);
            
            // #region agent log
            $log_entry = json_encode(array(
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'D',
                'location' => 'ajax.php:431',
                'message' => 'DB 저장 결과',
                'data' => array(
                    'order_post_id' => $order_post_id,
                    'is_wp_error' => is_wp_error($order_post_id),
                    'success' => ($order_post_id && !is_wp_error($order_post_id))
                ),
                'timestamp' => time() * 1000
            )) . "\n";
            @file_put_contents($log_file, $log_entry, FILE_APPEND);
            // #endregion
            
            if ($order_post_id) {
                $_SESSION['mainpay_order_post_id'] = $order_post_id;
            }
            
            wp_send_json_success(array(
                'message' => '신청이 완료되었습니다.',
                'mbrRefNo' => $mbrRefNo
            ));
        } catch (Exception $e) {
            wp_send_json_error(array('message' => '서버 오류가 발생했습니다: ' . $e->getMessage()));
        }
    }
    wp_ajax("mainpay_free_order", true); // 로그인 사용자만
    
    // 주문을 Custom Post Type으로 저장하는 함수
    function create_order_post($orderInfo, $payment_result) {
        // #region agent log
        $log_file = get_stylesheet_directory() . '/.cursor/debug.log';
        $log_entry = json_encode(array(
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'E',
            'location' => 'ajax.php:446',
            'message' => 'create_order_post 함수 진입',
            'data' => array('mbrRefNo' => $orderInfo['mbrRefNo'] ?? ''),
            'timestamp' => time() * 1000
        )) . "\n";
        @file_put_contents($log_file, $log_entry, FILE_APPEND);
        // #endregion
        
        // 중복 저장 방지: 동일한 mbrRefNo로 이미 주문이 있는지 확인
        $existing_order = get_posts(array(
            'post_type' => 'post_order',
            'meta_query' => array(
                array(
                    'key' => 'order_mbr_ref_no',
                    'value' => $orderInfo['mbrRefNo'] ?? '',
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1,
            'post_status' => 'any'
        ));
        
        // #region agent log
        $log_entry = json_encode(array(
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'F',
            'location' => 'ajax.php:461',
            'message' => '중복 주문 확인 결과',
            'data' => array(
                'existing_order_count' => count($existing_order),
                'existing_order_id' => !empty($existing_order) ? $existing_order[0]->ID : null
            ),
            'timestamp' => time() * 1000
        )) . "\n";
        @file_put_contents($log_file, $log_entry, FILE_APPEND);
        // #endregion
        
        if (!empty($existing_order)) {
            // 이미 존재하는 주문이면 업데이트
            $order_post_id = $existing_order[0]->ID;
        } else {
            // 새 주문 생성
            $post_title = ($orderInfo['mbrRefNo'] ?? '') . ' - ' . ($orderInfo['goodsName'] ?? '주문');
            $user_id = $orderInfo['user_id'] ?? get_current_user_id();
            
            // #region agent log
            $log_entry = json_encode(array(
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'G',
                'location' => 'ajax.php:469',
                'message' => '새 주문 생성 시도',
                'data' => array(
                    'post_title' => $post_title,
                    'user_id' => $user_id,
                    'post_type' => 'post_order'
                ),
                'timestamp' => time() * 1000
            )) . "\n";
            @file_put_contents($log_file, $log_entry, FILE_APPEND);
            // #endregion
            
            $order_post_id = wp_insert_post(array(
                'post_title' => $post_title,
                'post_type' => 'post_order',
                'post_status' => 'publish',
                'post_author' => $user_id > 0 ? $user_id : 1,
            ));
        }
        
        // #region agent log
        $log_entry = json_encode(array(
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'H',
            'location' => 'ajax.php:477',
            'message' => 'wp_insert_post 결과',
            'data' => array(
                'order_post_id' => $order_post_id,
                'is_wp_error' => is_wp_error($order_post_id),
                'error_message' => is_wp_error($order_post_id) ? $order_post_id->get_error_message() : null
            ),
            'timestamp' => time() * 1000
        )) . "\n";
        @file_put_contents($log_file, $log_entry, FILE_APPEND);
        // #endregion
        
        if (is_wp_error($order_post_id) || $order_post_id === 0) {
            return false;
        }
        
        // 주문 기본 정보 저장
        update_field('order_mbr_ref_no', $orderInfo['mbrRefNo'] ?? '', $order_post_id);
        update_field('order_ref_no', $payment_result['refNo'] ?? $orderInfo['refNo'] ?? '', $order_post_id);
        update_field('order_status', $payment_result['status'] ?? 'success', $order_post_id);
        update_field('order_amount', $orderInfo['amount'] ?? $payment_result['amount'] ?? 0, $order_post_id);
        update_field('order_quantity', $orderInfo['quantity'] ?? 1, $order_post_id);
        
        // 결제 정보 저장
        update_field('order_paymethod', $orderInfo['paymethod'] ?? $payment_result['paymethod'] ?? 'CARD', $order_post_id);
        update_field('order_paymethod_name', $payment_result['paymethodName'] ?? '', $order_post_id);
        update_field('order_tran_date', $payment_result['tranDate'] ?? date('YmdHis'), $order_post_id);
        update_field('order_card_no', $payment_result['cardNo'] ?? '', $order_post_id);
        update_field('order_card_code', $payment_result['cardCode'] ?? '', $order_post_id);
        update_field('order_installment', $payment_result['installment'] ?? 0, $order_post_id);
        
        // 가상계좌 정보 저장
        if (($payment_result['paymethod'] ?? '') === 'VACCT') {
            update_field('order_bank_code', $payment_result['bankCode'] ?? '', $order_post_id);
            update_field('order_account_no', $payment_result['accountNo'] ?? '', $order_post_id);
            update_field('order_account_close_date', $payment_result['accountCloseDate'] ?? '', $order_post_id);
        }
        
        // 주문자 정보 저장
        update_field('order_buyer_name', $orderInfo['buyerName'] ?? '', $order_post_id);
        update_field('order_buyer_email', $orderInfo['buyerEmail'] ?? '', $order_post_id);
        update_field('order_buyer_tel', $orderInfo['buyerTel'] ?? '', $order_post_id);
        
        // 상품 정보 저장
        $program_id = $orderInfo['program_id'] ?? 0;
        update_field('order_program_id', $program_id, $order_post_id);
        update_field('order_product_id', $orderInfo['product_id'] ?? $program_id, $order_post_id);
        update_field('order_goods_name', $orderInfo['goodsName'] ?? '', $order_post_id);
        update_field('order_user_id', $orderInfo['user_id'] ?? get_current_user_id(), $order_post_id);
        
        // #region agent log
        $log_entry = json_encode(array(
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'I',
            'location' => 'ajax.php:508',
            'message' => 'ACF 필드 저장 완료',
            'data' => array(
                'order_post_id' => $order_post_id,
                'program_id' => $program_id,
                'order_status' => get_field('order_status', $order_post_id),
                'order_amount' => get_field('order_amount', $order_post_id)
            ),
            'timestamp' => time() * 1000
        )) . "\n";
        @file_put_contents($log_file, $log_entry, FILE_APPEND);
        // #endregion
        
        return $order_post_id;
    }
    
    // 관리자용 주문 취소 처리
    function admin_cancel_order() {
        // 권한 체크
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => '권한이 없습니다.'));
            return;
        }
        
        $order_id = intval($_POST['order_id'] ?? 0);
        $mbr_ref_no = sanitize_text_field($_POST['mbr_ref_no'] ?? '');
        $ref_no = sanitize_text_field($_POST['ref_no'] ?? '');
        $amount = intval($_POST['amount'] ?? 0);
        $program_id = intval($_POST['program_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($order_id <= 0) {
            wp_send_json_error(array('message' => '주문 정보가 올바르지 않습니다.'));
            return;
        }
        
        // 주문 상태 확인
        $order_status = get_field('order_status', $order_id);
        if ($order_status !== 'success') {
            wp_send_json_error(array('message' => '이미 취소되거나 환불된 주문입니다.'));
            return;
        }
        
        // 무료 주문은 Mainpay API 호출 없이 바로 취소 처리
        if ($amount === 0 || empty($ref_no)) {
            // 주문 상태 업데이트
            update_field('order_status', 'cancelled', $order_id);
            
            // 프로그램 신청자 수 감소
            if ($program_id > 0 && $quantity > 0) {
                $current_count = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
                $new_count = max(0, $current_count - $quantity);
                update_post_meta($program_id, 'program_applicants_count', $new_count);
            }
            
            wp_send_json_success(array('message' => '신청이 취소되었습니다.'));
            return;
        }
        
        // Mainpay 취소 API 호출
        require(get_stylesheet_directory() . '/src/mainpay/config.php');
        
        $timestamp = makeTimestamp();
        $signature = makeSignature($mbrNo, $mbr_ref_no, $amount, $apiKey, $timestamp);
        
        // 결제 정보 가져오기
        $paymethod = get_field('order_paymethod', $order_id);
        $tran_date = get_field('order_tran_date', $order_id);
        $tran_date_clean = preg_replace('/[^0-9]/', '', $tran_date);
        
        // orgTranDate 포맷팅 (YYYYMMDD... -> YYMMDD) (없으면 오늘 날짜 사용 - 테스트용)
        if (!empty($tran_date_clean)) {
            $org_tran_date = substr($tran_date_clean, 2, 6);
        } else {
            // 만약 날짜가 없으면 주문 날짜나 오늘 날짜 사용 (테스트 환경 대응)
            $org_tran_date = date('ymd');
            // pintLog("WARNING: order_tran_date is empty for order $order_id. Using today: $org_tran_date", $logPath); // Assuming pintLog is defined elsewhere or commented out if not needed
        }
        
        $parameters = array(
            'mbrNo' => $mbrNo,
            'orgRefNo' => $ref_no,
            'orgTranDate' => $org_tran_date,
            'paymethod' => $paymethod,
            'mbrRefNo' => $mbr_ref_no,
            'cancelAmount' => $amount,
            'cancelReason' => '관리자 취소',
            'timestamp' => $timestamp,
            'signature' => $signature
        );
        
        $apiUrl = $API_BASE . "/v1/payment/cancel";
        $result = httpPost($apiUrl, $parameters);
        $obj = json_decode($result);
        
        if ($obj && isset($obj->resultCode) && $obj->resultCode == "200") {
            // 주문 상태 업데이트
            update_field('order_status', 'cancelled', $order_id);
            
            // 프로그램 신청자 수 감소
            if ($program_id > 0 && $quantity > 0) {
                $current_count = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
                $new_count = max(0, $current_count - $quantity);
                update_post_meta($program_id, 'program_applicants_count', $new_count);
            }
            
            wp_send_json_success(array('message' => '신청이 취소되었습니다.'));
        } else {
            $errorMessage = isset($obj->resultMessage) ? $obj->resultMessage : '취소 처리에 실패했습니다.';
            wp_send_json_error(array('message' => $errorMessage));
        }
    }
    wp_ajax("admin_cancel_order", true); // 관리자만
    
    // 관리자용 주문 환불 처리
    function admin_refund_order() {
        // 권한 체크
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => '권한이 없습니다.'));
            return;
        }
        
        $order_id = intval($_POST['order_id'] ?? 0);
        $mbr_ref_no = sanitize_text_field($_POST['mbr_ref_no'] ?? '');
        $ref_no = sanitize_text_field($_POST['ref_no'] ?? '');
        $amount = intval($_POST['amount'] ?? 0);
        $program_id = intval($_POST['program_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($order_id <= 0) {
            wp_send_json_error(array('message' => '주문 정보가 올바르지 않습니다.'));
            return;
        }
        
        // 무료 주문은 환불 불가
        if ($amount === 0) {
            wp_send_json_error(array('message' => '무료 주문은 환불할 수 없습니다. 신청 취소를 사용해주세요.'));
            return;
        }
        
        // 주문 상태 확인
        $order_status = get_field('order_status', $order_id);
        if ($order_status !== 'success') {
            wp_send_json_error(array('message' => '이미 취소되거나 환불된 주문입니다.'));
            return;
        }
        
        // Mainpay 취소 API 호출
        require(get_stylesheet_directory() . '/src/mainpay/config.php');
        
        $timestamp = makeTimestamp();
        $signature = makeSignature($mbrNo, $mbr_ref_no, $amount, $apiKey, $timestamp);
        
        // 결제 정보 가져오기
        $paymethod = get_field('order_paymethod', $order_id);
        $tran_date = get_field('order_tran_date', $order_id);
        $org_tran_date = !empty($tran_date) ? substr($tran_date, 2, 6) : '';

        $parameters = array(
            'mbrNo' => $mbrNo,
            'orgRefNo' => $ref_no,
            'orgTranDate' => $org_tran_date,
            'paymethod' => $paymethod,
            'mbrRefNo' => $mbr_ref_no,
            'cancelAmount' => $amount,
            'cancelReason' => '관리자 환불',
            'timestamp' => $timestamp,
            'signature' => $signature
        );
        
        $apiUrl = $API_BASE . "/v1/payment/cancel";
        $result = httpPost($apiUrl, $parameters);
        $obj = json_decode($result);
        
        if ($obj && isset($obj->resultCode) && $obj->resultCode == "200") {
            // 주문 상태 업데이트
            update_field('order_status', 'refunded', $order_id);
            
            // 프로그램 신청자 수 감소
            if ($program_id > 0 && $quantity > 0) {
                $current_count = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
                $new_count = max(0, $current_count - $quantity);
                update_post_meta($program_id, 'program_applicants_count', $new_count);
            }
            
            wp_send_json_success(array('message' => '환불이 처리되었습니다.'));
        } else {
            $errorMessage = isset($obj->resultMessage) ? $obj->resultMessage : '환불 처리에 실패했습니다.';
            wp_send_json_error(array('message' => $errorMessage));
        }
    }
    wp_ajax("admin_refund_order", true); // 관리자만
    
    // 환불 신청 처리
    function mainpay_refund_request() {
        // 로그인 체크
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => '로그인이 필요합니다.'));
            return;
        }
        
        require_once(get_stylesheet_directory() . '/src/php/refund_calculator.php');
        
        $order_id = sanitize_text_field($_POST['order_id'] ?? '');
        $refund_reason = sanitize_text_field($_POST['refund_reason'] ?? '');
        
        if (empty($order_id)) {
            wp_send_json_error(array('message' => '주문 정보가 올바르지 않습니다.'));
            return;
        }
        
        // 주문 정보 가져오기
        $order_posts = get_posts(array(
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
        
        if (empty($order_posts)) {
            wp_send_json_error(array('message' => '주문을 찾을 수 없습니다.'));
            return;
        }
        
        $order_post_id = $order_posts[0]->ID;
        
        // 주문자 확인 (본인 주문만 환불 신청 가능)
        $order_user_id = get_field('order_user_id', $order_post_id);
        if ($order_user_id != get_current_user_id()) {
            wp_send_json_error(array('message' => '본인의 주문만 환불 신청할 수 있습니다.'));
            return;
        }
        
        // 주문 상태 확인
        $order_status = get_field('order_status', $order_post_id);
        if ($order_status !== 'success') {
            wp_send_json_error(array('message' => '환불 신청 가능한 상태가 아닙니다.'));
            return;
        }
        
        // 프로그램 정보 가져오기
        $program_id = get_field('order_program_id', $order_post_id);
        $order_amount = intval(get_field('order_amount', $order_post_id) ?: 0);
        $tran_date = get_field('order_tran_date', $order_post_id);
        
        if ($program_id <= 0) {
            wp_send_json_error(array('message' => '프로그램 정보를 찾을 수 없습니다.'));
            return;
        }
        
        // 환불 금액 계산
        $refund_calc = calculate_refund($program_id, $order_amount, $tran_date);
        
        if (!$refund_calc['can_refund']) {
            // wp_send_json_error(array(
            //     'message' => $refund_calc['reason'],
            //     'is_online_refund' => false
            // ));
            // return;
            // 수동 상담 신청 허용을 위해 에러 리턴 주석 처리
        }
        
        // 환불 신청 정보 저장
        $refund_request_date = date('YmdHis');
        update_field('order_status', 'refund_requested', $order_post_id);
        update_field('order_refund_request_amount', $refund_calc['refund_amount'], $order_post_id);
        update_field('order_refund_request_reason', $refund_reason, $order_post_id);
        update_field('order_refund_request_date', $refund_request_date, $order_post_id);
        
        wp_send_json_success(array(
            'message' => '환불 신청이 완료되었습니다.',
            'refund_amount' => $refund_calc['refund_amount'],
            'refund_rate' => $refund_calc['refund_rate'],
            'reason' => $refund_calc['reason']
        ));
    }
    wp_ajax("mainpay_refund_request", true); // 로그인 사용자만
    
    // 환불 가능 여부 및 금액 조회
    function get_refund_info() {
        // 로그인 체크
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => '로그인이 필요합니다.'));
            return;
        }
        
        require_once(get_stylesheet_directory() . '/src/php/refund_calculator.php');
        
        $order_id = sanitize_text_field($_POST['order_id'] ?? '');
        
        if (empty($order_id)) {
            wp_send_json_error(array('message' => '주문 정보가 올바르지 않습니다.'));
            return;
        }
        
        // 주문 정보 가져오기
        $order_posts = get_posts(array(
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
        
        if (empty($order_posts)) {
            wp_send_json_error(array('message' => '주문을 찾을 수 없습니다.'));
            return;
        }
        
        $order_post_id = $order_posts[0]->ID;
        
        // 주문자 확인
        $order_user_id = get_field('order_user_id', $order_post_id);
        if ($order_user_id != get_current_user_id()) {
            wp_send_json_error(array('message' => '본인의 주문만 조회할 수 있습니다.'));
            return;
        }
        
        // 주문 상태 확인
        $order_status = get_field('order_status', $order_post_id);
        if ($order_status !== 'success') {
            // 디버깅: 현재 주문 상태를 포함한 에러 메시지
            $status_label = $order_status ?: '없음';
            wp_send_json_error(array(
                'message' => '환불 신청 가능한 상태가 아닙니다. (현재 상태: ' . $status_label . ')',
                'current_status' => $order_status
            ));
            return;
        }
        
        // 프로그램 정보 가져오기
        $program_id = get_field('order_program_id', $order_post_id);
        $order_amount = intval(get_field('order_amount', $order_post_id) ?: 0);
        $tran_date = get_field('order_tran_date', $order_post_id);
        
        if ($program_id <= 0) {
            wp_send_json_error(array('message' => '프로그램 정보를 찾을 수 없습니다.'));
            return;
        }
        
        // 환불 금액 계산
        $refund_calc = calculate_refund($program_id, $order_amount, $tran_date);
        
        wp_send_json_success($refund_calc);
    }
    wp_ajax("get_refund_info", true); // 로그인 사용자만
    
    // 관리자 환불 처리 (부분 환불 지원)
    function admin_process_refund() {
        // 권한 체크
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => '권한이 없습니다.'));
            return;
        }
        
        $order_id = intval($_POST['order_id'] ?? 0);
        $mbr_ref_no = sanitize_text_field($_POST['mbr_ref_no'] ?? '');
        $ref_no = sanitize_text_field($_POST['ref_no'] ?? '');
        $refund_amount = intval($_POST['amount'] ?? 0);
        $program_id = intval($_POST['program_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($order_id <= 0) {
            wp_send_json_error(array('message' => '주문 정보가 올바르지 않습니다.'));
            return;
        }
        
        if ($refund_amount <= 0) {
            wp_send_json_error(array('message' => '환불 금액이 올바르지 않습니다.'));
            return;
        }
        
        // 주문 상태 확인
        $order_status = get_field('order_status', $order_id);
        if ($order_status !== 'refund_requested') {
            wp_send_json_error(array('message' => '환불 신청 상태가 아닙니다.'));
            return;
        }
        
        // 원래 결제 금액 확인
        $original_amount = intval(get_field('order_amount', $order_id) ?: 0);
        if ($refund_amount > $original_amount) {
            wp_send_json_error(array('message' => '환불 금액이 결제 금액을 초과할 수 없습니다.'));
            return;
        }
        
        // 무료 주문은 환불 불가
        if ($original_amount === 0 || empty($ref_no)) {
            // 무료 주문은 상태만 변경
            update_field('order_status', 'refunded', $order_id);
            
            // 프로그램 신청자 수 감소
            if ($program_id > 0 && $quantity > 0) {
                $current_count = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
                $new_count = max(0, $current_count - $quantity);
                update_post_meta($program_id, 'program_applicants_count', $new_count);
            }
            
            wp_send_json_success(array('message' => '환불이 처리되었습니다.'));
            return;
        }
        
        // Mainpay 부분 환불 API 호출
        require(get_stylesheet_directory() . '/src/mainpay/config.php');
        
        // 결제 정보 가져오기
        $paymethod = get_field('order_paymethod', $order_id);
        $tran_date = get_field('order_tran_date', $order_id);
        $tran_date_clean = preg_replace('/[^0-9]/', '', $tran_date);
        $org_tran_date = !empty($tran_date_clean) ? substr($tran_date_clean, 2, 6) : '';
        $ref_no = get_field('order_ref_no', $order_id);
        $mbr_ref_no = get_field('order_mbr_ref_no', $order_id);
        
        // 새로운 취소용 mbrRefNo 생성 (원래 번호 재사용 시 중복 오류 가능성 있음)
        $cancel_mbr_ref_no = 'REF' . time() . $mbr_ref_no;
        $timestamp = makeTimestamp();
        $signature = makeSignature($mbrNo, $cancel_mbr_ref_no, $refund_amount, $apiKey, $timestamp);

        $parameters = array(
            'mbrNo' => $mbrNo,
            'orgRefNo' => $ref_no,
            'orgTranDate' => $org_tran_date,
            'paymethod' => $paymethod,
            'mbrRefNo' => $cancel_mbr_ref_no,
            'cancelAmount' => $refund_amount,
            'cancelReason' => '관리자 환불 처리',
            'timestamp' => $timestamp,
            'signature' => $signature
        );
        
        $apiUrl = $API_BASE . "/v1/payment/cancel";
        $result = httpPost($apiUrl, $parameters);
        $obj = json_decode($result);
        
        if ($obj && isset($obj->resultCode) && $obj->resultCode == "200") {
            // 주문 상태 업데이트
            update_field('order_status', 'refunded', $order_id);
            
            // 프로그램 신청자 수 감소
            if ($program_id > 0 && $quantity > 0) {
                $current_count = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
                $new_count = max(0, $current_count - $quantity);
                update_post_meta($program_id, 'program_applicants_count', $new_count);
            }
            
            wp_send_json_success(array('message' => '환불이 처리되었습니다.'));
        } else {
            $errorMessage = isset($obj->resultMessage) ? $obj->resultMessage : '환불 처리에 실패했습니다.';
            wp_send_json_error(array('message' => $errorMessage));
        }
    }
    wp_ajax("admin_process_refund", true); // 관리자만
    
    // 관리자 환불 신청 취소
    function admin_cancel_refund_request() {
        // 권한 체크
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => '권한이 없습니다.'));
            return;
        }
        
        $order_id = intval($_POST['order_id'] ?? 0);
        
        if ($order_id <= 0) {
            wp_send_json_error(array('message' => '주문 정보가 올바르지 않습니다.'));
            return;
        }
        
        // 주문 상태 확인
        $order_status = get_field('order_status', $order_id);
        if ($order_status !== 'refund_requested') {
            wp_send_json_error(array('message' => '환불 신청 상태가 아닙니다.'));
            return;
        }
        
        // 주문 상태를 "결제 완료"로 변경
        update_field('order_status', 'success', $order_id);
        
        // 환불 신청 정보 초기화 (선택사항)
        update_field('order_refund_request_amount', '', $order_id);
        update_field('order_refund_request_reason', '', $order_id);
        update_field('order_refund_request_date', '', $order_id);
        
        wp_send_json_success(array('message' => '환불 신청이 취소되었습니다.'));
    }
    wp_ajax("admin_cancel_refund_request", true); // 관리자만
    
    // 무료 주문 취소 (사용자용)
    function cancel_free_order() {
        // 로그인 체크
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => '로그인이 필요합니다.'));
            return;
        }
        
        $order_id = sanitize_text_field($_POST['order_id'] ?? '');
        
        if (empty($order_id)) {
            wp_send_json_error(array('message' => '주문 정보가 올바르지 않습니다.'));
            return;
        }
        
        // 주문 정보 가져오기
        $order_posts = get_posts(array(
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
        
        if (empty($order_posts)) {
            wp_send_json_error(array('message' => '주문을 찾을 수 없습니다.'));
            return;
        }
        
        $order_post_id = $order_posts[0]->ID;
        
        // 주문자 확인 (본인 주문만 취소 가능)
        $order_user_id = get_field('order_user_id', $order_post_id);
        if ($order_user_id != get_current_user_id()) {
            wp_send_json_error(array('message' => '본인의 주문만 취소할 수 있습니다.'));
            return;
        }
        
        // 주문 상태 확인
        $order_status = get_field('order_status', $order_post_id);
        if ($order_status !== 'success') {
            wp_send_json_error(array('message' => '취소 가능한 상태가 아닙니다.'));
            return;
        }
        
        // 무료 주문 확인
        $order_amount = intval(get_field('order_amount', $order_post_id) ?: 0);
        $paymethod = get_field('order_paymethod', $order_post_id);
        
        if ($order_amount > 0 && $paymethod !== 'FREE') {
            wp_send_json_error(array('message' => '유료 주문은 환불신청을 사용해주세요.'));
            return;
        }
        
        // 프로그램 정보 가져오기
        $program_id = get_field('order_program_id', $order_post_id);
        $quantity = intval(get_field('order_quantity', $order_post_id) ?: 1);
        
        // 주문 상태 업데이트
        update_field('order_status', 'cancelled', $order_post_id);
        
        // 프로그램 신청자 수 감소
        if ($program_id > 0 && $quantity > 0) {
            $current_count = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);
            $new_count = max(0, $current_count - $quantity);
            update_post_meta($program_id, 'program_applicants_count', $new_count);
        }
        
        wp_send_json_success(array('message' => '신청이 취소되었습니다.'));
    }
    wp_ajax("cancel_free_order", true); // 로그인 사용자만
    
    // 관리자용 이월 처리
    function admin_carryover_order() {
        // 권한 체크
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => '권한이 없습니다.'));
            return;
        }
        
        $order_id = intval($_POST['order_id'] ?? 0);
        $target_program_id = intval($_POST['target_program_id'] ?? 0);
        $reason = sanitize_text_field($_POST['reason'] ?? '부득이한 사정으로 인한 이월');
        
        if ($order_id <= 0 || $target_program_id <= 0) {
            wp_send_json_error(array('message' => '주문 정보 또는 대상 프로그램 정보가 올바르지 않습니다.'));
            return;
        }
        
        // 기존 주문 정보 가져오기
        $original_order_status = get_field('order_status', $order_id);
        if ($original_order_status !== 'success') {
            wp_send_json_error(array('message' => '결제 완료 상태의 주문만 이월 가능합니다.'));
            return;
        }
        
        $original_program_id = get_field('order_program_id', $order_id);
        $original_quantity = intval(get_field('order_quantity', $order_id) ?: 1);
        $buyer_name = get_field('order_buyer_name', $order_id);
        $buyer_email = get_field('order_buyer_email', $order_id);
        $buyer_tel = get_field('order_buyer_tel', $order_id);
        $user_id = get_field('order_user_id', $order_id);
        
        // 1. 기존 주문 상태 변경
        update_field('order_status', 'carried_over', $order_id);
        update_post_meta($order_id, 'order_carryover_reason', $reason);
        update_post_meta($order_id, 'order_carryover_target_program', $target_program_id);
        
        // 2. 기존 프로그램 신청자 수 감소
        if ($original_program_id > 0) {
            $current_count = intval(get_post_meta($original_program_id, 'program_applicants_count', true) ?? 0);
            update_post_meta($original_program_id, 'program_applicants_count', max(0, $current_count - $original_quantity));
        }
        
        // 3. 새로운 프로그램에 무료 주문 생성
        $new_mbr_ref_no = 'CARRY-' . $order_id . '-' . time();
        $target_program = get_post($target_program_id);
        $goods_name = $target_program ? $target_program->post_title : '이월 프로그램';
        
        $new_order_info = array(
            'mbrRefNo' => $new_mbr_ref_no,
            'amount' => 0,
            'paymethod' => 'FREE',
            'goodsName' => "[이월] " . $goods_name,
            'buyerName' => $buyer_name,
            'buyerEmail' => $buyer_email,
            'buyerTel' => $buyer_tel,
            'program_id' => $target_program_id,
            'product_id' => $target_program_id,
            'quantity' => $original_quantity,
            'user_id' => $user_id
        );
        
        $new_payment_result = array(
            'status' => 'success',
            'paymethodName' => '이월(무료)',
            'tranDate' => date('YmdHis')
        );
        
        $new_order_post_id = create_order_post($new_order_info, $new_payment_result);
        
        if ($new_order_post_id) {
            // 새 대기자에 이월 정보 기록
            update_post_meta($new_order_post_id, 'order_is_carryover', 'yes');
            update_post_meta($new_order_post_id, 'order_original_order_id', $order_id);
            
            // 새 프로그램 신청자 수 증가
            $target_current_count = intval(get_post_meta($target_program_id, 'program_applicants_count', true) ?? 0);
            update_post_meta($target_program_id, 'program_applicants_count', $target_current_count + $original_quantity);
            
            wp_send_json_success(array('message' => '이월 처리가 완료되었습니다.', 'new_order_id' => $new_order_post_id));
        } else {
            wp_send_json_error(array('message' => '새 주문 생성에 실패했습니다.'));
        }
    }
    wp_ajax("admin_carryover_order", true);
    
    // 관리자용 환불 금액 계산 조회 (정책 기준)
    function get_admin_refund_calculation() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => '권한이 없습니다.'));
            return;
        }
        
        require_once(get_stylesheet_directory() . '/src/php/refund_calculator.php');
        
        $program_id = intval($_POST['program_id'] ?? 0);
        $amount = intval($_POST['amount'] ?? 0);
        $order_date = sanitize_text_field($_POST['order_date'] ?? '');
        
        if ($program_id <= 0 || $amount <= 0) {
            wp_send_json_error(array('message' => '파라미터가 올바르지 않습니다.'));
            return;
        }
        
        $result = calculate_refund($program_id, $amount, $order_date);
        wp_send_json_success($result);
    }
    wp_ajax("get_admin_refund_calculation", true);
?>