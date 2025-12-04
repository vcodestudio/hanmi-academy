<?php
// 세션이 필요한 경우에만 시작 (이메일 인증용)
if (!empty($_POST) && isset($_POST["action"]) && $_POST["action"] == "change_user") {
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }
}
    if (!isset($user)) {
        $user = wp_get_current_user();
    }
    $user_id = "user_".$user->ID;
?>
<?php
    if(!empty($_POST) && isset($_POST["action"]) && $_POST["action"] == "change_user") {

        if(session_status() === PHP_SESSION_ACTIVE && isset($_SESSION["email"]) && isset($_POST["cert_numb"]) && $_SESSION["cert_numb"] == $_POST["cert_numb"]) {
            if(isset($_POST['email_id']) && !empty($_POST['email_id']) && isset($_POST['email_addr']) && !empty($_POST['email_addr'])) {
                wp_update_user([
                    "ID"=>$user->ID,
                    "user_email"=>$_POST['email_id']."@".$_POST['email_addr']
                ]);
            }
        }
        if(isset($_POST["tel-1"]) && isset($_POST["tel-2"]) && isset($_POST["tel-3"])) {
            update_field("tel",$_POST["tel-1"]."-".$_POST["tel-2"]."-".$_POST["tel-3"],$user_id);
        }
        if(isset($_POST["address"])) {
            update_field("address",$_POST["address"],$user_id);
        }

        echo comp("info",[
            "label"=>"회원정보가 변경되었습니다."
        ]);
    }
?>
<form method="post" class="row gap-32">
    <input type="hidden" name="action" value="change_user"/>
    <div class="form">
        <div class="item">
            <div>
                <p>
                    아이디
                </p>
            </div>
            <div>
                <p>
                    <?= $user->user_login ?>
                </p>
            </div>
        </div>
        <div class="item">
            <div>
                <p>
                    성명
                </p>
            </div>
            <div>
                <p>
                <?= $user->display_name ?>
                </p>
            </div>
        </div>
        <div class="item">
            <div>
                <p>
                    생년월일
                </p>
            </div>
            <div>
                <p>
                    <?php
                    $birth = _acf("birth",$user_id);
                    if($birth) {
                        echo changeDateFormat($birth,"","Y년 m월 d일");
                    } else {
                        echo "-";
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="item">
            <div>
                <p>
                    성별
                </p>
            </div>
            <div>
                <p>
                    <?php
                    $gender_obj = get_field_object("gender",$user_id);
                    $gender_value = _acf("gender",$user_id);
                    if($gender_obj && is_array($gender_obj) && isset($gender_obj["choices"]) && $gender_value) {
                        $gender_label = is_array($gender_value) && isset($gender_value["label"]) ? $gender_value["label"] : $gender_value;
                        if(isset($gender_obj["choices"][$gender_label])) {
                            echo esc_html($gender_obj["choices"][$gender_label]);
                        } else {
                            echo esc_html($gender_label);
                        }
                    } else {
                        echo "-";
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="item">
            <div>
                <p>
                    휴대폰
                </p>
            </div>
            <div>
                <div class="phone">
                <?= comp("select",["options"=>[
                        ["010"],
                        ["011"],
                        ["0136"],
                        ["016"],
                        ["017"],
                        ["018"],
                        ["019"]
                    ],"name"=>"tel-1"                    ]) ?>
                    -
                    <?= comp("number",["name"=>"tel-2","attr"=>["value"=>isset($_POST["tel-2"]) ? $_POST["tel-2"] : ""]]) ?>
                    -
                    <?= comp("number",["name"=>"tel-3","attr"=>["value"=>isset($_POST["tel-3"]) ? $_POST["tel-3"] : ""]]) ?>
                </div>
            </div>
        </div>
        <div class="item">
            <div>
                <p>
                    이메일 주소
                </p>
            </div>
            <div>
                <?php
                    $email = isset($user->user_email) ? $user->user_email : "";
                    $email_parts = $email ? explode("@",$email) : [];
                    $email_id_val = isset($email_parts[0]) ? $email_parts[0] : "";
                    $email_addr_val = count($email_parts) > 1 ? $email_parts[count($email_parts) - 1] : "";
                ?>
                <div class="email">
                    <input type="text" id="email_id" name="email_id" value="<?= esc_attr($email_id_val) ?>"/>
                    @
                    <input type="text" id="email_addr" name="email_addr" value="<?= esc_attr($email_addr_val) ?>"/>
                    <button type="button" class="w" click="
                    console.log(`${email_id.value}@${email_addr.value}`);
                    $.post(ajaxurl,{action:'createEmailCert',email:`${email_id.value}@${email_addr.value}`})
                    .done(res=>{email_caption.innerHTML = res.data;
                        email_caption.classList.add('active');
                        console.log(res);
                    })
                    .fail(res=>email_caption.innerHTML = `인증번호 발송에 실패하였습니다.`)
                    ">인증번호 전송</button>
                </div>
                <p class="caption" id="email_caption"></p>
                <div>
                    <input type="text" name="cert_numb" placeholder="인증번호 입력"/>
                    <!-- <?= comp("alert",[
                    // "display"=>isset($_POST) && isset($_SESSION["email"]) && $checks["email"],
                    "label"=>"올바른 이메일주소를 입력해주세요."
                    ]) ?> -->
                </div>
            </div>
        </div>
        <div class="item">
            <div>
                <p>
                    주소
                </p>
            </div>
            <div>
                <div class="flex gap-12">
                    <input type="text" name="address" value="<?= _acf("address",$user_id) ?>" readonly/>
                    <button type="button" click="dmap.open()" class="w">주소찾기</button>
                </div>
            </div>
        </div>
        <!-- 뉴스레터 -->
        <!-- <div class="item">
            <div>
                <p>
                    뉴스레터 수신동의
                </p>
            </div>
            <div>
                <?= comp("radios",["items"=>[
                    ["1","동의"],
                    ["0","동의안함"]
                ]]) ?>
            </div>
        </div> -->
    </div>
    <hr/>
    <div class="col right">
        <button type="submit">
            변경하기
        </button>
    </div>
</form>
<?= comp("address_script") ?>