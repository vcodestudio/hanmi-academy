<?php
/**
 * 프로그램 신청자 현황 페이지
 * 관리자 전용
 */

if (!current_user_can('manage_options')) {
    wp_die('권한이 없습니다.');
}

// 프로그램 필터
$selected_program_id = isset($_GET['program_id']) ? intval($_GET['program_id']) : 0;

// 게시 상태 필터 (기본값: publish)
$selected_post_status = isset($_GET['post_status']) ? sanitize_text_field($_GET['post_status']) : 'publish';

// 정렬 파라미터
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'order_date';
$order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC' ? 'ASC' : 'DESC';

// 모든 프로그램 목록 가져오기
$all_programs = get_posts(array(
    'post_type' => 'post_program',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'post_status' => 'publish'
));

// 환불 신청 주문 조회 쿼리
$refund_request_query_args = array(
    'post_type' => 'post_order',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => $selected_post_status,
    'meta_query' => array(
        array(
            'key' => 'order_status',
            'value' => 'refund_requested',
            'compare' => '='
        )
    )
);

// 프로그램 필터 적용 (환불 신청)
if ($selected_program_id > 0) {
    $refund_request_query_args['meta_query'][] = array(
        'key' => 'order_program_id',
        'value' => $selected_program_id,
        'compare' => '='
    );
}

$refund_request_posts = get_posts($refund_request_query_args);

// 환불 신청 주문 데이터 가공
$refund_requests = array();
require_once(get_stylesheet_directory() . '/src/php/refund_calculator.php');

foreach ($refund_request_posts as $order_post) {
    $order_post_id = $order_post->ID;
    $program_id = get_field('order_program_id', $order_post_id);
    
    $refund_request_date = get_field('order_refund_request_date', $order_post_id);
    $refund_request_date_formatted = '';
    $refund_request_date_raw = '';
    if ($refund_request_date) {
        if (strlen($refund_request_date) >= 8) {
            $year = substr($refund_request_date, 0, 4);
            $month = substr($refund_request_date, 4, 2);
            $day = substr($refund_request_date, 6, 2);
            $hour = strlen($refund_request_date) >= 10 ? substr($refund_request_date, 8, 2) : '';
            $minute = strlen($refund_request_date) >= 12 ? substr($refund_request_date, 10, 2) : '';
            if ($hour && $minute) {
                $refund_request_date_formatted = $year . '.' . $month . '.' . $day . ' ' . $hour . ':' . $minute;
                $refund_request_date_raw = $refund_request_date;
            } else {
                $refund_request_date_formatted = $year . '.' . $month . '.' . $day;
                $refund_request_date_raw = $year . $month . $day;
            }
        }
    }
    if (empty($refund_request_date_formatted)) {
        $refund_request_date_formatted = get_the_date('Y.m.d H:i', $order_post_id);
        $refund_request_date_raw = get_the_date('YmdHis', $order_post_id);
    }
    
    // 프로그램 시작일 기준 경과일 계산
    $date_description = '';
    if ($program_id) {
        $date_description = get_refund_date_description($program_id, $refund_request_date);
    }
    
    $refund_requests[] = array(
        'post_id' => $order_post_id,
        'mbr_ref_no' => get_field('order_mbr_ref_no', $order_post_id),
        'ref_no' => get_field('order_ref_no', $order_post_id),
        'buyer_name' => get_field('order_buyer_name', $order_post_id),
        'buyer_email' => get_field('order_buyer_email', $order_post_id),
        'buyer_tel' => get_field('order_buyer_tel', $order_post_id),
        'program_id' => $program_id,
        'program_title' => $program_id ? get_the_title($program_id) : get_field('order_goods_name', $order_post_id),
        'amount' => intval(get_field('order_amount', $order_post_id) ?: 0),
        'refund_request_amount' => intval(get_field('order_refund_request_amount', $order_post_id) ?: 0),
        'refund_request_reason' => get_field('order_refund_request_reason', $order_post_id),
        'refund_request_date' => $refund_request_date_formatted,
        'refund_request_date_raw' => $refund_request_date_raw,
        'date_description' => $date_description,
        'quantity' => intval(get_field('order_quantity', $order_post_id) ?: 1),
    );
}

// 주문 조회 쿼리 (환불 신청 제외)
$order_query_args = array(
    'post_type' => 'post_order',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => $selected_post_status,
    'meta_query' => array(
        array(
            'key' => 'order_status',
            'value' => array('success', 'cancelled', 'refunded'),
            'compare' => 'IN'
        )
    )
);

// 프로그램 필터 적용
if ($selected_program_id > 0) {
    $order_query_args['meta_query'][] = array(
        'key' => 'order_program_id',
        'value' => $selected_program_id,
        'compare' => '='
    );
}

$order_posts = get_posts($order_query_args);

// 주문 데이터 가공
$orders = array();
foreach ($order_posts as $order_post) {
    $order_post_id = $order_post->ID;
    $program_id = get_field('order_program_id', $order_post_id);
    
    $tran_date = get_field('order_tran_date', $order_post_id);
    $order_date = '';
    $order_date_raw = '';
    if ($tran_date) {
        if (strlen($tran_date) >= 8) {
            $year = substr($tran_date, 0, 4);
            $month = substr($tran_date, 4, 2);
            $day = substr($tran_date, 6, 2);
            $hour = strlen($tran_date) >= 10 ? substr($tran_date, 8, 2) : '';
            $minute = strlen($tran_date) >= 12 ? substr($tran_date, 10, 2) : '';
            if ($hour && $minute) {
                $order_date = $year . '.' . $month . '.' . $day . ' ' . $hour . ':' . $minute;
                $order_date_raw = $year . $month . $day . $hour . $minute;
            } else {
                $order_date = $year . '.' . $month . '.' . $day;
                $order_date_raw = $year . $month . $day;
            }
        }
    }
    if (empty($order_date)) {
        $order_date = get_the_date('Y.m.d H:i', $order_post_id);
        $order_date_raw = get_the_date('YmdHis', $order_post_id);
    }
    
    $orders[] = array(
        'post_id' => $order_post_id,
        'mbr_ref_no' => get_field('order_mbr_ref_no', $order_post_id),
        'ref_no' => get_field('order_ref_no', $order_post_id),
        'buyer_name' => get_field('order_buyer_name', $order_post_id),
        'buyer_email' => get_field('order_buyer_email', $order_post_id),
        'buyer_tel' => get_field('order_buyer_tel', $order_post_id),
        'program_id' => $program_id,
        'program_title' => $program_id ? get_the_title($program_id) : get_field('order_goods_name', $order_post_id),
        'amount' => intval(get_field('order_amount', $order_post_id) ?: 0),
        'paymethod' => get_field('order_paymethod', $order_post_id),
        'paymethod_name' => get_field('order_paymethod_name', $order_post_id),
        'order_date' => $order_date,
        'order_date_raw' => $order_date_raw,
        'status' => get_field('order_status', $order_post_id) ?: 'pending',
        'quantity' => intval(get_field('order_quantity', $order_post_id) ?: 1),
    );
}

// 정렬 처리
if (!empty($orders)) {
    usort($orders, function($a, $b) use ($orderby, $order) {
        $result = 0;
        
        switch ($orderby) {
            case 'mbr_ref_no':
                $result = strcmp($a['mbr_ref_no'] ?? '', $b['mbr_ref_no'] ?? '');
                break;
            case 'buyer_name':
                $result = strcmp($a['buyer_name'] ?? '', $b['buyer_name'] ?? '');
                break;
            case 'buyer_email':
                $result = strcmp($a['buyer_email'] ?? '', $b['buyer_email'] ?? '');
                break;
            case 'buyer_tel':
                $result = strcmp($a['buyer_tel'] ?? '', $b['buyer_tel'] ?? '');
                break;
            case 'program_title':
                $result = strcmp($a['program_title'] ?? '', $b['program_title'] ?? '');
                break;
            case 'amount':
                $result = ($a['amount'] ?? 0) - ($b['amount'] ?? 0);
                break;
            case 'paymethod_name':
                $result = strcmp($a['paymethod_name'] ?? $a['paymethod'] ?? '', $b['paymethod_name'] ?? $b['paymethod'] ?? '');
                break;
            case 'order_date':
                $result = strcmp($a['order_date_raw'] ?? '', $b['order_date_raw'] ?? '');
                break;
            case 'quantity':
                $result = ($a['quantity'] ?? 0) - ($b['quantity'] ?? 0);
                break;
            case 'status':
                $result = strcmp($a['status'] ?? '', $b['status'] ?? '');
                break;
            default:
                $result = strcmp($a['order_date_raw'] ?? '', $b['order_date_raw'] ?? '');
        }
        
        return $order === 'ASC' ? $result : -$result;
    });
}
?>
<div class="wrap">
    <h1>프로그램 신청자현황</h1>
    
    <!-- 필터 -->
    <div style="margin: 20px 0;">
        <form method="get" action="">
            <input type="hidden" name="page" value="program-applicants">
            <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                <div>
                    <label for="program_id">프로그램 선택: </label>
                    <select name="program_id" id="program_id" style="min-width: 300px;">
                        <option value="0">전체 프로그램</option>
                        <?php foreach ($all_programs as $program): ?>
                            <option value="<?= $program->ID ?>" <?= $selected_program_id == $program->ID ? 'selected' : '' ?>>
                                <?= esc_html($program->post_title) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="post_status">게시 상태: </label>
                    <select name="post_status" id="post_status" style="min-width: 150px;">
                        <option value="publish" <?= $selected_post_status === 'publish' ? 'selected' : '' ?>>발행됨 (publish)</option>
                        <option value="draft" <?= $selected_post_status === 'draft' ? 'selected' : '' ?>>임시저장 (draft)</option>
                        <option value="trash" <?= $selected_post_status === 'trash' ? 'selected' : '' ?>>휴지통 (trash)</option>
                        <option value="any" <?= $selected_post_status === 'any' ? 'selected' : '' ?>>전체</option>
                    </select>
                </div>
                <div>
                    <input type="submit" class="button" value="필터 적용">
                </div>
            </div>
        </form>
    </div>
    
    <!-- 환불 신청 테이블 -->
    <?php if (!empty($refund_requests)): ?>
    <div style="margin: 30px 0;">
        <h2 style="margin-bottom: 20px; font-size: 18px; font-weight: 600;">환불 신청 내역</h2>
        <div class="table-container">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 120px;">주문번호</th>
                        <th style="width: 100px;">신청자</th>
                        <th style="width: 150px;">프로그램명</th>
                        <th style="width: 100px;">결제금액</th>
                        <th style="width: 120px;">환불 신청 금액</th>
                        <th style="width: 200px;">환불 사유</th>
                        <th style="width: 180px;">환불 신청 일시</th>
                        <th style="width: 150px;">작업</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($refund_requests as $refund_request): ?>
                        <tr>
                            <td><?= esc_html($refund_request['mbr_ref_no']) ?></td>
                            <td><?= esc_html($refund_request['buyer_name']) ?></td>
                            <td><?= esc_html($refund_request['program_title']) ?></td>
                            <td><?= $refund_request['amount'] > 0 ? number_format($refund_request['amount']) . '원' : '무료' ?></td>
                            <td>
                                <input type="number" 
                                       class="refund-amount-input" 
                                       data-order-id="<?= $refund_request['post_id'] ?>"
                                       value="<?= $refund_request['refund_request_amount'] ?>"
                                       min="0"
                                       max="<?= $refund_request['amount'] ?>"
                                       style="width: 100px; padding: 4px;">
                                <span>원</span>
                            </td>
                            <td><?= esc_html($refund_request['refund_request_reason'] ?: '-') ?></td>
                            <td>
                                <?= esc_html($refund_request['refund_request_date']) ?>
                                <?php if ($refund_request['date_description']): ?>
                                    <br><span style="color: #666; font-size: 12px;">(<?= esc_html($refund_request['date_description']) ?>)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="button process-refund-btn" 
                                        data-order-id="<?= $refund_request['post_id'] ?>"
                                        data-mbr-ref-no="<?= esc_attr($refund_request['mbr_ref_no']) ?>"
                                        data-ref-no="<?= esc_attr($refund_request['ref_no']) ?>"
                                        data-program-id="<?= $refund_request['program_id'] ?>"
                                        data-quantity="<?= $refund_request['quantity'] ?>"
                                        style="margin-right: 5px;">
                                    환불
                                </button>
                                <button class="button cancel-refund-btn" 
                                        data-order-id="<?= $refund_request['post_id'] ?>"
                                        style="background-color: #f0f0f0; color: #000;">
                                    취소
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- 주문 목록 테이블 -->
    <div style="margin: 30px 0;">
        <h2 style="margin-bottom: 20px; font-size: 18px; font-weight: 600;">전체 주문 내역</h2>
        <div class="table-container">
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <?php
                // 정렬 링크 생성 함수
                function get_sortable_header($label, $column, $current_orderby, $current_order) {
                    $url = admin_url('admin.php?page=program-applicants');
                    if (isset($_GET['program_id'])) {
                        $url .= '&program_id=' . intval($_GET['program_id']);
                    }
                    if (isset($_GET['post_status'])) {
                        $url .= '&post_status=' . esc_attr($_GET['post_status']);
                    }
                    
                    $new_order = 'DESC';
                    $arrow = '';
                    if ($current_orderby === $column) {
                        $new_order = $current_order === 'ASC' ? 'DESC' : 'ASC';
                        $arrow = $current_order === 'ASC' ? ' ↑' : ' ↓';
                    }
                    
                    $url .= '&orderby=' . esc_attr($column) . '&order=' . $new_order;
                    
                    return '<th style="width: 120px;"><a href="' . esc_url($url) . '" style="text-decoration: none; color: inherit; font-weight: 600;">' . esc_html($label) . $arrow . '</a></th>';
                }
                
                echo get_sortable_header('주문번호', 'mbr_ref_no', $orderby, $order);
                echo get_sortable_header('신청자', 'buyer_name', $orderby, $order);
                echo get_sortable_header('이메일', 'buyer_email', $orderby, $order);
                echo get_sortable_header('전화번호', 'buyer_tel', $orderby, $order);
                echo get_sortable_header('프로그램명', 'program_title', $orderby, $order);
                echo get_sortable_header('결제금액', 'amount', $orderby, $order);
                echo get_sortable_header('결제수단', 'paymethod_name', $orderby, $order);
                echo get_sortable_header('결제일시', 'order_date', $orderby, $order);
                echo get_sortable_header('수량', 'quantity', $orderby, $order);
                echo get_sortable_header('상태', 'status', $orderby, $order);
                ?>
                <th style="width: 150px;">작업</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="11" style="text-align: center; padding: 40px;">
                        신청 내역이 없습니다.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= esc_html($order['mbr_ref_no']) ?></td>
                        <td><?= esc_html($order['buyer_name']) ?></td>
                        <td><?= esc_html($order['buyer_email']) ?></td>
                        <td><?= esc_html($order['buyer_tel']) ?></td>
                        <td><?= esc_html($order['program_title']) ?></td>
                        <td><?= $order['amount'] > 0 ? number_format($order['amount']) . '원' : '무료' ?></td>
                        <td><?= esc_html($order['paymethod_name'] ?: $order['paymethod']) ?></td>
                        <td><?= esc_html($order['order_date']) ?></td>
                        <td><?= $order['quantity'] ?>매</td>
                        <td>
                            <?php
                            $status_labels = array(
                                'success' => '결제완료',
                                'cancelled' => '취소됨',
                                'refunded' => '환불됨',
                                'refund_requested' => '환불 요청',
                                'pending' => '대기중'
                            );
                            echo esc_html($status_labels[$order['status']] ?? $order['status']);
                            ?>
                        </td>
                        <td>
                            <?php if ($order['status'] === 'success'): ?>
                                <button class="button process-order-btn" 
                                        data-order-id="<?= $order['post_id'] ?>"
                                        data-mbr-ref-no="<?= esc_attr($order['mbr_ref_no']) ?>"
                                        data-ref-no="<?= esc_attr($order['ref_no']) ?>"
                                        data-amount="<?= $order['amount'] ?>"
                                        data-program-id="<?= $order['program_id'] ?>"
                                        data-program-title="<?= esc_attr($order['program_title']) ?>"
                                        data-quantity="<?= $order['quantity'] ?>"
                                        style="background-color: #2271b1; color: #fff; border-color: #2271b1;">
                                    주문 처리
                                </button>
                            <?php else: ?>
                                <span style="color: #999;">처리완료</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.wrap {
    margin: 20px 20px 0 0;
}
.wp-list-table th {
    font-weight: 600;
}

/* 테이블 가로 스크롤 컨테이너 */
.table-container {
    overflow-x: auto;
    overflow-y: visible;
    width: 100%;
    max-width: 100%;
    margin: 20px 0;
    -webkit-overflow-scrolling: touch; /* iOS 부드러운 스크롤 */
}

.table-container table {
    min-width: 100%;
    table-layout: auto;
    white-space: nowrap;
}

.table-container table th,
.table-container table td {
    white-space: nowrap;
    padding: 10px;
}

/* WordPress 알림 배너 숨기기 */
#wpbody-content > .notice,
#wpbody-content > .updated,
#wpbody-content > .error,
#wpbody-content > .warning,
#wpbody-content > .info,
#wpbody-content .notice,
#wpbody-content .updated,
#wpbody-content .error,
#wpbody-content .warning,
#wpbody-content .info,
.quadlayers_woocommerce-checkout-manager_notice_delay,
.quadlayers_woocommerce-direct-checkout_notice_delay,
.wc-subscriptions-moved-notice,
.wc-subscriptions-site-moved-notice,
.woocommerce-message,
.woocommerce-info,
.woocommerce-error,
.woocommerce-nux-notice,
div[class*="notice"],
div[class*="quadlayers"],
div[class*="wc-subscriptions"] {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
}
</style>

<!-- 주문 처리 모달 -->
<div id="order-process-modal" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fff; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 500px; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <h2 style="margin: 0; font-size: 18px;">주문 처리: <span id="modal-order-no"></span></h2>
            <span id="close-modal" style="cursor: pointer; font-size: 24px; font-weight: bold;">&times;</span>
        </div>
        
        <div id="modal-content">
            <div style="margin-bottom: 20px;">
                <p><strong>프로그램:</strong> <span id="modal-program-title"></span></p>
                <p><strong>결제금액:</strong> <span id="modal-order-amount-text"></span>원</p>
            </div>
            
            <div style="margin-bottom: 20px; padding: 15px; background-color: #f9f9f9; border-radius: 4px;">
                <h3 style="margin-top: 0; font-size: 14px;">처리 유형 선택</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <button type="button" class="action-type-btn button" data-type="cancel_full">폐강 (100% 환불)</button>
                    <button type="button" class="action-type-btn button" data-type="illness">질병 (100% 환불)</button>
                    <button type="button" class="action-type-btn button" data-type="policy">정책 환불 (계산)</button>
                    <button type="button" class="action-type-btn button" data-type="carryover">다음 학기 이월</button>
                </div>
            </div>
            
            <div id="action-details" style="display: none; margin-bottom: 20px;">
                <!-- 환불 필드 -->
                <div id="refund-fields" style="display: none;">
                    <div style="margin-bottom: 10px;">
                        <label style="display: block; margin-bottom: 5px;">환불 금액:</label>
                        <input type="number" id="final-refund-amount" style="width: 100%;" min="0">
                        <small id="refund-calc-reason" style="display: block; margin-top: 5px; color: #d63638;"></small>
                    </div>
                </div>
                
                <!-- 이월 필드 -->
                <div id="carryover-fields" style="display: none;">
                    <div style="margin-bottom: 10px;">
                        <label style="display: block; margin-bottom: 5px;">이월 대상 프로그램:</label>
                        <select id="target-program-id" style="width: 100%;">
                            <option value="">프로그램을 선택하세요</option>
                            <?php foreach ($all_programs as $program): ?>
                                <option value="<?= $program->ID ?>"><?= esc_html($program->post_title) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label style="display: block; margin-bottom: 5px;">처리 사유:</label>
                    <textarea id="action-reason" style="width: 100%; height: 60px;"></textarea>
                </div>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee; padding-top: 20px;">
                <button type="button" id="confirm-process-btn" class="button button-primary" disabled>처리 실행</button>
                <button type="button" id="cancel-modal-btn" class="button">취소</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // WordPress 알림 배너 강제 숨기기
    function hideNotices() {
        const notices = document.querySelectorAll(
            '.notice, .updated, .error, .warning, .info, ' +
            '.quadlayers_woocommerce-checkout-manager_notice_delay, ' +
            '.quadlayers_woocommerce-direct-checkout_notice_delay, ' +
            '.wc-subscriptions-moved-notice, ' +
            '.wc-subscriptions-site-moved-notice, ' +
            '[class*="notice"], ' +
            '[class*="quadlayers"], ' +
            '[class*="wc-subscriptions"]'
        );
        notices.forEach(function(notice) {
            if (notice && notice.parentElement && notice.parentElement.id === 'wpbody-content') {
                notice.style.display = 'none';
                notice.style.visibility = 'hidden';
                notice.style.opacity = '0';
                notice.style.height = '0';
                notice.style.margin = '0';
                notice.style.padding = '0';
                notice.style.overflow = 'hidden';
            }
        });
    }
    
    // 즉시 실행
    hideNotices();
    
    // DOM 변경 감지하여 동적으로 추가되는 알림도 숨기기
    const observer = new MutationObserver(function(mutations) {
        hideNotices();
    });
    
    const wpbodyContent = document.getElementById('wpbody-content');
    if (wpbodyContent) {
        observer.observe(wpbodyContent, {
            childList: true,
            subtree: true
        });
    }
    
    // 추가 안전장치: 주기적으로 확인
    setInterval(hideNotices, 500);

    // 주문 처리 버튼 이벤트
    document.querySelectorAll('.process-order-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const mbrRefNo = this.getAttribute('data-mbr-ref-no');
            const refNo = this.getAttribute('data-ref-no');
            const amount = parseInt(this.getAttribute('data-amount') || 0);
            const programId = this.getAttribute('data-program-id');
            const programTitle = this.getAttribute('data-program-title');
            const quantity = this.getAttribute('data-quantity');
            
            // 모달 초기화
            document.getElementById('modal-order-no').textContent = mbrRefNo;
            document.getElementById('modal-program-title').textContent = programTitle;
            document.getElementById('modal-order-amount-text').textContent = amount.toLocaleString();
            document.getElementById('final-refund-amount').value = '';
            document.getElementById('action-reason').value = '';
            document.getElementById('target-program-id').value = '';
            document.getElementById('action-details').style.display = 'none';
            document.getElementById('confirm-process-btn').disabled = true;
            
            // 데이터 저장
            const modal = document.getElementById('order-process-modal');
            modal.setAttribute('data-order-id', orderId);
            modal.setAttribute('data-mbr-ref-no', mbrRefNo);
            modal.setAttribute('data-ref-no', refNo);
            modal.setAttribute('data-amount', amount);
            modal.setAttribute('data-program-id', programId);
            modal.setAttribute('data-quantity', quantity);
            
            modal.style.display = 'block';
        });
    });
    
    // 모달 닫기
    document.getElementById('close-modal').addEventListener('click', closeModal);
    document.getElementById('cancel-modal-btn').addEventListener('click', closeModal);
    
    function closeModal() {
        document.getElementById('order-process-modal').style.display = 'none';
    }
    
    // 처리 유형 선택 버튼
    document.querySelectorAll('.action-type-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            const modal = document.getElementById('order-process-modal');
            const amount = parseInt(modal.getAttribute('data-amount'));
            const programId = modal.getAttribute('data-program-id');
            
            document.querySelectorAll('.action-type-btn').forEach(b => b.classList.remove('button-primary'));
            this.classList.add('button-primary');
            
            document.getElementById('action-details').style.display = 'block';
            document.getElementById('confirm-process-btn').disabled = false;
            document.getElementById('confirm-process-btn').setAttribute('data-action-type', type);
            
            // 필드 가시성 조정
            const refundFields = document.getElementById('refund-fields');
            const carryoverFields = document.getElementById('carryover-fields');
            const refundAmountInput = document.getElementById('final-refund-amount');
            const reasonInput = document.getElementById('action-reason');
            const refundReasonText = document.getElementById('refund-calc-reason');
            
            refundFields.style.display = 'none';
            carryoverFields.style.display = 'none';
            refundReasonText.textContent = '';
            
            if (type === 'cancel_full') {
                refundFields.style.display = 'block';
                refundAmountInput.value = amount;
                refundAmountInput.readOnly = true;
                reasonInput.value = '적정 수강인원 미달로 인한 폐강';
            } else if (type === 'illness') {
                refundFields.style.display = 'block';
                refundAmountInput.value = amount;
                refundAmountInput.readOnly = true;
                reasonInput.value = '질병으로 인한 수업 불가능 (진단서 확인됨)';
            } else if (type === 'policy') {
                refundFields.style.display = 'block';
                refundAmountInput.value = '';
                refundAmountInput.readOnly = false;
                reasonInput.value = '고객 요청으로 인한 정규 환불 정책 적용';
                
                // 정책 환불 금액 계산 API 호출
                const formData = new FormData();
                formData.append('action', 'get_admin_refund_calculation');
                formData.append('program_id', programId);
                formData.append('amount', amount);
                
                fetch('<?= admin_url('admin-ajax.php') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        refundAmountInput.value = data.data.refund_amount;
                        refundReasonText.textContent = data.data.reason;
                    }
                });
            } else if (type === 'carryover') {
                carryoverFields.style.display = 'block';
                reasonInput.value = '부득이한 사정으로 인한 다음 학기 이월 (3주 이내)';
            }
        });
    });
    
    // 처리 실행
    document.getElementById('confirm-process-btn').addEventListener('click', function() {
        const type = this.getAttribute('data-action-type');
        const modal = document.getElementById('order-process-modal');
        const orderId = modal.getAttribute('data-order-id');
        const mbrRefNo = modal.getAttribute('data-mbr-ref-no');
        const refNo = modal.getAttribute('data-ref-no');
        const programId = modal.getAttribute('data-program-id');
        const quantity = modal.getAttribute('data-quantity');
        const reason = document.getElementById('action-reason').value;
        
        if (!confirm('정말 처리를 진행하시겠습니까?')) return;
        
        this.disabled = true;
        this.textContent = '처리 중...';
        
        if (type === 'carryover') {
            const targetProgramId = document.getElementById('target-program-id').value;
            if (!targetProgramId) {
                alert('이월 대상 프로그램을 선택해주세요.');
                this.disabled = false;
                this.textContent = '처리 실행';
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'admin_carryover_order');
            formData.append('order_id', orderId);
            formData.append('target_program_id', targetProgramId);
            formData.append('reason', reason);
            
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('이월 처리가 완료되었습니다.');
                    location.reload();
                } else {
                    alert('실패: ' + (data.data?.message || '오류 발생'));
                    this.disabled = false;
                    this.textContent = '처리 실행';
                }
            });
        } else {
            // 환불 처리
            const refundAmount = document.getElementById('final-refund-amount').value;
            
            const formData = new FormData();
            formData.append('action', 'admin_refund_order');
            formData.append('order_id', orderId);
            formData.append('mbr_ref_no', mbrRefNo);
            formData.append('ref_no', refNo);
            formData.append('amount', refundAmount);
            formData.append('program_id', programId);
            formData.append('quantity', quantity);
            formData.append('cancel_reason', reason);
            
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('환불 처리가 완료되었습니다.');
                    location.reload();
                } else {
                    alert('실패: ' + (data.data?.message || '오류 발생'));
                    this.disabled = false;
                    this.textContent = '처리 실행';
                }
            });
        }
    });

    // 신청취소 버튼 이벤트
    document.querySelectorAll('.cancel-order-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!confirm('정말 이 신청을 취소하시겠습니까?')) {
                return;
            }
            
            const orderId = this.getAttribute('data-order-id');
            const mbrRefNo = this.getAttribute('data-mbr-ref-no');
            const refNo = this.getAttribute('data-ref-no');
            const amount = this.getAttribute('data-amount');
            const programId = this.getAttribute('data-program-id');
            const quantity = this.getAttribute('data-quantity');
            
            btn.disabled = true;
            btn.textContent = '처리중...';
            
            const formData = new FormData();
            formData.append('action', 'admin_cancel_order');
            formData.append('order_id', orderId);
            formData.append('mbr_ref_no', mbrRefNo);
            formData.append('ref_no', refNo);
            formData.append('amount', amount);
            formData.append('program_id', programId);
            formData.append('quantity', quantity);
            
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('신청이 취소되었습니다.');
                    location.reload();
                } else {
                    alert('취소 실패: ' + (data.data?.message || '알 수 없는 오류'));
                    btn.disabled = false;
                    btn.textContent = '신청취소';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('처리 중 오류가 발생했습니다.');
                btn.disabled = false;
                btn.textContent = '신청취소';
            });
        });
    });
    
    // 환불 버튼 이벤트
    document.querySelectorAll('.refund-order-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!confirm('정말 이 주문을 환불 처리하시겠습니까?')) {
                return;
            }
            
            const orderId = this.getAttribute('data-order-id');
            const mbrRefNo = this.getAttribute('data-mbr-ref-no');
            const refNo = this.getAttribute('data-ref-no');
            const amount = this.getAttribute('data-amount');
            const programId = this.getAttribute('data-program-id');
            const quantity = this.getAttribute('data-quantity');
            
            btn.disabled = true;
            btn.textContent = '처리중...';
            
            const formData = new FormData();
            formData.append('action', 'admin_refund_order');
            formData.append('order_id', orderId);
            formData.append('mbr_ref_no', mbrRefNo);
            formData.append('ref_no', refNo);
            formData.append('amount', amount);
            formData.append('program_id', programId);
            formData.append('quantity', quantity);
            
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('환불이 처리되었습니다.');
                    location.reload();
                } else {
                    alert('환불 실패: ' + (data.data?.message || '알 수 없는 오류'));
                    btn.disabled = false;
                    btn.textContent = '환불';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('처리 중 오류가 발생했습니다.');
                btn.disabled = false;
                btn.textContent = '환불';
            });
        });
    });
    
    // 환불 신청 처리 버튼 이벤트
    document.querySelectorAll('.process-refund-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const mbrRefNo = this.getAttribute('data-mbr-ref-no');
            const refNo = this.getAttribute('data-ref-no');
            const programId = this.getAttribute('data-program-id');
            const quantity = this.getAttribute('data-quantity');
            
            // 환불 금액 가져오기
            const amountInput = document.querySelector('.refund-amount-input[data-order-id="' + orderId + '"]');
            const refundAmount = parseInt(amountInput.value) || 0;
            
            if (refundAmount <= 0) {
                alert('환불 금액을 입력해주세요.');
                return;
            }
            
            if (!confirm('환불 금액 ' + refundAmount.toLocaleString() + '원을 환불 처리하시겠습니까?')) {
                return;
            }
            
            btn.disabled = true;
            btn.textContent = '처리중...';
            
            const formData = new FormData();
            formData.append('action', 'admin_process_refund');
            formData.append('order_id', orderId);
            formData.append('mbr_ref_no', mbrRefNo);
            formData.append('ref_no', refNo);
            formData.append('amount', refundAmount);
            formData.append('program_id', programId);
            formData.append('quantity', quantity);
            
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('환불이 처리되었습니다.');
                    location.reload();
                } else {
                    alert('환불 실패: ' + (data.data?.message || '알 수 없는 오류'));
                    btn.disabled = false;
                    btn.textContent = '환불';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('처리 중 오류가 발생했습니다.');
                btn.disabled = false;
                btn.textContent = '환불';
            });
        });
    });
    
    // 환불 신청 취소 버튼 이벤트
    document.querySelectorAll('.cancel-refund-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!confirm('환불 신청을 취소하시겠습니까? 주문 상태가 "결제 완료"로 변경됩니다.')) {
                return;
            }
            
            const orderId = this.getAttribute('data-order-id');
            
            btn.disabled = true;
            btn.textContent = '처리중...';
            
            const formData = new FormData();
            formData.append('action', 'admin_cancel_refund_request');
            formData.append('order_id', orderId);
            
            fetch('<?= admin_url('admin-ajax.php') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('환불 신청이 취소되었습니다.');
                    location.reload();
                } else {
                    alert('취소 실패: ' + (data.data?.message || '알 수 없는 오류'));
                    btn.disabled = false;
                    btn.textContent = '취소';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('처리 중 오류가 발생했습니다.');
                btn.disabled = false;
                btn.textContent = '취소';
            });
        });
    });
});
</script>
