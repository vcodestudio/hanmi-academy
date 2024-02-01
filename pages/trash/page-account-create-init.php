<?php
get_header();
session_start();
$row_class="field col gap-24";
$col_class="field-input row gap-1r non-stretch";
$item_class="field-item flex gap-1r middle";
$cert = true;
$keys = ["user_name","birth","email"];
foreach($keys as $key) {
    if(!isset($_SESSION[$key])) $cert = false;
}
if($cert):
    
    require_once DIR_SRC."/php/join_check.php";
    function _amsg($key="",$msg="") {
        global $checks;
        if(isset($_POST) && !empty($_POST) && !$checks[$key]):
        echo comp("alert",["label"=>$msg]);
        endif;
    }
$birth_str = $_SESSION["birth"];
$birth_str = substr_replace($birth_str,"-",4,0);
$birth_str = substr_replace($birth_str,"-",7,0);
$birth = date("Y년 m월 d일",strtotime($birth_str));
?>
<form class="page-wrap forms row gap-32" method="POST">
    <h3>회원정보 입력</h3>
    <div class="row gap-24">
        <div class="<?= $row_class ?>">
            <h6>이름</h6>
            <div class="<?= $col_class ?>">
                <!-- <input type="text" name="user_name" value="<?= $_SESSION["user_name"] ?? $_POST["user_name"] ?? "" ?>"/> -->
                <p><?= $_SESSION["user_name"]  ?></p>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>생년월일</h6>
            <div class="<?= $col_class ?>">
                <!-- <input type="text" name="birth" value="<?= $_SESSION["birth"] ?? $_POST["birth"] ?>"/> -->
                <p><?= $birth ?></p>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>아이디</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?>">
                    <input type="text" name="id" value="<?= $_POST["id"] ?? "" ?>" id="userID"/>
                    <button type="button" class="w" click="
                    $.post(ajaxurl,{action:'searchID',id:userID.value}).done(res=>{
                        a_id_1.style.display = 'block';
                        a_id_1.innerHTML = res.data;
                    });
                    ">중복확인</button>
                </div>
                <p class="caption" id="a_id_1" style="display:none"></p>
                <?= _amsg("id","유효한 아이디를 입력해주세요.") ?>
            </div>
        </div>
        
        <div class="<?= $row_class ?>">
            <h6>비밀번호</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?>">
                    <input type="password" name="pw" value=""/>
                </div>
                <div class="row gap-8">
                    <p class="caption">영문 숫자를 혼용하여 10~12자리 이내를 입력해 주세요.</p>
                    <?= _amsg("pw","비밀번호 형식이 정확하지 않습니다.") ?>
                </div>
            </div>
        </div>
        <div class="<?= $row_class ?>">
            <h6>비밀번호 확인</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?>">
                    <input type="password" name="pw_" value=""/>
                </div>
                <div class="row gap-8">
                    <?= _amsg("pw_","비밀번호가 일치하지 않습니다.") ?>
                    <p class="caption">비밀번호를 한번 더 입력해 주세요.</p>
                </div>
            </div>
        </div>
        <div class="<?= $row_class ?> phone">
            <h6>휴대폰</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?> stretch">
                    <?= comp("select",["options"=>[
                        ["010"],
                        ["011"],
                        ["0136"],
                        ["016"],
                        ["017"],
                        ["018"],
                        ["019"]
                    ],"name"=>"tel-1"]) ?>
                    -
                    <?= comp("number",["name"=>"tel-2","attr"=>["value"=>$_POST["tel-2"] ?? ""]]) ?>
                    -
                    <?= comp("number",["name"=>"tel-3","attr"=>["value"=>$_POST["tel-3"] ?? ""]]) ?>
                    <!-- <button class="w">인증하기</button> -->
                </div>
                <?= _amsg("tel","번호가 올바르지 않습니다.") ?>
            </div>
        </div>
        <!-- 이메일 -->
        <div class="<?= $row_class ?>">
            <h6>이메일</h6>
            <div class="<?= $col_class ?>">
                <!-- <div class="<?= $item_class ?> email">
                    <input type="text" name="email_id" value="<?= $_POST["email_id"] ?>"/>
                    @
                    <?= comp("select",["options"=>[
                        ["*","직접입력"],
                        ["gmail.com","Gmail"],
                        ["naver.com","네이버"]
                    ],"change"=>"
                    email_addr.value = this.selected;
                    if(v!='') $(email_addr).attr('readonly',1);
                    else email_addr.removeAttribute('readonly');
                    "]) ?>
                    <input type="text" name="email" id="email_addr" value="<?= $_POST["email"] ?>">
                </div> -->
                <p><?= $_SESSION["email"] ?></p>
            </div>
        </div>
        <!-- 주소 -->
        <div class="<?= $row_class ?>">
            <h6>주소</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?>">
                    <input type="text" name="address" value="<?= $_POST["address"] ?? "" ?>" readonly/>
                    <button type="button" click="dmap.open()" class="w">주소찾기</button>
                </div>
                <?= _amsg("address","주소가 올바르지 않습니다.") ?>
            </div>
        </div>
        <!-- 성별 -->
        <div class="<?= $row_class ?>">
            <h6>성별</h6>
            <div class="<?= $col_class ?>">
                <div class="<?= $item_class ?>">
                    <?= comp("radios",[
                        "id"=>"gender",
                        "name"=>"gender",
                        "item"=>[
                            ["male","남자"],
                            ["female","여자"]
                        ],
                        "selected"=>"male"
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="flex end gap-16">
        <a href="/account-create" class="w button">
            취소하기
        </a>
        <button type="submit">
            가입하기
        </button>
    </div>
</form>
<?php
else:
    ?>
    <div class="page-wrap row gap-24 middle center">
        <h3 style="center">인증정보가 올바르지 않습니다.</h3>
        <p>다시 입력해주세요.</p>
        <br/>
        <button class="w" click="history.back()">돌아가기</button>
    </div>
    <?php
    endif;
    ?>
    <?= comp("address_script") ?>
    <?php
get_footer();
?>