<?php
    if(isset($_POST) && !empty($_POST) &&  isset($_POST["action"]) && $_POST["action"] == "change_pw") {
        $user = wp_get_current_user();
        $user_id = $user->ID;
        if($_POST["pw"] == $_POST["pw_"]) {
            wp_set_password($_POST["pw"], $user_id);
        }

        echo comp("info",["label"=>"비밀번호가 변경되었습니다."]);
    }
?>
<p>
    보안을 위해 30일마다 비밀번호 변경을 권장드립니다.
</p>
<form method="post" class="row gap-32">
    <input type="hidden" name="action" value="change_pw"/>
    <div class="form">
        <div class="item">
            <div>변경할 비밀번호</div>
            <div>
                <input type="password" name="pw"/>
            </div>
        </div>
        <div class="item">
            <div>비밀번호 확인</div>
            <div>
                <input type="password" name="pw_"/>
            </div>
        </div>
    </div>
    <hr/>
    <div class="col right">
        <button type="submit">
            비밀번호 변경하기
        </button>
    </div>
</form>