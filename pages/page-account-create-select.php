<?php
get_header();
echo temp("account-header");
if (session_status() === PHP_SESSION_NONE) { @session_start(); }
if(isset($_POST["age"])) {
    $_SESSION["age"] = $_POST["age"];
    wp_redirect(getPage("account-create-agreement")->permalink);
}
?>
<div class="col-3 gap-24">
    <form class="row gap-24 pannel card" method="POST">
        <input type="hidden" value="adult" name="age"/>
        <div class="row gap-12">
            <h5>일반회원 14세 이상 내국인</h5>
            <p class="fade">
                웹회원은 뮤지엄한미 방문 예약 및 프로그램을 신청하실 수 있으며, 전시, 프로그램 등 미술관 소식을 받아보실 수 있습니다.
            </p>
        </div>
        <div class="flex bottom h_fit">
            <p>
                <button class="w" type="submit">
                    가입하기
                </button>
            </p>
        </div>
    </form>
    <form class="row gap-24 pannel card" method="POST">
        <input type="hidden" value="child" name="age"/>
        <div class="row gap-12">
            <h5>14세 미만 내국인 어린이</h5>
            <p class="fade">
                어린이 회원은 부모의 동의 후 가입 가능합니다.
            </p>
        </div>
        <div class="flex bottom h_fit">
            <p>
                <button class="w" type="submit">
                    가입하기
                </button>
            </p>
        </div>
    </form>
<!-- 
    <div class="row gap-24 pannel card">
        <div class="row gap-12">
            <h5>외국인 등록 번호 소지자</h5>
            <p class="fade">
                외국 국적을 가진 자 중 외국인 등록번호를 소지하지 않으신 분은 가입할 수 없습니다.
            </p>
        </div>
        <div class="flex bottom h_fit">
            <p>
                <a href="/account-create-agreement" class="button w">
                    가입하기
                </a>
            </p>
        </div>
    </div> -->
</div>
<?php
echo temp("account-footer");
get_footer();
?>