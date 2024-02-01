<?php
// require_once get_stylesheet_directory()."/src/hanmi-components/php/precomp.php";
include_once "src/hanmi-components/php/precomp.php";

function isValid($a) {
	return !((is_string($a)?empty(trim($a)):empty($a)) || is_null($a) || $a == false);
}

function isValidGroup($arr = []) {
	$out = [];
	array_walk_recursive($arr,function($v,$k) use(&$out) {
		if(isValid($v)) $out[] = $v;
	});
	return !empty($out);
}

function acf_img_set($imgarr,$image_size = "large",$max_width = 640) {
	$image_id = $imgarr["id"] ?? "";
	// check the image ID is not blank
	if($image_id != '') {

		// set the default src image size
		$image_src = wp_get_attachment_image_url( $image_id, $image_size );

		// set the srcset with various image sizes
		$image_srcset = wp_get_attachment_image_srcset( $image_id, $image_size );

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

function getPosts() {
	$p = $_POST;
	$arg = [
		'post_type'=>'post',
		'post_state'=>'publish',
		'tax_query'=>[
		],
		'meta_query'=>[
			'AND',
		],
		's'=>$p['s'],
		'posts_per_page'=>16,
		'paged'=>$p['p']
	];
}
function getPage($slug = "") {
	 $query = get_posts([
		"post_type"=>"page",
		"name"=>$slug,
		"post_status"=>"publish"
	 ]);
	 if($out = $query[0] ?? null) {
		$out->permalink = get_permalink($out);
	 }
	 
	 return $out;
}

function _acfobjs() {
	$fields = ["info","info_download","copywrite","achive","insurance","artist"];
	$out = [];
	foreach($fields as $field) {
		if($obj = _acfobj($field)) {
			$out[] = $obj;
		}
	}
	return $out;
}
function get_img($size,$dir='up') {
	return _acf('copywrite')['i_1']['i_1']['sizes'][$size];
}
function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');   
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}
function dateFormat($str,$before="Y-m-d H:i:s",$after = "Y. m. d") {
	$date = DateTime::createFromFormat($before, $str);
	return $date->format($after);
}
function post_ids($post) {
	return $post->ID;
}
function customSetPostViews() {
	$postID = get_the_ID();
    $countKey = 'views';
    $count = get_post_meta($postID, $countKey, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $countKey);
        add_post_meta($postID, $countKey, '1');
    }else{
        update_post_meta($postID, $countKey, ++$count);
    }
}

function get_post_type_label($post) {
	$type = get_post_type($post);
	global $wp_post_types;
	return $wp_post_types[$type]->labels->singular_name;
}

function sendMail($to = "",$subject="",$message="") {
	wp_mail( $to, $subject, $message ,"From : 뮤지엄한미 <noreply@museumhanmi.or.kr>");
}

function _bot_detected() {
	return (
	  isset($_SERVER['HTTP_USER_AGENT'])
	  && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
	);
  }

  add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login

  function my_front_end_login_fail( $username ) {
	 $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
	 // if there's a valid referrer, and it's not the default log-in screen
	 if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
		wp_redirect( "/login?login=failed" );  // let's append some information (login=failed) to the URL for the theme to use
		exit;
	 }
  }
function map_artist($arr) {
	function map_($arr_) {
		return $arr_->name;
	}
	$map = array_map("map_",$arr);
	return implode(",",$map);
}

//front page
// if(!is_admin() && strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Yeti")) <= 0 ){ // if not Naver
// 	//do something
// 	if((!is_404() && !_bot_detected() && get_the_ID() != getPage("front")->ID) && get_field("front_active","option")):
// 		$skey = "frontgate";
// 		session_start();
// 		if(!isset($_SESSION[$skey])) {
// 			$_SESSION[$skey] = true;
// 			$gate_page = getPage("front");
// 			wp_redirect(get_permalink($gate_page));
// 			exit;
// 		}
// 	endif;
// }
// function getWhatsOn() {
// 	$posts = get_posts([
// 		"post_type"=>["post_exhibition","post_program"],
// 		"post_status"=>"publish",
// 		"meta_query"=>[
// 			'relation'=>"AND",
// 			[
// 				"key"=>"end",
// 				"value"=>date("Y-m-d"),
// 				"compare"=>">=",
// 				"type"=>"DATE"
// 			],
// 			[
// 				"key"=>"permanent",
// 				"value"=>"1",
// 				"compare"=>"!="
// 			]
// 		]
// 	]);
// 	return $posts;
// }
function get_start_end_format($post) {
	$start = _acf("start",$post);
	$end = _acf("end",$post);
	$form = dateFormat($start,"Y.m.d","Y. m. d. ").yoil($start)." ~ ".dateFormat($end,"Y.m.d","Y. m. d. ").yoil($end) ?? "";
	return $form;
}
function matchEmailCert() {
	session_start();
	$pmail = $_POST["email"] ?? $_POST["email_id"]."@".$_POST["email_addr"];
	$mail = isset($_SESSION["email"]);
	if($_POST["cert_numb"]) $_SESSION["input_cert_numb"] = $_POST["cert_numb"];
	$certi = ($_SESSION["cert_numb"] == $_POST["cert_numb"] || $_SESSION["cert_numb"] == $_SESSION["input_cert_numb"]);
	return ($mail && $certi);
}

//wpforms
function wpf_dev_frontend_confirmation_message( $message, $form_data, $fields, $entry_id ) {
 
    // also it is possible to access the first, middle, and the last name as follows inplace of [ 'value' ]
    //  $contact_name = $fields[ '0' ][ 'first' ]; - this will pass in the first name
    //  $contact_name = $fields[ '0' ][ 'last' ]; - this will pass in the last name
    //  $contact_name = $fields[ '0' ][ 'middle' ]; - this will pass in the middle name in the format First Middle Last
          
    // Get the name field ID '0' to set the name for the message
    $contact_name = $fields[ '0' ][ 'value' ];
     
    // Add the name to the message
    $message .= "
	<div class='col'>
		<a href='/' class='button' style='margin-top:1.5rem'>메인으로 돌아가기</a>
	</div>
	";
    return $message;
      
}
add_filter( 'wpforms_frontend_confirmation_message', 'wpf_dev_frontend_confirmation_message', 10, 4 );


//woocommerce
function mytheme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );
add_filter( 'product_type_options', function( $options ) {
	// remove "Virtual" checkbox
	// if( isset( $options[ 'virtual' ] ) ) {
	// 	unset( $options[ 'virtual' ] );
	// }
	// remove "Downloadable" checkbox
	if( isset( $options[ 'downloadable' ] ) ) {
		unset( $options[ 'downloadable' ] );
	}
	return $options;
} );
add_filter( 'cfw_get_billing_checkout_fields', 'remove_checkout_fields', 100 );

function remove_checkout_fields( $fields ) {
	unset( $fields['billing_company'] );
	unset( $fields['billing_city'] );
	unset( $fields['billing_postcode'] );
	unset( $fields['billing_country'] );
	unset( $fields['billing_state'] );
	unset( $fields['billing_address_1'] );
	unset( $fields['billing_address_2'] );
	return $fields;
}

// Set billing address fields to not required
add_filter( 'woocommerce_checkout_fields', 'unrequire_checkout_fields' );

function unrequire_checkout_fields( $fields ) {
	$fields['billing']['billing_company']['required']   = false;
	$fields['billing']['billing_city']['required']      = false;
	$fields['billing']['billing_postcode']['required']  = false;
	$fields['billing']['billing_country']['required']   = false;
	$fields['billing']['billing_state']['required']     = false;
	$fields['billing']['billing_address_1']['required'] = false;
	$fields['billing']['billing_address_2']['required'] = false;
	return $fields;
}

add_action( 'woocommerce_add_cart_item_data', 'woocommerce_clear_cart_url' );

function woocommerce_clear_cart_url() {

    global $woocommerce;
    $woocommerce->cart->empty_cart();
} 

// remove menu link
add_filter( 'woocommerce_account_menu_items', 'misha_remove_my_account_dashboard' );
function misha_remove_my_account_dashboard( $menu_links ){
	$trans = [
		"subscriptions"=>"멤버쉽",
		"edit-account"=>"계정 관리"
	];
	foreach($trans as $key=>$value) {
		$menu_links[$key] = $value;
	}
	unset( $menu_links[ 'dashboard' ] );
	return $menu_links;
	
}
// perform a redirect
add_action( 'template_redirect', 'misha_redirect_to_orders_from_dashboard' );
function misha_redirect_to_orders_from_dashboard(){
	
	if( is_account_page() && empty( WC()->query->get_current_endpoint() ) ){
		wp_safe_redirect( wc_get_account_endpoint_url( 'orders' ) );
		exit;
	}
	
}

include DIR_SRC."/php/ajax.php";

