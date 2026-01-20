<?php
/**
 * 환불 금액 계산 유틸리티 함수
 */

/**
 * 프로그램의 환불 가능 여부 및 환불 금액 계산
 * 
 * @param int $program_id 프로그램 ID
 * @param int $order_amount 주문 금액
 * @param string $order_date 주문일시 (Y.m.d 형식 또는 YmdHis 형식)
 * @param bool $is_cancelled_program 폐강 여부 (기본값: false)
 * @param bool $is_illness 질병으로 인한 수업 불가 여부 (기본값: false)
 * @return array {
 *     'can_refund' => bool,      // 환불 가능 여부
 *     'refund_amount' => int,    // 환불 금액 (0 또는 계산된 금액)
 *     'refund_rate' => float,    // 환불 비율 (0.0 ~ 1.0)
 *     'reason' => string,         // 환불 불가 사유 또는 환불 비율 설명
 *     'is_online_refund' => bool // 온라인 환불 가능 여부
 * }
 */
function calculate_refund($program_id, $order_amount, $order_date = null, $is_cancelled_program = false, $is_illness = false) {
    // 기본값 설정
    $result = array(
        'can_refund' => false,
        'refund_amount' => 0,
        'refund_rate' => 0.0,
        'reason' => '',
        'is_online_refund' => false
    );
    
    // 폐강 시 전액 환불
    if ($is_cancelled_program) {
        $result['can_refund'] = true;
        $result['refund_amount'] = $order_amount;
        $result['refund_rate'] = 1.0;
        $result['reason'] = '폐강으로 인한 전액 환불';
        $result['is_online_refund'] = true;
        return $result;
    }
    
    // 질병으로 인한 수업 불가 시 전액 환불 (온라인 환불 불가, 전화 안내 필요)
    if ($is_illness) {
        $result['can_refund'] = true;
        $result['refund_amount'] = $order_amount;
        $result['refund_rate'] = 1.0;
        $result['reason'] = '질병으로 인한 전액 환불 (진단서 필요)';
        $result['is_online_refund'] = false; // 전화 안내 필요
        return $result;
    }
    
    // 프로그램 시작일 가져오기
    $start_date = get_field('start', $program_id);
    
    // #region agent debug
    error_log(sprintf('[REFUND_DEBUG] PID:%s StartDate:%s Amt:%d', $program_id, $start_date, $order_amount));
    // #endregion

    if (empty($start_date)) {
        $result['reason'] = '프로그램 시작일 정보가 없습니다.';
        error_log('[REFUND_DEBUG] Error: ' . $result['reason']);
        return $result;
    }
    
    // 날짜 파싱
    $start_timestamp = strtotime(str_replace('.', '-', $start_date));

    if ($start_timestamp === false) {
        $result['reason'] = '프로그램 시작일 형식이 올바르지 않습니다.';
        error_log('[REFUND_DEBUG] Error: ' . $result['reason']);
        return $result;
    }
    
    // 현재 날짜 또는 주문일시 사용
    if ($order_date) {
        // ... (existing code for $order_date)
    } else {
        $current_timestamp = time();
    }
    
    if ($current_timestamp === false) {
        $result['reason'] = '날짜 계산 오류가 발생했습니다.';
        error_log('[REFUND_DEBUG] Error: ' . $result['reason']);
        return $result;
    }
    
    // 일 단위 차이 계산 (개강일 - 현재일)
    $days_diff = ceil(($start_timestamp - $current_timestamp) / (60 * 60 * 24));
    
    // #region agent debug
    error_log(sprintf('[REFUND_DEBUG] Now:%s DaysDiff:%d', date('Y-m-d H:i:s', $current_timestamp), $days_diff));
    // #endregion
    
    // 과정 정보 가져오기 (taxonomy: course)
    $course_terms = get_the_terms($program_id, 'course');
    $course_slug = '';
    if (!empty($course_terms) && !is_wp_error($course_terms)) {
        $course_slug = $course_terms[0]->slug;
    }
    
    error_log(sprintf('[REFUND_DEBUG] CourseSlug:%s', $course_slug));
    
    // 과정 구분에 따른 환불 규정 적용 (슬러그 기준: regular, short)
    if ($course_slug === 'short') {
        // 단기과정(6주 이내)
        if ($days_diff > 0) {
            // 개강 전: 전액 환불
            $result['can_refund'] = true;
            $result['refund_amount'] = $order_amount;
            $result['refund_rate'] = 1.0;
            $result['reason'] = '개강 전 전액 환불';
            $result['is_online_refund'] = true;
        } elseif ($days_diff >= -14) {
            // 개강 후 2주 이내: 70% 환불
            $result['can_refund'] = true;
            $result['refund_rate'] = 0.7;
            $result['refund_amount'] = intval($order_amount * 0.7);
            $result['reason'] = '개강 후 2주 이내 70% 환불';
            $result['is_online_refund'] = true;
        } else {
            // 개강 후 2주 이후: 환불 불가 (기존 주석 '3주'를 실제 로직 '2주'에 맞춰 수정)
            $result['can_refund'] = false;
            $result['reason'] = '개강 2주 후 환불 불가';
            $result['is_online_refund'] = false;
        }
    } elseif ($course_slug === 'regular') {
        // 정규과정
        if ($days_diff > 0) {
            // 개강 전: 전액 환불
            $result['can_refund'] = true;
            $result['refund_amount'] = $order_amount;
            $result['refund_rate'] = 1.0;
            $result['reason'] = '개강 전 전액 환불';
            $result['is_online_refund'] = true;
        } elseif ($days_diff >= -21) {
            // 개강 후 3주 이내: 70% 환불
            $result['can_refund'] = true;
            $result['refund_rate'] = 0.7;
            $result['refund_amount'] = intval($order_amount * 0.7);
            $result['reason'] = '개강 후 3주 이내 70% 환불';
            $result['is_online_refund'] = true;
        } else {
            // 개강 후 3주 이후: 환불 불가 (기존 주석 '4주'를 실제 로직 '3주'에 맞춰 수정)
            $result['can_refund'] = false;
            $result['reason'] = '개강 3주 후 환불 불가';
            $result['is_online_refund'] = false;
        }
    } else {
        // 과정 정보가 없거나 다른 경우 기존 기간 기반 로직 사용 (Fallback)
        // 프로그램 기간 계산 (시작일과 종료일)
        $end_date = get_field('end', $program_id);
        $program_duration_days = 0;
        if ($end_date) {
            $end_timestamp = strtotime(str_replace('.', '-', $end_date));
            if ($end_timestamp !== false) {
                $program_duration_days = ceil(($end_timestamp - $start_timestamp) / (60 * 60 * 24));
            }
        }
        
        // 단기과정 여부 확인 (6주 이내 = 42일 이내)
        $is_short_term = ($program_duration_days > 0 && $program_duration_days <= 42);

        if ($is_short_term) {
            if ($days_diff > 0) {
                $result['can_refund'] = true;
                $result['refund_amount'] = $order_amount;
                $result['refund_rate'] = 1.0;
                $result['reason'] = '개강 전 전액 환불';
                $result['is_online_refund'] = true;
            } elseif ($days_diff >= -14) {
                $result['can_refund'] = true;
                $result['refund_rate'] = 0.7;
                $result['refund_amount'] = intval($order_amount * 0.7);
                $result['reason'] = '개강 후 2주 이내 70% 환불';
                $result['is_online_refund'] = true;
            } else {
                $result['can_refund'] = false;
                $result['reason'] = '개강 2주 후 환불 불가';
                $result['is_online_refund'] = false;
            }
        } else {
            if ($days_diff > 0) {
                $result['can_refund'] = true;
                $result['refund_amount'] = $order_amount;
                $result['refund_rate'] = 1.0;
                $result['reason'] = '개강 전 전액 환불';
                $result['is_online_refund'] = true;
            } elseif ($days_diff >= -21) {
                $result['can_refund'] = true;
                $result['refund_rate'] = 0.7;
                $result['refund_amount'] = intval($order_amount * 0.7);
                $result['reason'] = '개강 후 3주 이내 70% 환불';
                $result['is_online_refund'] = true;
            } else {
                $result['can_refund'] = false;
                $result['reason'] = '개강 3주 후 환불 불가';
                $result['is_online_refund'] = false;
            }
        }
    }
    
    return $result;
}

/**
 * 환불 신청일 기준으로 프로그램 시작일까지의 경과일 계산
 * 
 * @param int $program_id 프로그램 ID
 * @param string $refund_request_date 환불 신청일 (YmdHis 또는 Y.m.d 형식)
 * @return string 경과일 설명 문자열 (예: "개강 30일 전" 또는 "개강 5일 후")
 */
function get_refund_date_description($program_id, $refund_request_date) {
    $start_date = get_field('start', $program_id);
    if (empty($start_date)) {
        return '';
    }
    
    // 시작일 파싱
    $start_timestamp = strtotime(str_replace('.', '-', $start_date));
    if ($start_timestamp === false) {
        return '';
    }
    
    // 환불 신청일 파싱
    if (strlen($refund_request_date) >= 8) {
        if (strlen($refund_request_date) >= 14) {
            // YmdHis 형식
            $year = substr($refund_request_date, 0, 4);
            $month = substr($refund_request_date, 4, 2);
            $day = substr($refund_request_date, 6, 2);
            $refund_timestamp = strtotime($year . '-' . $month . '-' . $day);
        } else {
            // Ymd 형식
            $year = substr($refund_request_date, 0, 4);
            $month = substr($refund_request_date, 4, 2);
            $day = substr($refund_request_date, 6, 2);
            $refund_timestamp = strtotime($year . '-' . $month . '-' . $day);
        }
    } else {
        // Y.m.d 형식
        $refund_timestamp = strtotime(str_replace('.', '-', $refund_request_date));
    }
    
    if ($refund_timestamp === false) {
        return '';
    }
    
    // 일 단위 차이 계산
    $days_diff = ceil(($start_timestamp - $refund_timestamp) / (60 * 60 * 24));
    
    if ($days_diff > 0) {
        return "개강 {$days_diff}일 전";
    } elseif ($days_diff < 0) {
        return "개강 " . abs($days_diff) . "일 후";
    } else {
        return "개강일";
    }
}
?>
