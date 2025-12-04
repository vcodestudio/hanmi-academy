<?php
// require_once get_stylesheet_directory()."/src/hanmi-components/php/precomp.php";
include_once "src/hanmi-components/php/precomp.php";

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
		wp_redirect("/login?login=failed"); // let's append some information (login=failed) to the URL for the theme to use
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
add_action('template_redirect', 'custom_page_routing');
function custom_page_routing() {
	$request_uri = $_SERVER['REQUEST_URI'];
	$request_uri = strtok($request_uri, '?'); // 쿼리 스트링 제거
	
	if ($request_uri === '/order' || $request_uri === '/order/') {
		$page_path = get_stylesheet_directory() . '/pages/page-order.php';
		if (file_exists($page_path)) {
			require $page_path;
			exit;
		}
	}
	
	if ($request_uri === '/payment-detail' || $request_uri === '/payment-detail/') {
		$page_path = get_stylesheet_directory() . '/pages/page-payment-detail.php';
		if (file_exists($page_path)) {
			require $page_path;
			exit;
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

// Filter out deprecated warnings from output
add_action("template_redirect", function() {
	ob_start(function($buffer) {
		if ($buffer) {
			// Remove deprecated warnings from HTML output
			$buffer = preg_replace("/<b>Deprecated<\/b>.*?<br \/>/is", "", $buffer);
			$buffer = preg_replace("/Deprecated:.*?on line.*?<br \/>/is", "", $buffer);
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
