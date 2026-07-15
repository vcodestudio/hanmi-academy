<?php
    get_header();
    if (session_status() === PHP_SESSION_NONE) { @session_start(); }
    $row_class="field flex gap-24 p-gap-8 middle";
    $col_class="field-input row gap-1r non-stretch";
    $item_class="field-item flex gap-1r middle";
    
    $email = $_POST["email_id"]."@".$_POST["email_addr"];
    // 회원(wp_users)은 네트워크 전체가 공유하지만 권한(role)은 사이트별로 분리돼 있다.
    // role/blog 조건으로 좁히면 아카데미 권한이 없는 기존 회원(메인사이트 가입자)이
    // 검색에서 누락돼 아이디·비밀번호를 못 찾으므로 네트워크 전체를 대상으로 조회한다.
    $args = [
        "search"=>$email,
        "search_columns"=>["user_email"],
        "blog_id"=>0,
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
    <?php
            if($_POST["mode"] == "id"):
    ?>
    <h3>아이디 확인</h3>
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
    <?php
    elseif($_POST["mode"] == "pw"):
    ?>
    <h3>비밀번호 확인</h3>
    <?php
                $user = get_users($args)[0] ?? null;
                if(isset($user)
                && $user->display_name == ($_POST["user_name"] ?? false)
                && $user->user_email == "{$_POST["email_id"]}@{$_POST["email_addr"]}"):
                    if(function_exists("um_fetch_user")) :
                        um_fetch_user($user->ID);
                        UM()->user()->password_reset();
    ?>
    <p>
        <b>[<?= $user->user_email ?>]</b> 메일로 비밀번호 초기화 링크를 보냈습니다.
    </p>
    <?php
                    endif;
            else:
    ?>
    <p>
        유효하지 않은 계정정보입니다.
    </p>
    <hr />
    <div class="col right">
        <a href="../" class="button w">돌아가기</a>
    </div>
    <?php
            endif;
    ?>
    <?php
        else:
            wp_redirect(getPage("account-find")->permalink);
        endif;
    ?>
</div>
<?php
    unset($_SESSION["email"]);
    unset($_SESSION["cert_numb"]);
    get_footer();
?>