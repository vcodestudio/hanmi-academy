<?php
    $vue = $arg["vue"] ?? false;
	$redirect_to = $_GET["redirect_to"] ?? get_home_url();
?>
<div class="user-login">
				<div class="gap-24 inner row">
					<div class="gap-0 s-col-2">
						<h3>로그인</h3>
                        <?php
                            if($vue):
                        ?>
						<div class="flex right">
							<div class="flex middle button clean transparent" @click="loginPanel = 0">
								<?= icon("close") ?>
							</div>
						</div>
                        <?php
                            endif;
                        ?>
					</div>
					<?php
						if(isset($_GET["login"]) && $_GET["login"] == "failed"):
						?>
						<div class="infobox">
						<?= icon("alert") ?>	
						올바른 계정정보를 입력해주세요.
						</div>
						<?php
							endif;
						?>
						<?php
							// redirect_to 파라미터가 있으면 hidden 필드로 유지
							if(isset($_GET["redirect_to"]) && !empty($_GET["redirect_to"])):
								$redirect_to = esc_url_raw($_GET["redirect_to"]);
							endif;
						?>
					<div class="row gap-1r">
						<div class="gap-8 row">
							<p class="bold">아이디</p>
							<?= comp("text",[
								"attr"=>[
									"name"=>"log",
									"id"=>"user_login",
									"autocomplete"=>"username"
									]
								]) ?>
						</div>
						<div class="gap-8 row">
							<p class="bold">비밀번호</p>
							<?= comp("text",[
								"type"=>"password",
								"attr"=>[
									"name"=>"pwd",
									"id"=>"user_pass",
									"autocomplete"=>"current-password"
									]
								]) ?>
						</div>
					</div>
					<div class="flex gap-1r">
						<div class="flex flex-auto middle">
							<div class="divider">
								<a href="<?= getPage("account-find")->permalink ?>">아이디 찾기</a>
								<a href="<?= getPage("account-find")->permalink."?cert_type=password" ?>">비밀번호 찾기</a>
							</div>
						</div>
						<div class="flex flex-none right middle">
							<a href="<?= getPage("account-create")->permalink ?>">회원가입</a>
						</div>
					</div>
					<div class="row">
						<input type="submit" name="wp-submit" id="wp-submit" value="로그인" class="button button-primary">
						<input type="hidden" name="redirect_to" value="<?= $redirect_to ?>">
					</div>
				</div>
			</div>