<?php
get_header();
echo temp("account-header");
if(isset($_POST["account_agree"])) {
    $_SESSION["agree"] = $_POST["account_agree"];
    wp_redirect(getPage("account-create-certification")->permalink);
}
?>
<div class="col-1-2 gap-24">
    <div>
        <h5 class="bold">
            이용약관
        </h5>
    </div>
    <div class="row gap-1r">
        <div class="textbox">
        <?= get_field("account-policy","option") ?>  
        </div>
        <div>
            <?= comp("checkbox",["label"=>"동의합니다",
            "input_attr"=>[
                "v-model"=>"check",
                "value"=>"1"
            ]
            ]) ?>
        </div>
    </div>
</div>
<hr/>
<div class="col-1-2 gap-24">
    <div>
        <h5 class="bold">
        개인정보 수집 이용에 대한 동의[필수]
        </h5>
    </div>
    <div class="row gap-1r">
        <div class="textbox">
        <?= get_field("account-policy_2","option") ?>  
        </div>
        <div>
        <?= comp("checkbox",["label"=>"동의합니다",
            "input_attr"=>[
                "v-model"=>"check",
                "value"=>"2"
            ],
            "change"=>"
                check_1.value = e.currentTarget.value;
            "
            ]) ?>
        </div>
    </div>
</div>
<hr/>
<form class="flex end gap-24 middle m-col-1" method="post">
    <?= comp("checkbox",[
        "label"=>"전체 동의합니다.",
        "input_attr"=>[
            ":checked"=>"checkedAll",
            "@click"=>"checkAll"
        ],
        ]) ?>
    <input type="hidden" v-model="check" name="account_agree"/>
    <button type="submit" :disabled="!(checkedAll)">
    동의 및 가입인증 페이지로 이동
    </button>
</form>
<?php
echo temp("account-footer");
get_footer();
?>