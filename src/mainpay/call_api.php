﻿<?php
	require('config.php');                										// 가맹점 설정정보 정의
	
	/*=================================================================================================
       요청 파라미터
      =================================================================================================*/
	$mbrRefNo = makeMbrRefNo($mbrNo);											// 가맹점에서 나름대로 정한 중복되지 않는 주문번호
	$timestamp = makeTimestamp();												// 타임스탬프 (YYYYMMDDHHMI24SS 형식의 문자열)
	$amount = 1000;															// 결제금액 (숫자만 입력)
	$signature = makeSignature($mbrNo,$mbrRefNo,$amount,$apiKey,$timestamp); 	// 결제 위변조 방지를 위한 파라미터 서명 값
	// 매뉴얼을 참고하여 필요한 추가 파라미터를 작성한다
	// 추가파라미터 생략..

	var_dump($signature);
	
	/* Request 파라미터 array 생성 */
	$parameters = array(
		'mbrNo' => $mbrNo,
		'mbrRefNo' => $mbrRefNo
		//추가파리터 생략..
	);
	
	/*=================================================================================================
       API 호출
      =================================================================================================*/
	$apiUrl = $API_BASE."/v1/payment/ready";					

	$result = "";
	pintLog("PAY-API: ".$apiUrl, $logPath);
	pintLog("PARAM: ".print_r($parameters, TRUE), $logPath);
	$result = httpPost($apiUrl, $parameters);
	$obj = json_decode($result);				
	$resultCode = $obj->{'resultCode'};
	$resultMessage = $obj->{'resultMessage'};

	if($resultCode != "200"){				// API 호출 실패	
		echo $result;
		return;
	}
		
	/*=================================================================================================
       RESPONSE 데이터 추출 및 저장 (상점상황에 따라 DB에 저장해도 무방)
      =================================================================================================*/
	$data = $obj->{'data'};
	/* 응답 data 항목은 연동매뉴얼 참조*/
	//$refNo = $data->{'refNo'};				// 거래번호
	//$tranDate = $data->{'tranDate'};			// 거래일자

	
	echo("<br>## API 호출 결과 ##<br>" .$result);
	pintLog("RESPONSE: ".$result, $logPath);
	
?>