<?php
    /**
 * Template for the login form
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/login.php
 *
 * Page: "Login"
 *
 * @version 2.6.1
 *
 * @var string $mode
 * @var int    $form_id
 * @var array  $args
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<style>
    /* 페이지 콘텐츠 숨기기 (123 등 불필요한 텍스트) */
    .page-wrap > .title,
    .page-wrap > .row.gap-24 > p:first-child {
        display: none !important;
    }
    /* 로그인 페이지 레이아웃 */
    .page-wrap > .row.gap-24 > form.flex.center {
        padding: 0 !important;
        margin: 0 !important;
    }
    .page-wrap > .row.gap-24 > form.flex.center > .user-login {
        padding: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        background: transparent !important;
    }
    .page-wrap > .row.gap-24 > form.flex.center > .user-login > .inner {
        max-width: 100% !important;
        width: 100% !important;
    }
    /* 닫기 버튼 숨기기 (로그인 페이지에서만) */
    .page-wrap > .row.gap-24 > form.flex.center .s-col-2 > .flex.right {
        display: none !important;
    }
    /* 모바일 레이아웃 개선 */
    @media (max-width: 765px) {
        .page-wrap > .row.gap-24 > form.flex.center > .user-login > .inner {
            gap: 20px !important;
        }
        .page-wrap > .row.gap-24 > form.flex.center .s-col-2 {
            flex-direction: column !important;
            gap: 8px !important;
        }
        .page-wrap > .row.gap-24 > form.flex.center h3 {
            text-align: center;
        }
        .page-wrap > .row.gap-24 > form.flex.center .flex.gap-1r {
            flex-direction: column !important;
            gap: 16px !important;
            align-items: center !important;
        }
        .page-wrap > .row.gap-24 > form.flex.center .flex.gap-1r > .flex-auto,
        .page-wrap > .row.gap-24 > form.flex.center .flex.gap-1r > .flex-none {
            width: 100%;
            justify-content: center !important;
        }
        .page-wrap > .row.gap-24 > form.flex.center .divider {
            justify-content: center !important;
        }
        .page-wrap > .row.gap-24 > form.flex.center .row.gap-1r .gap-8.row p.bold {
            text-align: left;
        }
        .page-wrap > .row.gap-24 > form.flex.center input[type="text"],
        .page-wrap > .row.gap-24 > form.flex.center input[type="password"] {
            width: 100%;
        }
        .page-wrap > .row.gap-24 > form.flex.center .button-primary {
            width: 100%;
        }
    }
</style>
<?php
    if( isset( $_GET['updated'] ) && 'password_changed' === sanitize_key( $_GET['updated'] ) ):
?>
<div class="row gap-32">
    <div class="um-field um-field-block um-field-type_block">
        <div class="um-field-block">
            <div style="text-align:center;">
                <?php esc_html_e( '성공적으로 비밀번호가 변경되었습니다.', 'ultimate-member' ); ?>
            </div>
        </div>
    </div>
    <div class="flex center">
        <a href="/" class="button">
            메인페이지로 바로가기
        </a>
    </div>
</div>
<?php
    else:
?>
        <form action="/wp-login.php" method="post" class="flex center">
            <?= temp("user-login") ?>
        </form>
<?php
    endif;
?>