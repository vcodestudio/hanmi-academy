<?php
/**
 * 404 pages
 *
 * @package WordPress
 * @subpackage Vanilla
 * @since Vanilla 1.0
 */
get_header();
if (session_status() === PHP_SESSION_NONE) { @session_start(); }
?>
<div class="page-wrap row middle center">
    <?php
        if(isset($_SESSION["id"])):
    ?>
        <h2>
            회원가입이 완료되었습니다.
        </h2>
        <p>
            <b><?= $_SESSION["id"] ?></b>로 로그인을 시도해주세요.
        </p>
        <br />
        <div class="row gap-1r">
            <a class="button" href="/login">
                로그인하기
            </a>
            <a class="button w" href="/">
                메인페이지로 돌아가기
            </a>
        </div>
    <?php
        else:
    ?>
        <h2>
            정상적이지 않은 접근입니다. 다시 시도해주세요.
        </h2>
        <br />
        <p>
            <a class="button" href="/">
                메인페이지로 돌아가기
            </a>
        </p>
    <?php
        endif;
    ?>
</div>
<?php
get_footer();