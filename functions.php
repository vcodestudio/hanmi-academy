<?php
// require_once get_stylesheet_directory()."/src/hanmi-components/php/precomp.php";
include_once "src/hanmi-components/php/precomp.php";

// WooCommerce 세션 쿠키 경고 억제를 위한 에러 핸들러
set_error_handler(function($errno, $errstr, $errfile, $errline) {
	// WooCommerce 세션 핸들러의 모든 "Undefined array key" 경고 억제 (key 번호와 무관하게)
	if (strpos($errfile, 'class-wc-session-handler.php') !== false && 
	    strpos($errstr, 'Undefined array key') !== false) {
		return true; // 에러 억제
	}
	// 다른 에러는 기본 핸들러로 전달
	return false;
}, E_WARNING);

// debug handling
if (defined('WP_DEBUG') && WP_DEBUG) {
	ini_set("display_errors", 1);
	ini_set("display_startup_errors", 1);
	error_reporting(E_ALL);
} else {
	ini_set("display_errors", 0);
	ini_set("display_startup_errors", 0);
	// hide deprecation notices from legacy plugins/themes on production
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
}

function isValid($a)
{
	return !(
		(is_string($a) ? empty(trim($a)) : empty($a)) ||
		is_null($a) ||
		$a == false
	);
}

function isValidGroup($arr = [])
{
	$out = [];
	array_walk_recursive($arr, function ($v, $k) use (&$out) {
		if (isValid($v)) {
			$out[] = $v;
		}
	});
	return !empty($out);
}

function acf_img_set($imgarr, $image_size = "large", $max_width = 640)
{
	$image_id = $imgarr["id"] ?? "";
	// check the image ID is not blank
	if ($image_id != "") {
		// set the default src image size
		$image_src = wp_get_attachment_image_url($image_id, $image_size);

		// set the srcset with various image sizes
		$image_srcset = wp_get_attachment_image_srcset($image_id, $image_size);

		// generate the markup for the responsive image
		return "
		src=\"$image_src\" 
		srcset=\"$image_srcset\"
		sizes=\"(max-width:{$max_width}px) 100vw, {$max_width}px\"
		";
	} else {
		$error = getImg("error.svg");
		return "src=\"$error\"";
	}
}

function getPosts()
{
	$p = $_POST;
	$arg = [
		"post_type" => "post",
		"post_state" => "publish",
		"tax_query" => [],
		"meta_query" => ["AND"],
		"s" => $p["s"],
		"posts_per_page" => 16,
		"paged" => $p["p"],
	];
}
function getPage($slug = "")
{
	$query = get_posts([
		"post_type" => "page",
		"name" => $slug,
		"post_status" => "publish",
	]);
	if ($out = $query[0] ?? null) {
		$out->permalink = get_permalink($out);
	}

	return $out;
}

function _acfobjs()
{
	$fields = [
		"info",
		"info_download",
		"copywrite",
		"achive",
		"insurance",
		"artist",
	];
	$out = [];
	foreach ($fields as $field) {
		if ($obj = _acfobj($field)) {
			$out[] = $obj;
		}
	}
	return $out;
}
function get_img($size, $dir = "up")
{
	return _acf("copywrite")["i_1"]["i_1"]["sizes"]["$size"];
}
function formatBytes($size, $precision = 2)
{
	$base = log($size, 1024);
	$suffixes = ["", "KB", "MB", "GB", "TB"];
	return round(pow(1024, $base - floor($base)), $precision) .
		" " .
		$suffixes[floor($base)];
}
function dateFormat($str, $before = "Y-m-d H:i:s", $after = "Y. m. d")
{
	$date = DateTime::createFromFormat($before, $str);
	return $date->format($after);
}
function post_ids($post)
{
	return $post->ID;
}
function customSetPostViews()
{
	$postID = get_the_ID();
	$countKey = "views";
	$count = get_post_meta($postID, $countKey, true);
	if ($count == "") {
		$count = 0;
		delete_post_meta($postID, $countKey);
		add_post_meta($postID, $countKey, "1");
	} else {
		update_post_meta($postID, $countKey, ++$count);
	}
}

/**
 * Get the post views
 * @param int $postID
 */
function get_post_type_label($postID)
{
	$post = get_post($postID);
	$post_type = get_post_type_object($post->post_type);
	// if no label, set it "게시물"
	$out = $post_type->label ?? "";
	return $out;
}

function sendMail($to = "", $subject = "", $message = "")
{
	wp_mail(
		$to,
		$subject,
		$message,
		"From : 뮤지엄한미 <noreply@museumhanmi.or.kr>"
	);
}

function _bot_detected()
{
	return isset($_SERVER["HTTP_USER_AGENT"]) &&
		preg_match(
			"/bot|crawl|slurp|spider|mediapartners/i",
			$_SERVER["HTTP_USER_AGENT"]
		);
}

add_action("wp_login_failed", "my_front_end_login_fail"); // hook failed login

function my_front_end_login_fail($username)
{
	$referrer = $_SERVER["HTTP_REFERER"]; // where did the post submission come from?
	// if there's a valid referrer, and it's not the default log-in screen
	if (
		!empty($referrer) &&
		!strstr($referrer, "wp-login") &&
		!strstr($referrer, "wp-admin")
	) {
		// redirect_to 파라미터 유지
		$redirect_to = isset($_POST['redirect_to']) ? urlencode($_POST['redirect_to']) : '';
		$redirect_param = $redirect_to ? '&redirect_to=' . $redirect_to : '';
		wp_redirect("/login?login=failed" . $redirect_param); // let's append some information (login=failed) to the URL for the theme to use
		exit();
	}
}
function map_artist($arr)
{
	function map_($arr_)
	{
		return $arr_->name;
	}
	$map = array_map("map_", $arr);
	return implode(",", $map);
}

function get_start_end_format($post)
{
	$start = _acf("start", $post);
	$end = _acf("end", $post);
	$form =
		dateFormat($start, "Y.m.d", "Y. m. d. ") .
			yoil($start) .
			" ~ " .
			dateFormat($end, "Y.m.d", "Y. m. d. ") .
			yoil($end) ??
		"";
	return $form;
}
function matchEmailCert()
{
	session_start();
	$pmail = $_POST["email"] ?? $_POST["email_id"] . "@" . $_POST["email_addr"];
	$mail = isset($_SESSION["email"]);
	if ($_POST["cert_numb"]) {
		$_SESSION["input_cert_numb"] = $_POST["cert_numb"];
	}
	$certi =
		$_SESSION["cert_numb"] == $_POST["cert_numb"] ||
		$_SESSION["cert_numb"] == $_SESSION["input_cert_numb"];
	return $mail && $certi;
}

//wpforms
function wpf_dev_frontend_confirmation_message(
	$message,
	$form_data,
	$fields,
	$entry_id
) {
	// also it is possible to access the first, middle, and the last name as follows inplace of [ 'value' ]
	//  $contact_name = $fields[ '0' ][ 'first' ]; - this will pass in the first name
	//  $contact_name = $fields[ '0' ][ 'last' ]; - this will pass in the last name
	//  $contact_name = $fields[ '0' ][ 'middle' ]; - this will pass in the middle name in the format First Middle Last

	// Get the name field ID '0' to set the name for the message
	$contact_name = $fields["0"]["value"];

	// Add the name to the message
	$message .= "
	<div class='col'>
		<a href='/' class='button' style='margin-top:1.5rem'>메인으로 돌아가기</a>
	</div>
	";
	return $message;
}
add_filter(
	"wpforms_frontend_confirmation_message",
	"wpf_dev_frontend_confirmation_message",
	10,
	4
);

//woocommerce
function mytheme_add_woocommerce_support()
{
	add_theme_support("woocommerce");
}
add_action("after_setup_theme", "mytheme_add_woocommerce_support");

/**
 * WooCommerce 세션 쿠키 경고(Undefined array key 3) 해결을 위한 필터
 * 쿠키가 || 로 나뉘었을 때 4개 미만의 파트만 있으면 강제로 제거하여 에러 방지
 * plugins_loaded 훅을 사용하여 WooCommerce가 로드되기 전에 실행
 */
add_action('plugins_loaded', function() {
    if (isset($_COOKIE) && is_array($_COOKIE)) {
        foreach ($_COOKIE as $key => $value) {
            if (is_string($key) && strpos($key, 'wp_woocommerce_session_') === 0 && is_string($value)) {
                $cookie_parts = explode('||', $value);
                if (count($cookie_parts) < 4) {
                    // 잘못된 형식의 쿠키 제거
                    $cookie_path = defined('COOKIEPATH') ? COOKIEPATH : '/';
                    $cookie_domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
                    setcookie($key, '', time() - 3600, $cookie_path, $cookie_domain);
                    if (defined('SITECOOKIEPATH') && SITECOOKIEPATH !== $cookie_path) {
                        setcookie($key, '', time() - 3600, SITECOOKIEPATH, $cookie_domain);
                    }
                    unset($_COOKIE[$key]);
                }
            }
        }
    }
}, 1);

add_filter("product_type_options", function ($options) {
	// remove "Virtual" checkbox
	// if( isset( $options[ 'virtual' ] ) ) {
	// 	unset( $options[ 'virtual' ] );
	// }
	// remove "Downloadable" checkbox
	if (isset($options["downloadable"])) {
		unset($options["downloadable"]);
	}
	return $options;
});
add_filter("cfw_get_billing_checkout_fields", "remove_checkout_fields", 100);

function remove_checkout_fields($fields)
{
	unset($fields["billing_company"]);
	unset($fields["billing_city"]);
	unset($fields["billing_postcode"]);
	unset($fields["billing_country"]);
	unset($fields["billing_state"]);
	unset($fields["billing_address_1"]);
	unset($fields["billing_address_2"]);
	return $fields;
}

// Set billing address fields to not required
add_filter("woocommerce_checkout_fields", "unrequire_checkout_fields");

function unrequire_checkout_fields($fields)
{
	$fields["billing"]["billing_company"]["required"] = false;
	$fields["billing"]["billing_city"]["required"] = false;
	$fields["billing"]["billing_postcode"]["required"] = false;
	$fields["billing"]["billing_country"]["required"] = false;
	$fields["billing"]["billing_state"]["required"] = false;
	$fields["billing"]["billing_address_1"]["required"] = false;
	$fields["billing"]["billing_address_2"]["required"] = false;
	return $fields;
}

add_action("woocommerce_add_cart_item_data", "woocommerce_clear_cart_url");

function woocommerce_clear_cart_url()
{
	global $woocommerce;
	$woocommerce->cart->empty_cart();
}

// remove menu link and add payment history tab
add_filter(
	"woocommerce_account_menu_items",
	"misha_remove_my_account_dashboard",
	10,
	1
);
function misha_remove_my_account_dashboard($menu_links)
{
	$trans = [
		"subscriptions" => "멤버쉽",
		"edit-account" => "계정 관리",
	];
	foreach ($trans as $key => $value) {
		if (isset($menu_links[$key])) {
			$menu_links[$key] = $value;
		}
	}
	
	// 결제내역 탭을 orders 다음에 추가
	$new_menu = [];
	$added = false;
	foreach ($menu_links as $k => $v) {
		$new_menu[$k] = $v;
		// orders 다음에 결제내역 추가
		if ($k === 'orders' && !$added) {
			$new_menu['payment-history'] = '결제내역';
			$added = true;
		}
	}
	// orders가 없으면 맨 뒤에 추가
	if (!$added) {
		$new_menu['payment-history'] = '결제내역';
	}
	
	unset($new_menu["dashboard"]);
	return $new_menu;
}
// perform a redirect
add_action("template_redirect", "misha_redirect_to_orders_from_dashboard");
function misha_redirect_to_orders_from_dashboard()
{
	// is_account_page is exists
	if (function_exists("is_account_page")) {
		// if it is account page and there is no endpoint
		if (is_account_page() && empty(WC()->query->get_current_endpoint())) {
			wp_safe_redirect(wc_get_account_endpoint_url("orders"));
			exit();
		}
	}
}

// ACF location 규칙에 페이지 슬러그 지원 추가
add_filter('acf/location/rule_match/page', 'acf_location_rule_match_page_slug', 10, 3);
function acf_location_rule_match_page_slug($match, $rule, $options) {
	// 페이지 슬러그로 매칭 시도
	if (isset($options['post_id'])) {
		$post = get_post($options['post_id']);
		if ($post && $post->post_type === 'page') {
			$page_slug = $post->post_name;
			if ($rule['operator'] === '==') {
				$match = ($page_slug === $rule['value']);
			} elseif ($rule['operator'] === '!=') {
				$match = ($page_slug !== $rule['value']);
			}
		}
	}
	return $match;
}

// 커스텀 라우팅: /order, /payment-detail
add_action('template_redirect', 'custom_page_routing', 1);
function custom_page_routing() {
	// REQUEST_URI에서 경로 추출
	$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	
	// 쿼리 스트링 제거 및 경로 정규화
	$path = parse_url($request_uri, PHP_URL_PATH);
	$path = rtrim($path, '/'); // 끝의 슬래시 제거
	
	// 경로 매핑
	$routes = [
		'/order' => 'page-order.php',
		'/payment-approval' => 'page-payment-approval.php',
		'/payment-close' => 'page-payment-close.php',
		'/payment-detail' => 'page-payment-detail.php',
	];
	
	// 매칭되는 경로 확인
	if (isset($routes[$path])) {
		$page_file = $routes[$path];
		$page_path = get_stylesheet_directory() . '/pages/' . $page_file;
		
		if (file_exists($page_path)) {
			require $page_path;
			exit;
		}
	}
}


// 커스텀 페이지 rewrite rule 추가
add_action('init', 'add_custom_page_rewrite_rules');
function add_custom_page_rewrite_rules() {
	add_rewrite_rule('^order/?$', 'index.php?custom_page=order', 'top');
	add_rewrite_rule('^payment-approval/?$', 'index.php?custom_page=payment-approval', 'top');
	add_rewrite_rule('^payment-close/?$', 'index.php?custom_page=payment-close', 'top');
	add_rewrite_rule('^payment-detail/?$', 'index.php?custom_page=payment-detail', 'top');
	add_rewrite_rule('^payment-noti/?$', 'index.php?custom_page=payment-noti', 'top');
}

// 커스텀 쿼리 변수 등록
add_filter('query_vars', 'add_custom_page_query_vars');
function add_custom_page_query_vars($vars) {
	$vars[] = 'custom_page';
	return $vars;
}

// 커스텀 페이지 템플릿 로드
add_action('template_redirect', 'load_custom_page_template', 0);
function load_custom_page_template() {
	$custom_page = get_query_var('custom_page');
	if ($custom_page) {
		$page_map = [
			'order' => 'page-order.php',
			'payment-approval' => 'page-payment-approval.php',
			'payment-close' => 'page-payment-close.php',
			'payment-detail' => 'page-payment-detail.php',
			'payment-noti' => 'page-payment-noti.php',
		];
		
		if (isset($page_map[$custom_page])) {
			$page_path = get_stylesheet_directory() . '/pages/' . $page_map[$custom_page];
			if (file_exists($page_path)) {
				require $page_path;
				exit;
			}
		}
	}
}

// WooCommerce account 엔드포인트 등록
add_action('init', 'add_payment_history_endpoint');
function add_payment_history_endpoint() {
	add_rewrite_endpoint('payment-history', EP_ROOT | EP_PAGES);
}

// 엔드포인트 타이틀 설정
add_filter('woocommerce_endpoint_payment-history_title', 'payment_history_endpoint_title', 10, 2);
function payment_history_endpoint_title($title, $endpoint) {
	return '결제내역';
}

include DIR_SRC . "/php/ajax.php";

// Filter out deprecated warnings and specific WooCommerce session warnings from output
add_action("template_redirect", function() {
	ob_start(function($buffer) {
		if ($buffer) {
			// Remove deprecated warnings from HTML output
			$buffer = preg_replace("/<b>Deprecated<\/b>.*?<br \/>/is", "", $buffer);
			$buffer = preg_replace("/Deprecated:.*?on line.*?<br \/>/is", "", $buffer);
			
			// Remove specific WooCommerce session handler warnings (모든 array key 번호 대응)
			$buffer = preg_replace("/<b>Warning<\/b>:.*?Undefined array key \d+.*?class-wc-session-handler\.php.*?<br \/>/is", "", $buffer);
			$buffer = preg_replace("/Warning:.*?Undefined array key \d+.*?class-wc-session-handler\.php.*?on line.*?<br \/>/is", "", $buffer);
			$buffer = preg_replace("/Warning:.*?Undefined array key \d+.*?class-wc-session-handler\.php.*?\n/is", "", $buffer);
			$buffer = preg_replace("/<b>Warning<\/b>:.*?Undefined array key \d+.*?class-wc-session-handler\.php.*?\n/is", "", $buffer);
			// HTML 태그 없이 직접 출력되는 경우
			$buffer = preg_replace("/Warning: Undefined array key \d+ in.*?class-wc-session-handler\.php.*?on line \d+.*?\n?/is", "", $buffer);
		}
		return $buffer;
	});
}, 1);

add_action("shutdown", function() {
	if (ob_get_level()) {
		ob_end_flush();
	}
}, 99999);
include DIR_SRC . "/php/acf_fields.php";
include DIR_SRC . "/php/post_types.php";
include DIR_SRC . "/php/taxonomies.php";
include DIR_SRC . "/php/refund_calculator.php";

// 관리자 메뉴 "프로그램 신청자현황" 추가
add_action('admin_menu', 'add_program_applicants_menu');
function add_program_applicants_menu() {
    add_menu_page(
        '프로그램 신청자현황',
        '프로그램 신청자현황',
        'manage_options',
        'program-applicants',
        'display_program_applicants_page',
        'dashicons-groups',
        30
    );
}

// 프로그램 신청자 현황 페이지에서 WordPress 알림 제거
add_action('admin_head', 'remove_admin_notices_on_program_applicants');
function remove_admin_notices_on_program_applicants() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'toplevel_page_program-applicants') {
        // 모든 admin_notices 액션 제거
        remove_all_actions('admin_notices');
    }
}

// 프로그램 신청자 현황 페이지 콜백 함수
function display_program_applicants_page() {
    require_once(get_stylesheet_directory() . '/admin/program-applicants.php');
}

// 관리자 주문 목록 개선: 커스텀 컬럼 추가
add_filter('manage_post_order_posts_columns', 'add_order_admin_columns');
function add_order_admin_columns($columns) {
    // 기존 컬럼 재정렬
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = '주문 제목';
    $new_columns['order_mbr_ref_no'] = '주문번호';
    $new_columns['order_buyer'] = '주문자';
    $new_columns['order_amount'] = '결제금액';
    $new_columns['order_status'] = '주문 상태';
    $new_columns['order_program'] = '프로그램';
    $new_columns['date'] = '주문일자';
    
    return $new_columns;
}

// 관리자 주문 목록: 커스텀 컬럼 내용 출력
add_action('manage_post_order_posts_custom_column', 'display_order_admin_column_content', 10, 2);
function display_order_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'order_mbr_ref_no':
            $mbr_ref_no = get_field('order_mbr_ref_no', $post_id);
            echo $mbr_ref_no ? esc_html($mbr_ref_no) : '—';
            break;
            
        case 'order_buyer':
            $buyer_name = get_field('order_buyer_name', $post_id);
            $buyer_email = get_field('order_buyer_email', $post_id);
            if ($buyer_name) {
                echo esc_html($buyer_name);
                if ($buyer_email) {
                    echo '<br><small style="color: #666;">' . esc_html($buyer_email) . '</small>';
                }
            } else {
                echo '—';
            }
            break;
            
        case 'order_amount':
            $amount = get_field('order_amount', $post_id);
            $quantity = get_field('order_quantity', $post_id) ?: 1;
            if ($amount !== null && $amount !== '') {
                echo number_format(intval($amount)) . '원';
                if ($quantity > 1) {
                    echo '<br><small style="color: #666;">수량: ' . $quantity . '</small>';
                }
            } else {
                echo '—';
            }
            break;
            
        case 'order_status':
            $status = get_field('order_status', $post_id);
            $status_labels = array(
                'success' => '결제 완료',
                'cancelled' => '취소됨',
                'refunded' => '환불됨',
                'pending' => '대기중'
            );
            $status_label = $status_labels[$status] ?? $status ?? '—';
            $status_colors = array(
                'success' => '#28a745',
                'cancelled' => '#dc3545',
                'refunded' => '#ffc107',
                'pending' => '#6c757d'
            );
            $status_color = $status_colors[$status] ?? '#6c757d';
            echo '<span style="color: ' . esc_attr($status_color) . '; font-weight: 700;">' . esc_html($status_label) . '</span>';
            break;
            
        case 'order_program':
            $program_id = get_field('order_program_id', $post_id);
            if ($program_id) {
                $program_title = get_the_title($program_id);
                if ($program_title) {
                    echo '<a href="' . esc_url(get_edit_post_link($program_id)) . '">' . esc_html($program_title) . '</a>';
                } else {
                    echo '—';
                }
            } else {
                echo '—';
            }
            break;
    }
}

// 관리자 주문 목록: 정렬 가능한 컬럼 설정
add_filter('manage_edit-post_order_sortable_columns', 'make_order_columns_sortable');
function make_order_columns_sortable($columns) {
    $columns['order_mbr_ref_no'] = 'order_mbr_ref_no';
    $columns['order_amount'] = 'order_amount';
    $columns['order_status'] = 'order_status';
    $columns['date'] = 'date';
    return $columns;
}

// 관리자 주문 목록: 주문 상태 필터 추가
add_action('restrict_manage_posts', 'add_order_status_filter');
function add_order_status_filter() {
    global $typenow;
    
    if ($typenow === 'post_order') {
        $selected = isset($_GET['order_status_filter']) ? $_GET['order_status_filter'] : '';
        
        $statuses = array(
            '' => '모든 상태',
            'success' => '결제 완료',
            'cancelled' => '취소됨',
            'refunded' => '환불됨',
            'refund_requested' => '환불 요청',
            'pending' => '대기중'
        );
        
        echo '<select name="order_status_filter">';
        foreach ($statuses as $value => $label) {
            printf(
                '<option value="%s"%s>%s</option>',
                esc_attr($value),
                selected($selected, $value, false),
                esc_html($label)
            );
        }
        echo '</select>';
    }
}

// 관리자 주문 목록: 필터 쿼리 적용
add_filter('parse_query', 'filter_orders_by_status');
function filter_orders_by_status($query) {
    global $pagenow, $typenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'post_order' && isset($_GET['order_status_filter']) && $_GET['order_status_filter'] !== '') {
        $query->query_vars['meta_key'] = 'order_status';
        $query->query_vars['meta_value'] = sanitize_text_field($_GET['order_status_filter']);
    }
}
