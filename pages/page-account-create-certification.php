<?php
get_header();
session_start();
$row_class="field col gap-24 p-gap-8";
$col_class="field-input row gap-1r non-stretch";
$item_class="field-item flex gap-1r middle";
$checks = [
    "cert_type"=>$_POST["cert_type"] ?? false,
    "user_name"=>$_POST["user_name"] ?? false,
    "birth"=>$_POST["birth"] ?? false,
    "cert_numb"=>(isset($_SESSION["cert_numb"]) && !empty($_SESSION["cert_numb"]) && ($_SESSION["cert_numb"] ?? "0") == ($_POST["cert_numb"] ?? "post-no")),
];
$cert = true;
function alertMsg($check = "",$msg = "") {
    global $checks;
    if($checks["cert_type"] && !$checks[$check]):
    echo comp("alert",["label"=>$msg]);
    endif;
}
foreach($checks as $key=>$check) {
    if($check != false && isset($_POST[$key])) {
        $_SESSION[$key] = $_POST[$key];
    } else {
        $cert = false;
    }
}
if($cert) {
    // var_dump($_SESSION);
    wp_redirect(getPage("account-create-init")->permalink);
}
?>
<div class="page-wrap forms row gap-32 tabs">
    <h3>가입인증</h3>
    <div class="row gap-16">
        <div>
            <?= comp('radios',['item'=>[
            ['label'=>'이메일','value'=>'1'],
            // ['label'=>'휴대폰','value'=>'2'],
            // ['label'=>'아이핀(I-PIN)','value'=>'3'],
        ],'id'=>'rg',"change"=>"changeTab(e.currentTarget.value - 1)"]) ?>
        </div>
        <p>
            아이디/비밀번호 분실 등 본인 여부 확인이 필요할 경우를 위해 신분증에 기재된 이름, 생년월일과 휴대폰번호 또는 이메일 주소를 입력해 주세요. 휴대폰, 또는 이메일로 인증번호를 받으실 수
            있습니다. (동일 휴대폰 번호, 이메일 주소로 1개 아이디 가입이 가능합니다.) 이름, 생년월일은 가입 이후에 수정할 수 없습니다.
        </p>
    </div>
    <form method="post" class="row gap-24 tabs-tab active">
        <input type="hidden" name="cert_type" value="email" />
        <div class="<?= $row_class ?>">
            <h6>이름</h6>
            <div class="<?= $col_class ?>">
                <input type="text" name="user_name" placeholder="이름" value="<?= $_POST["user_name"] ?? "" ?>"/>
                <?php
                    alertMsg("user_name","올바른 이름을 입력해주세요.");
                ?>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>생년월일</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?>">
                    <?= comp("number",["name"=>"birth","attr"=>["value"=>$_POST["birth"] ?? ""]]) ?>
                </div>
                <p class="caption">숫자만 입력해주세요. (예 : <?= date("Ymd") ?>)</p>
                <?php
                    alertMsg("birth","생년월일을 정확히 입력해주세요.");
                ?>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>이메일</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?> email">
                    <input type="text" name="email_id" id="email_id" placeholder="아이디" />
                    @
                    <input type="text" name="email_addr" id="email_addr" placeholder="메일주소">
                    <button type="button" class="w" click="
                    console.log(`${email_id.value}@${email_addr.value}`);
                    $.post(ajaxurl,{action:'createEmailCert',email:`${email_id.value}@${email_addr.value}`})
                    .done(res=>{email_caption.innerHTML = res.data;console.log(res)})
                    .fail(res=>email_caption.innerHTML = `인증번호 발송에 실패하였습니다.`)
                    ">인증번호 전송</button>
                </div>
                <p class="caption" id="email_caption"></p>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>인증번호</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?>">
                    <?= comp("number",["name"=>"cert_numb","attr"=>["placeholder"=>"6자리 번호입력"]]) ?>
                </div>
                <?=
                    alertMsg("cert_numb","인증번호가 올바르지 않습니다.");
                ?>
            </div>
        </div>
        <hr />
        <div class="flex end">
            <button type="submit">
                이메일 인증하기
            </button>
        </div>
    </form>
    <form class="row gap-24 tabs-tab">
        <div class="<?= $row_class ?>">
            <h6>이름</h6>
            <div>
                <?= comp("text") ?>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>생년월일</h6>
            <div>
                <?= comp("text") ?>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>휴대폰</h6>
            <div>
                <?= comp("text") ?>
            </div>
        </div>
        <hr />
        <div class="flex end">
            <a href="#" class="button">
                휴대폰 인증하기
            </a>
        </div>
    </form>
    <form class="row gap-24 tabs-tab">
        <hr />
        <div class="flex end">
            <a href="#" class="button">
                아이핀 인증하기
            </a>
        </div>
    </form>
</div>
<?php
get_footer();
?>