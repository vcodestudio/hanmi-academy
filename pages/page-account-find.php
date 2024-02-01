<?php
get_header();
session_start();
$row_class="field col gap-24 p-gap-8";
$col_class="field-input row gap-1r non-stretch";
$item_class="field-item flex gap-1r middle";
$ispass = (($_GET["cert_type"] ?? false) == "password");
?>
<div class="page-wrap forms row gap-32 tabs">
    <h3><?= get_the_title() ?></h3>
    <div>
    </div>
    <div class="radio_group">
        <a class="w <?= $ispass?'':'checked' ?>" href="<?= getPage("account-find")->permalink ?>">아이디 찾기</a>
        <a class="w <?= $ispass?'checked':'' ?>"
            href="<?= getPage("account-find")->permalink ?>?cert_type=password">패스워드 찾기</a>
    </div>
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
    <?php
        if($_GET["cert_type"] ?? false == "password"): //pw
    ?>
    <form method="post" action="<?= getPage("id-result")->permalink ?>" class="row gap-24 tabs-tab active">
        <input type="hidden" name="cert_type" value="email" />
        <input type="hidden" name="mode" value="pw" />
        <div class="<?= $row_class ?>">
            <h6>아이디</h6>
            <div class="<?= $col_class ?>">
                <input type="text" name="id" placeholder="" />
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>이름</h6>
            <div class="<?= $col_class ?>">
                <input type="text" name="user_name" placeholder="이름" />
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>생년월일</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?>">
                    <?= comp("number",["name"=>"birth"]) ?>
                </div>
                <p class="caption">숫자만 입력해주세요. (예 : <?= date("Ymd") ?>)</p>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>이메일</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?> email">
                    <input type="text" name="email_id" id="email_id" placeholder="아이디" />
                    @
                    <input type="text" name="email_addr" id="email_addr" placeholder="메일주소">
                    <button
                    class="w"
                    click="
                    console.log(`${email_id.value}@${email_addr.value}`);
                    $.post(ajaxurl,{action:'createEmailCert',pw_reset:true,email:`${email_id.value}@${email_addr.value}`})
                    .done(res=>{email_caption.innerHTML = res.data;console.log(res)})
                    .fail(res=>email_caption.innerHTML = `인증번호 발송에 실패하였습니다.`)
                    ">인증번호 전송</button>
                </div>
            </div>
        </div>
        <?= comp("form-cert-email") ?>
        <hr />
        <div class="flex end">
            <button type="submit">
                비밀번호 재설정
            </button>
        </div>
    </form>
    <?php
        else: //id
    ?>
    <form method="post" action="<?= getPage("id-result")->permalink ?>" class="row gap-24 tabs-tab active">
        <input type="hidden" name="cert_type" value="email" />
        <input type="hidden" name="mode" value="id" />
        <div class="<?= $row_class ?>">
            <h6>이름</h6>
            <div class="<?= $col_class ?>">
                <input type="text" name="user_name" placeholder="이름" />
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>생년월일</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?>">
                    <?= comp("number",["name"=>"birth"]) ?>
                </div>
                <p class="caption">숫자만 입력해주세요. (예 : <?= date("Ymd") ?>)</p>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>이메일</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?> email">
                    <input type="text" name="email_id" id="email_id" placeholder="아이디" />
                    @
                    <input type="text" name="email_addr" id="email_addr" placeholder="메일주소">
                </div>
            </div>
        </div>
        <hr />
        <div class="flex end">
            <button type="submit">
                아이디 찾기
            </button>
        </div>
    </form>
    <?php
        endif;
    ?>
</div>
<?php
get_footer();
?>