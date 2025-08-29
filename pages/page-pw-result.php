<?php
    get_header();
    $row_class="field flex gap-24 p-gap-8 middle";
$col_class="field-input row gap-1r non-stretch";
$item_class="field-item flex gap-1r middle";
    // $_POST["user_name"] $_POST["email_id"] $_POST["email"] $_POST["birth"]
    $args = [
        "search"=>$_POST["email_id"]."@".$_POST["email_addr"],
        "search_columns"=>["user_email"],
        "role"=>"subscriber",
        "meta_query"=>[
            "AND"
        ]
        ];
    $fields = [
        ["birth",$_POST["birth"]],
    ];
    foreach($fields as $f) {
        array_push($args["meta_query"],[
            "key"=>$f[0],
            "value"=>$f[1]
        ]);
    }
?>
<div class="page-wrap row gap-32">
    <h3>비밀번호 확인</h3>
    <?php
                $user = get_users($args)[0] ?? null;
                if($user && $user->display_name == $_POST["user_name"]):
                ?>
        <div class="<?= $row_class ?>">
            <h6>아이디</h6>
            <div class="<?= $col_class ?>">
                    <p>
                    <?= $user->user_login ?>
                    </p>
            </div>
        </div>
    <?php
                else:
                ?>
                <p>
                    일치하는 계정정보가 없습니다.
                </p>
    <?php
                endif;
            ?>
    <hr />
    <div class="col gap-1r right">
        <a href="../" class="button w">돌아가기</a>
        <a href="<?= getPage("account-find")->permalink."?cert_type=password" ?>" class="button">비밀번호 찾기</a>
    </div>
</div>
<?php
    get_footer();
?>