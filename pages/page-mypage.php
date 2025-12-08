<?php
get_header();

// 로그인 체크
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}
?>
<div class="row gap-24 page-wrap">
<?php
// Ultimate Member account 템플릿 사용
$account_template_path = get_stylesheet_directory() . '/ultimate-member/templates/account.php';
if (file_exists($account_template_path)) {
    require $account_template_path;
} else {
    // 폴백: 기본 계정 정보 표시
    $user = wp_get_current_user();
    ?>
    <div class="row gap-32 page-wrap">
        <h1 class="title">마이페이지</h1>
        <div class="row gap-24">
            <p>안녕하세요, <?= esc_html($user->display_name) ?>님</p>
            <p>계정 정보를 표시할 수 없습니다.</p>
        </div>
    </div>
<?php
}
?>
</div>
<?php
get_footer();
?>

