<?php
	function httpPost($url,$params){
		$postData = '';
		foreach($params as $k => $v) { 
			$postData .= $k . '='.urlencode($v).'&'; 
		}
		$postData = rtrim($postData, '&'); 
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$output=curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (curl_errno($ch)) {
			$error = curl_error($ch);
			curl_close($ch);
			return json_encode(array('resultCode' => '500', 'resultMessage' => 'CURL Error: ' . $error));
		}
		curl_close($ch);    
		return $output;
	}	
	function makeSignature($mbrNo,$mbrRefNo,$amount,$apiKey,$timestamp) {		
		$message = $mbrNo ."|".$mbrRefNo."|".$amount."|".$apiKey."|".$timestamp;
		return hash("sha256", $message);	
	}
	
	function makeTimestamp() {	
		date_default_timezone_set('Asia/Seoul');
		// 메뉴얼 기준: yyMMddHHmmssSSS (15자리, 밀리초 포함)
		$now = microtime(true);
		$date = date('ymdHis', (int)$now);
		$milliseconds = str_pad((int)(($now - (int)$now) * 1000), 3, '0', STR_PAD_LEFT);
		return $date . $milliseconds;
	}
	function generateRandomString($length = 4) {
	    return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)) )),1,$length);
	}
		
	function makeMbrRefNo($prefix) {
		return uniqid($prefix);
	}
	
	function pintLog($msg, $path){
		date_default_timezone_set('Asia/Seoul');
		$datetime = date_create('now')->format('Y-m-d H:i:s.u');
		$msg = "[".$datetime."] ".$msg."\n";
		error_log ($msg, 3, $path);
	}
	
	/**
	 * 신용카드 수기(KEY-IN) 결제 승인 API 호출
	 * 메뉴얼 기준: POST /v1/api/payments/payment/card-keyin/trans
	 * 
	 * @param string $mbrNo 가맹점번호
	 * @param string $mbrRefNo 가맹점주문번호
	 * @param string $cardNo 카드번호 (16자리)
	 * @param string $expd 카드유효기간 (YYMM 형식)
	 * @param int $amount 결제금액
	 * @param int $installment 할부개월 (0~24)
	 * @param string $goodsName 상품명
	 * @param string $keyinAuthType 키인인가구분 (K: 비인증 | O: 구인증)
	 * @param string $authType 구인증용 인증타입 (0: 생년월일 | 1: 사업자번호)
	 * @param string $regNo 구인증용 아이디 (생년월일 YYMMDD 또는 사업자번호)
	 * @param string $passwd 구인증용 카드 비밀번호 앞2자리
	 * @param string $apiKey API 키
	 * @param string $logPath 로그 경로
	 * @param string $relayBase RELAY 서버 Base URL
	 * @param string $customerName 구매자명 (선택)
	 * @param string $customerTelNo 구매자연락처 (선택)
	 * @param string $customerEmail 구매자이메일 (선택)
	 * @param string $retailerCode 거래처 코드 (선택)
	 * @return array|false API 응답 객체 또는 실패 시 false
	 */
	function cardKeyinPayment($mbrNo, $mbrRefNo, $cardNo, $expd, $amount, $installment, $goodsName, 
		$keyinAuthType, $authType, $regNo, $passwd, $apiKey, $logPath, $relayBase,
		$customerName = '', $customerTelNo = '', $customerEmail = '', $retailerCode = '') {
		
		// 타임스탬프 생성
		$timestamp = makeTimestamp();
		
		// Signature 생성
		$signature = makeSignature($mbrNo, $mbrRefNo, $amount, $apiKey, $timestamp);
		
		// 요청 파라미터 구성
		$parameters = array(
			'mbrNo' => $mbrNo,
			'mbrRefNo' => $mbrRefNo,
			'paymethod' => 'CARD',
			'cardNo' => $cardNo,
			'expd' => $expd,
			'amount' => $amount,
			'installment' => str_pad($installment, 2, '0', STR_PAD_LEFT), // 2자리로 패딩
			'goodsName' => $goodsName,
			'timestamp' => $timestamp,
			'signature' => $signature,
			'keyinAuthType' => $keyinAuthType,
			'authType' => $authType,
			'regNo' => $regNo,
			'passwd' => $passwd
		);
		
		// 선택 파라미터 추가
		if (!empty($customerName)) {
			$parameters['customerName'] = $customerName;
		}
		if (!empty($customerTelNo)) {
			$parameters['customerTelNo'] = $customerTelNo;
		}
		if (!empty($customerEmail)) {
			$parameters['customerEmail'] = $customerEmail;
		}
		if (!empty($retailerCode)) {
			$parameters['retailerCode'] = $retailerCode;
		}
		
		// API URL
		$apiUrl = $relayBase . "/v1/api/payments/payment/card-keyin/trans";
		
		// 로그 기록
		pintLog("KEYIN-PAYMENT-API: " . $apiUrl, $logPath);
		pintLog("KEYIN-PAYMENT-PARAM: " . print_r($parameters, TRUE), $logPath);
		
		// API 호출
		$result = httpPost($apiUrl, $parameters);
		pintLog("KEYIN-PAYMENT-RESPONSE: " . $result, $logPath);
		
		// 응답 파싱
		$obj = json_decode($result);
		
		return $obj;
	}
?>