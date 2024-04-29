<?php
    $vue = $arg["vue"] ?? false;
?>
<div class="user-login">
				<div class="inner row gap-24">
					<div class="s-col-2 gap-0">
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
					<div class="row gap-1r">
						<div class="row gap-8">
							<p class="bold">아이디</p>
							<?= comp("text",[
								"attr"=>[
									"name"=>"log",
									"id"=>"user_login",
									"autocomplete"=>"username"
									]
								]) ?>
						</div>
						<div class="row gap-8">
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
						<div class="flex middle flex-auto">
							<div class="divider">
								<a href="<?= getPage("account-find")->permalink ?>">아이디 찾기</a>
								<a href="<?= getPage("account-find")->permalink."?cert_type=password" ?>">비밀번호 찾기</a>
							</div>
						</div>
						<div class="flex right middle flex-none">
							<a href="<?= getPage("account-create")->permalink ?>">회원가입</a>
						</div>
					</div>
					<div class="row">
						<input type="submit" name="wp-submit" id="wp-submit" value="로그인" class="button button-primary">
						<input type="hidden" name="redirect_to" value="<?= get_home_url() ?>">
					</div>
				</div>
			</div>