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
	
	// 기본 탭 메뉴 (필터를 통해 수정 가능)
	$default_tabs = [
		["account","계정정보 수정"],
		["password-change","비밀번호 변경"],
		["delete-account","회원탈퇴"],
		["payment-history","결제내역"]
	];
	
	// 필터를 통해 탭 메뉴 커스터마이징 가능
	$tabs = apply_filters('hanmi_account_tabs', $default_tabs);
	
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
			$template_path = DIR_MODULE . "/templates/account/".$tab[0].".php";
			if(file_exists($template_path)) {
				echo temp("account/".$tab[0],["user"=>$user]);
			} else {
				echo "<p>템플릿 파일을 찾을 수 없습니다: ".$tab[0]."</p>";
			}
		endif;
	endforeach;
?>