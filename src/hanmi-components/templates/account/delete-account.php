<?php
// Include WordPress admin functions to access delete user functions
require_once(ABSPATH . 'wp-admin/includes/user.php');

$message = '';
$message_type = '';

if (!empty($_POST ?? []) && isset($_POST["action"]) && $_POST["action"] == "delete_user") {
    if ($_POST["txt"] == "계정삭제") {
        $current_user = wp_get_current_user();
        if ($current_user->ID != 0) {
            // Check if multisite and use appropriate function
            if (is_multisite()) {
                require_once(ABSPATH . 'wp-admin/includes/ms.php');
                $result = wpmu_delete_user($current_user->ID);
                // wpmu_delete_user() returns void, so we'll assume success if no errors
                $result = true;
            } else {
                $result = wp_delete_user($current_user->ID);
            }

            if ($result) {
                $message = "계정이 성공적으로 삭제되었습니다.";
                $message_type = "success";
                // Redirect after successful deletion
                wp_redirect(home_url());
                exit;
            } else {
                $message = "계정 삭제에 실패했습니다. 다시 시도해주세요.";
                $message_type = "error";
            }
        } else {
            $message = "로그인한 사용자가 없습니다.";
            $message_type = "error";
        }
    } else {
        $message = "정확한 문구를 입력해주세요.";
        $message_type = "error";
    }
}
?>
<?php if (!empty($message)): ?>
    <div class="message <?php echo $message_type; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
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