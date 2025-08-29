<?php
    if(!empty($_POST ?? []) && isset($_POST["action"]) && $_POST["action"] == "delete_user") {
        if($_POST["txt"] == "계정삭제") {
            wp_delete_user(wp_get_current_user()->ID);
        }
    }
?>
<p>
    회원탈퇴를 진행합니다.
    아래 문구입력에 <b>[계정삭제]</b>를 입력해주세요.
</p>
<form method="post" class="row gap-32">
    <input type="hidden" name="action" value="delete_user"/>
    <div class="form">
        <div class="item">
            <div>문구입력</div>
            <div>
                <input type="text" name="txt"/>
            </div>
        </div>
    </div>
    <hr/>
    <div class="col right">
        <button type="submit">
            탈퇴하기
        </button>
    </div>
</form>