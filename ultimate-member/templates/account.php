<?php
	/**
 * Template for the login form
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/login.php
 *
 * Page: "Account"
 *
 * @version 2.6.1
 *
 * @var string $mode
 * @var int    $form_id
 * @var array  $args
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
	$user = wp_get_current_user();
	$tabs = [//각 앞단 key들은 파일명과 연관되어 있음
		["account","계정정보 수정"],
		["my-subscriptions","멤버쉽"],
		["password-change","비밀번호 변경"],
		["delete-account","회원탈퇴"]
	];
	function tabKeys($a) {
		return $a[0];
	}
	$curtab = $_GET["tab"] ?? $tabs[0][0];
	$curtab = array_search($curtab,array_map("tabKeys",$tabs))?$curtab:$tabs[0][0];
?>
<div class="col tab">
	<?php
		foreach($tabs as $i=>$tab):
	?>
		<a href="./?tab=<?= $tab[0] ?>" class="<?= ($curtab == $tab[0])?"selected":"" ?>">
			<?= $tab[1] ?>
		</a>
	<?php
		endforeach;
	?>
</div>
<?php
	foreach($tabs as $i=>$tab):
		if($tab[0] == $curtab):
			echo temp("account/".$tab[0],["user"=>$user]);
		endif;
	endforeach;
?>