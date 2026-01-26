<?php
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        //check,fail back
        //진행상황별 session기록하기. 각 페이지별로 따로 지정해야할 듯 함.
        //왠만하면 a tag 로
        // $checks = [
        //     "type"=>[$_SESSION["type"] ?? false, "account-create"],
        //     "age"=>[$_SESSION["age"] ?? false, "account-create-select"],
        //     "agree"=>[($_SESSION["account_agree"] == "1,2"), "account-create-agree"],
        //     "cert"=>[$_SESSION[""],"account-create-certification"],
        //     "init"=>[$_SESSION[""],"account-create-init"],
        //     "success"=>[$_SESSION[""],"account-create-success"],
        // ];
?>
<div class="page-wrap row gap-32 v-init">
    <div class="row gap-12 single-line">
        <h3><?= get_the_title() ?></h3>
        <p>
        회원가입을 환영합니다. 원하는 회원 유형을 선택해주세요.
        </p>
    </div>