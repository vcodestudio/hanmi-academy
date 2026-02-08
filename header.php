<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo("charset"); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title><?= bloginfo("name") ?></title>
	<?php wp_head(); ?>
	<script type="text/javascript">
		var ajaxurl = "<?php echo admin_url("admin-ajax.php"); ?>";
	</script>
</head>
<?php
$pgname = !is_single()
	? get_post()->post_name ?? ""
	: get_post()->post_type ?? "";
if (is_home()) {
	$pgname = "home";
}
?>
<body lang="kr" class="<?= $pgname ?>">
	<div class="dimmer" id="dimmer" click="closePanel()"></div>
	<div class="header">
		<div class="wrap row pc">
			<div class="col header-top">
				<a href="/" class="logo">
					<img src="<?= HM::$logo ?>" />
				</a>
				<div></div>
				<div class="gap-60">
				</div>
			</div>
			<div class="col menu-wrap">
				<div class="flex gap-60 c-gap-30 middle menus">
					<a href="/" class="menu_logo">
						<img src="<?= HM::$symbol ?>" />
					</a>
					<?php if (function_exists('get_field') && ($menu_items = get_field("menu", "option"))): foreach ($menu_items as $idx => $item):
     	$link = $item["page"]; ?>
					<?php $iscur =
     	!is_home() && str_contains($link, $_SERVER["REQUEST_URI"])
     		? "current"
     		: ""; ?>
					<a href="<?= $link ?>" class="<?= $iscur ?> <?= "menu-" . $idx ?>"><?= $item[
	"label"
] ?></a>
					<?php
     endforeach; endif; ?>
				</div>
				<div></div>
				<div class="col gap-32 flex middle">
					<div class="cursor-pointer img-block" @click="search.active ^= true">
						<?= icon("search") ?>
					</div>
					<?php if (is_user_logged_in() && ($user = wp_get_current_user())): ?>
					<a href="<?= wp_logout_url("/") ?>">로그아웃</a>
					<a href="<?= getPage("mypage")->permalink ??
     	"/" ?>" class="bold flex middle col gap-8"><?= icon(
	"user"
) ?> <?= $user->display_name ?></a>
					<?php else: ?>
					<!-- <a href="<?= getPage("account-create")->permalink ??
     	"/" ?>">회원가입</a> -->
					<a href="javascript:void()" @click="loginPanel ^= 1">로그인/회원가입</a>
					<?php endif; ?>

					<?php if (function_exists('get_field') && get_field("multi_lang", "options")): ?>
					<div class="switch">
						<a href="/" class="active">한</a>
						<a href="/">A</a>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col flex hide" v-show="search.active">
				<div class="search flex gap-1r max-width-half flex-auto">
					<input class="flex-auto" type="text" v-model="search.text" @keyup.enter="$refs.searchAction.click()"
						placeholder="시리즈명 또는 작품명 또는 작가명을 입력하여 검색해주세요." />
					<a :href="`/?s=${search.text}`" class="button flex-none" ref="searchAction">
						검색
					</a>
				</div>
				<div></div>
				<div class="button w clean" @click="search.active = 0">
					<?= icon("close") ?>
				</div>
			</div>
		</div>
		<div class="wrap row mob">
			<div class="w_fit s-col-2">
				<a href="/" class="logo">
					<img src="<?= HM::$logo ?>" />
				</a>
				<div class="col middle gap-1r flex right">
					<div class="cursor-pointer img-block search-icon" @click="search.active ^= true">
						<?= icon("search") ?>
					</div>
					<div class="hambug" @click="hambug ^= true">
						<div class="l" v-for="i in 5"></div>
					</div>
				</div>
			</div>
			<div class="flex hide gap-1r w_fit" v-show="search.active">
				<div class="search flex gap-1r max-width-half flex-auto">
					<input class="flex-auto min-w-[0px]" type="text" v-model="search.text" @keyup.enter="$refs.searchAction.click()"
						placeholder="시리즈명 또는 작품명 또는 작가명을 입력하여 검색해주세요." />
					<a :href="`/?s=${search.text}`" class="button flex-none" ref="searchAction">
						검색
					</a>
				</div>
				<div class="button w clean flex-none rect" @click="search.active = 0">
					<?= icon("close") ?>
				</div>
			</div>
		</div>
		<!--login-->
		<form action="/wp-login.php" method="post" class="login-panel hide sign-in" v-show="loginPanel == 1">
			<?= temp("user-login", ["vue" => true]) ?>
		</form>
		<!-- hambug -->
		<div class="hambug-menu mob">
			<div class="wrap col gap-1r">
				<?php if (function_exists('get_field') && ($menu_items = get_field("menu", "option"))): foreach ($menu_items as $idx => $item):
    	$link = $item["page"]; ?>
				<?php $iscur =
    	!is_home() && str_contains($link, $_SERVER["REQUEST_URI"])
    		? "current"
    		: ""; ?>
				<p>
					<a href="<?= $link ?>" class="menu <?= $iscur ?> <?= "menu-" .
 	$idx ?>"><?= $item["label"] ?></a>
				</p>
				<?php
    endforeach; endif; ?>
				<br />
				<hr />
				<div class="w_fit flex gap-1r end">
					<?php if (is_user_logged_in() && ($user = wp_get_current_user())): ?>
					<a href="<?= getPage("mypage")
     	->permalink ?>" class="flex middle gap-8 single-line">
						<?= icon("user") ?>
						<?= $user->display_name ?>
					</a>
					<a href="<?= wp_logout_url("/") ?>">로그아웃</a>
					<?php else: ?>
					<a href="javascript:void()" @click="loginPanel ^= 1">로그인</a>
					<a href="<?= getPage("account-create")->permalink ?>">회원가입</a>
					<?php endif; ?>
					<!-- <div class="switch">
						<a href="/" class="active">한</a>
						<a href="/">A</a>
					</div> -->
				</div>
			</div>
		</div>
	</div>
	<!-- page content -->
	<div class="content_wrap">
		<?php wp_body_open();
?>
