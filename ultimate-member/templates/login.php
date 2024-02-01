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
<style>
    .page-wrap > .title {
        display:none;
    }
</style>
        <form action="/wp-login.php" method="post" class="flex center">
            <?= temp("user-login") ?>
        </form>
        <?php
    endif;
?>