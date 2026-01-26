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
    /* 모바일 레이아웃 개선 */
    @media (max-width: 765px) {
        .user-login {
            padding: 24px 16px !important;
            width: 100% !important;
            max-width: none !important;
        }
        .user-login .inner {
            gap: 20px !important;
        }
        .user-login .s-col-2 {
            flex-direction: column !important;
            gap: 8px !important;
        }
        .user-login h3 {
            text-align: center;
        }
        .user-login .flex.gap-1r {
            flex-direction: column !important;
            gap: 16px !important;
            align-items: center !important;
        }
        .user-login .flex.gap-1r > .flex-auto,
        .user-login .flex.gap-1r > .flex-none {
            width: 100%;
            justify-content: center !important;
        }
        .user-login .divider {
            justify-content: center !important;
        }
        .user-login .row.gap-1r .gap-8.row p.bold {
            text-align: left;
        }
        .user-login input[type="text"],
        .user-login input[type="password"] {
            width: 100%;
        }
        .user-login .button-primary {
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