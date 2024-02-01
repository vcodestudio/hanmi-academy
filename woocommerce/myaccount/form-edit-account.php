<?php
	defined( 'ABSPATH' ) || exit;
?>
<div></div>
<h3>계정정보 변경</h3>
<?php
	echo temp("account/account");
?>
<h3>비밀번호 변경</h3>
<?php
	echo temp("account/password-change");
?>
<h3>계정삭제</h3>
<?php
	echo temp("account/delete-account");
?>