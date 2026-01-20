<?php
require_once(__DIR__ . '/utils.php');                // 유틸리티 포함

	$logPath = get_stylesheet_directory()."/src/app.log";            //(Windows)로그경로 (하위디렉토리를 반드시 만들어야 합니다.)
	//$logPath = "/home/app.log";        //(Linux)로그경로 (하위디렉토리를 반드시 만들어야 합니다.)

	/*=================================================================================================
		****** 아래의 정보는 TEST용 가맹점 정보 입니다. 
			REAL 서비스 전환 시 반드시 실제 가맹점 정보로 변경해야 합니다. *******
    =================================================================================================*/
	// 환경 설정
	$is_test_mode = false; // 운영 시 false로 변경
	
	if ($is_test_mode) {
		// 테스트 환경
		$API_BASE = "https://test-api-std.mainpay.co.kr";				//TEST API URL (결제창)
		$RELAY_BASE = "https://test-relay.mainpay.co.kr";				//TEST RELAY URL (KEY-IN)
		$mbrNo = "100011";												//테스트 가맹점번호
		// NOTE: 메인페이 개발자센터 '테스트 정보' 기준 값
		$apiKey = "U1FVQVJFLTEwMDAxMTIwMTgwNDA2MDkyNTMyMTA1MjM0";		//테스트 apiKey
	} else {
		// 실서버 환경
		$API_BASE = "https://api-std.mainpay.co.kr";					//REAL API URL (결제창)
		$RELAY_BASE = "https://relay.mainpay.co.kr";					//REAL RELAY URL (KEY-IN)
		$mbrNo = "113120";										//실제 가맹점번호
		$apiKey = "U1FVQVJFLTExMzEyMDIwMjMwNzA1MTAyMzI0Nzk3MTQ1";										//실제 API Key
	}
?>