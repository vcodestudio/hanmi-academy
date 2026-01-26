<?php
// date_default_timezone_set('Asia/Seoul');
define("URI", get_stylesheet_directory_uri());
define("SRC", URI . "/src");
define("SRC_MODULE", URI . "/src/hanmi-components");
define("DIR", get_stylesheet_directory());
define("DIR_SRC", DIR . "/src");
define("DIR_MODULE", DIR . "/src/hanmi-components");

add_filter("show_admin_bar", "__return_false");
if (file_exists(DIR_MODULE . "/php/admin.php")) {
    include DIR_MODULE . "/php/admin.php";
}

if (!is_admin()) {
    if (file_exists(DIR_SRC . "/js/dist/app.css")) {
        wp_enqueue_style(
            "global-css",
            SRC . "/js/dist/app.css",
            [],
            filemtime(DIR_SRC . "/js/dist/app.css")
        );
    }
    if (file_exists(DIR_SRC . "/js/dist/app.js")) {
        wp_enqueue_script(
            "app-js",
            SRC . "/js/dist/app.js",
            [],
            filemtime(DIR_SRC . "/js/dist/app.js")
        );
    }
}
class HM
{
    static $logo = SRC . "/imgs/logo/typo.svg";
    static $logo_w = SRC . "/imgs/logo/typo_w.svg";
    static $symbol = SRC . "/imgs/logo/symbol.svg";
    static $lab_logo = SRC . "/imgs/logo/symbol.svg";

    // static function. can call MH::getLogo();
    static function getLogo($str)
    {
        $path = DIR_SRC . "/imgs/logo/$str.svg";
        if (file_exists($path)) {
            $path = SRC . "/imgs/logo/$str.svg";
        } else {
            $path = SRC_MODULE . "/imgs/logo/error.svg";
        }
        return $path;
    }
}

/**
 * ACF 필드 값 가져오기 (캐싱 적용)
 * @param string $str 필드 이름
 * @param int|WP_Post $id 포스트 ID 또는 객체
 * @return mixed 필드 값
 */
function _acf($str, $id = 0)
{
    static $cache = [];
    
    if (!$id) {
        $id = get_post();
    }
    
    // 포스트 객체인 경우 ID 추출
    $post_id = is_object($id) ? $id->ID : $id;
    $cache_key = $post_id . '_' . $str;
    
    // 캐시에 있으면 반환 (null 값도 캐싱)
    if (array_key_exists($cache_key, $cache)) {
        return $cache[$cache_key];
    }
    
    $value = get_field($str, $id);
    $cache[$cache_key] = $value;
    
    return $value;
}

/**
 * ACF 필드 객체 가져오기 (캐싱 적용)
 * @param string $str 필드 이름
 * @param int|WP_Post $id 포스트 ID 또는 객체
 * @return array|false 필드 객체 또는 false
 */
function _acfobj($str, $id = 0)
{
    static $cache = [];
    
    if (!$id) {
        $id = get_post();
    }
    
    // 포스트 객체인 경우 ID 추출
    $post_id = is_object($id) ? $id->ID : $id;
    $cache_key = $post_id . '_obj_' . $str;
    
    // 캐시에 있으면 반환
    if (array_key_exists($cache_key, $cache)) {
        return $cache[$cache_key];
    }
    
    $result = false;
    if (
        ($obj = get_field_object($str, $id)) &&
        ((function_exists("user_level") &&
            ((int) $obj["instructions"] ?? 0) <= user_level()) ||
            !function_exists("user_level"))
    ) {
        $result = $obj;
    }
    
    $cache[$cache_key] = $result;
    return $result;
}

/**
 * 여러 ACF 필드를 한 번에 로드 (성능 최적화)
 * @param array $fields 필드 이름 배열
 * @param int|WP_Post $id 포스트 ID 또는 객체
 * @return array 필드명 => 값 배열
 */
function _acf_batch($fields, $id = 0)
{
    if (!$id) {
        $id = get_post();
    }
    
    $result = [];
    foreach ($fields as $field) {
        $result[$field] = _acf($field, $id);
    }
    
    return $result;
}

/**
 * 이미지 출력 함수 (캐싱 적용)
 * @param array|int $obj 이미지 객체 또는 ID
 * @param string $size 이미지 사이즈
 * @param array $empty 빈 이미지 대체용
 * @return string 이미지 HTML
 */
function img($obj = [], $size = "large", $empty = [])
{
    static $img_cache = [];
    
    $id = 0;
    if ((empty($obj) || !$obj) && !empty($empty)) {
        $obj = $empty;
    }
    switch (gettype($obj)) {
        case "integer":
            $id = $obj;
            break;
        case "array":
            $id = $obj["id"] ?? 0;
            break;
    }
    
    if (!$id) {
        return '<img src="' . getImg("empty.svg") . '" class="empty" />';
    }
    
    $cache_key = $id . '_' . $size;
    
    // 이미지 URL 캐싱
    if (!isset($img_cache[$cache_key])) {
        $img_cache[$cache_key] = wp_get_attachment_image_url($id, $size);
    }
    
    if ($img_cache[$cache_key]) {
        return comp("img", ["id" => $id, "size" => $size]);
    } else {
        return '<img src="' . getImg("empty.svg") . '" class="empty" />';
    }
}

/**
 * 시스템 이미지 경로 가져오기 (캐싱 적용)
 * @param string $str 이미지 파일명
 * @return string 이미지 URL
 */
function getImg($str = "error.svg")
{
    static $cache = [];
    
    if (isset($cache[$str])) {
        return $cache[$str];
    }
    
    $path = DIR_SRC . "/imgs/system/$str";
    if (file_exists($path)) {
        $cache[$str] = SRC . "/imgs/system/$str";
    } else {
        $cache[$str] = SRC_MODULE . "/imgs/system/error.svg";
    }
    
    return $cache[$str];
}

/**
 * 아이콘 출력 함수 (캐싱 적용)
 * @param string $str 아이콘 이름
 * @param string $class CSS 클래스
 * @return string 이미지 태그
 */
function icon($str, $class = "")
{
    static $cache = [];
    
    if (!isset($cache[$str])) {
        $path = DIR_MODULE . "/imgs/icons/$str.svg";
        $cache[$str] = file_exists($path)
            ? SRC_MODULE . "/imgs/icons/$str.svg"
            : SRC_MODULE . "/imgs/system/empty.svg";
    }
    
    return "<img class='$class' src='{$cache[$str]}'/>";
}

/**
 * 컴포넌트 렌더링 함수 (파일 존재 캐싱 적용)
 * @param string $name 컴포넌트 이름
 * @param array $arg 컴포넌트 인자
 * @return string 렌더링된 HTML
 */
function comp($name = "", $arg = [])
{
    static $file_exists_cache = [];
    
    $path = DIR_MODULE . "/components/$name.php";
    
    // 파일 존재 여부 캐싱
    if (!isset($file_exists_cache[$path])) {
        $file_exists_cache[$path] = file_exists($path);
    }
    
    if (!$file_exists_cache[$path]) {
        return "";
    }
    
    ob_start();
    require $path;
    $html = ob_get_contents();
    ob_end_clean();
    
    return $html;
}

/**
 * 템플릿 렌더링 함수 (파일 존재 캐싱 적용)
 * @param string $name 템플릿 이름
 * @param array $arg 템플릿 인자
 * @return string 렌더링된 HTML
 */
function temp($name = "", $arg = [])
{
    static $file_exists_cache = [];
    
    $path = DIR_MODULE . "/templates/$name.php";
    
    // 파일 존재 여부 캐싱
    if (!isset($file_exists_cache[$path])) {
        $file_exists_cache[$path] = file_exists($path);
    }
    
    if (!$file_exists_cache[$path]) {
        return "";
    }
    
    ob_start();
    extract($arg);
    require $path;
    $html = ob_get_contents();
    ob_end_clean();
    
    return $html;
}
function comp_attr_str($arg = [])
{
    $out = [];
    if (isset($arg["attr"])) {
        $arg = $arg["attr"];
    }
    if ($arg):
        foreach ($arg as $key => $value) {
            array_push($out, "$key=\"$value\"");
        }
    endif;
    return implode(" ", $out);
}
function yoil($str = "0000.00.00")
{
    $y = ["일", "월", "화", "수", "목", "금", "토"];
    if (empty($str) || $str === null) {
        return "";
    }
    $str = str_replace(".", "-", $str);
    return $y[date("w", strtotime($str))];
}
function getDateRange()
{
    $s = _acf("start");
    $e = _acf("end");
    $end = $e ? " ~ " . $e . ". " . yoil($e) : "";
    if ($s == $e) {
        $end = "";
    }
    return $s . ". " . yoil($s) . $end;
}
function getDateState($post = null)
{
    if (!isset($post)) {
        $post = get_post();
    }
    if (
        $post &&
        ($start = get_field("start", $post)) &&
        ($end = get_field("end", $post))
    ) {
        return dateState($start, $end);
    } else {
        return null;
    }
}
function changeDateFormat($current = "Ymd", $seperator = "", $to = "Y.m.d")
{
    if(empty($current) || $current === null) {
        return "";
    }
    $current = str_replace($seperator, "", (string)$current);
    if(strlen($current) < 8) {
        return "";
    }
    $current = substr_replace($current, "-", 4, 0);
    $current = substr_replace($current, "-", 7, 0);
    $timestamp = strtotime($current);
    if($timestamp === false) {
        return "";
    }
    return date($to, $timestamp);
}
function dateState($start = "0000.00.00", $end = "0000.00.00")
{
    $start = str_replace(".", "", $start);
    $start_ = str_replace(".", "-", $start);
    $end = str_replace(".", "", $end);
    $end_ = str_replace(".", "-", $end);
    if (!isset($end) || $end == 0) {
        $end = $start;
        $end_ = $start_;
    }
    $cur = date("Ymd");
    $cur_ = date("Y-m-d");
    $start_ = new DateTime($start_);
    $end_ = new DateTime($end_);
    $cur_ = new DateTime($cur_);
    $dobjs = [
        [
            "name" => "예정",
            "slug" => "before",
            "remain" => date_diff($cur_, $start_)->days,
        ],
        [
            "name" => "진행중",
            "slug" => "current",
            "remain" => date_diff($cur_, $end_)->days,
        ],
        [
            "name" => "종료",
            "slug" => "end",
            "remain" => date_diff($end_, $cur_)->days,
        ],
    ];
    $out = $dobjs[1];
    if ($cur < $start) {
        $out = $dobjs[0];
    } elseif ($cur > $end) {
        $out = $dobjs[2];
    }
    if ($start == 0 || $end == 0) {
        $out = null;
    }
    return $out;
}

/**
 * @param $img_object // [...,["sizes"=>["thumbnail"=>"url","medium"=>"url","large"=>"url","full"=>"url"],...],...]
 * @param string $img_size // thumbnail, medium, large, full
 */
function get_img_url($img_object, $img_size = "large")
{
    // search sizes, if large->medium->thumbnail->full based on the order
    $sizes = ["large", "medium", "thumbnail", "full"];
    if (in_array($img_size, $sizes)) {
        try {
            $img_url = $img_object["sizes"][$img_size];
        } catch (Exception $e) {
            $img_url = $img_object["sizes"]["full"];
        }
    }
    return $img_url;
}

/**
 * 문자열 앞뒤의 br 태그와 공백을 제거
 * @param string $str 입력 문자열
 * @return string 정리된 문자열
 */
function trim_br($str)
{
    if (empty($str)) {
        return '';
    }
    // 앞뒤 공백 제거
    $str = trim($str);
    // 앞쪽 br 태그 제거 (br, br/, br /, <br>, <br/>, <br /> 등 모든 형태)
    $str = preg_replace('/^(<br\s*\/?>|\s|&nbsp;)+/i', '', $str);
    // 뒤쪽 br 태그 제거
    $str = preg_replace('/(<br\s*\/?>|\s|&nbsp;)+$/i', '', $str);
    // 최종 trim
    return trim($str);
}
?>
