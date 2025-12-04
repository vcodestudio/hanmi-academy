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

function _acf($str, $id = 0)
{
    if (!$id) {
        $id = get_post();
    }
    return get_field($str, $id);
}
function _acfobj($str, $id = 0)
{
    if (!$id) {
        $id = get_post();
    }
    if (
        ($obj = get_field_object($str, $id)) &&
        ((function_exists("user_level") &&
            ((int) $obj["instructions"] ?? 0) <= user_level()) ||
            !function_exists("user_level"))
    ) {
        return $obj;
    } else {
        return false;
    }
}

function img($obj = [], $size = "large", $empty = [])
{
    $id = 0;
    if ((empty($obj) || !$obj) && !empty($empty)) {
        $obj = $empty;
    }
    switch (gettype($obj)) {
        case "integer":
            $id = $obj;
            break;
        case "array":
            $id = $obj["id"];
            break;
    }
    $img = wp_get_attachment_image_url($id, $size);
    if ($img) {
        return comp("img", ["id" => $id, "size" => $size]);
    } else {
         ?>
			<img src="<?= getImg("empty.svg") ?>" class="empty" />
		<?php
    }
}
function getImg($str = "error.svg")
{
    $path = DIR_SRC . "/imgs/system/$str";
    if (file_exists($path)) {
        $path = SRC . "/imgs/system/$str";
    } else {
        $path = SRC_MODULE . "/imgs/system/error.svg";
    }
    return $path;
}
function icon($str, $class = "")
{
    $url = DIR_MODULE . "/imgs/icons/$str.svg";
    $url = file_exists($url)
        ? SRC_MODULE . "/imgs/icons/$str.svg"
        : SRC_MODULE . "/imgs/system/empty.svg";
    return "<img class='$class' src='$url'/>";
}
function comp($name = "", $arg = [])
{
    $html = "";
    if (($path = DIR_MODULE . "/components/$name.php") && file_exists($path)):
        ob_start();
        require $path;
        $html = ob_get_contents();
        ob_end_clean();
    endif;
    return $html;
}
function temp($name = "", $arg = [])
{
    $html = "";
    if (($path = DIR_MODULE . "/templates/$name.php") && file_exists($path)):
        ob_start();
        extract($arg);
        require $path;
        $html = ob_get_contents();
        ob_end_clean();
    endif;
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
?>
