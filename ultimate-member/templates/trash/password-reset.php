<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$passlink = getPage("account-find")->permalink."?cert_type=password";
?>
<div class="um <?php echo esc_attr( $this->get_class( $mode ) ); ?> um-<?php echo esc_attr( $form_id ); ?>">
	<div class="um-form">
		<form method="post" action="" class="row gap-32">
			<?php if ( isset( $_GET['updated'] ) && 'checkemail' === sanitize_key( $_GET['updated'] ) ) { ?>
				<div class="um-field um-field-block um-field-type_block">
					<div class="um-field-block">
						<div style="text-align:center;">
							<?php esc_html_e( 'If an account matching the provided details exists, we will send a password reset link. Please check your inbox.', 'ultimate-member' ); ?>
						</div>
					</div>
				</div>
			<?php } elseif ( isset( $_GET['updated'] ) && 'password_changed' === sanitize_key( $_GET['updated'] ) ) { ?>
				<div class="um-field um-field-block um-field-type_block">
					<div class="um-field-block">
						<div style="text-align:center;">
							<?php esc_html_e( '성공적으로 비밀번호가 변경되었습니다.', 'ultimate-member' ); ?>
						</div>
					</div>
				</div>
                <div class="col center">
                    <a href="/" class="button">
                        메인페이지로 바로가기
                    </a>
                </div>
			<?php } else { ?>
				<input type="hidden" name="_um_password_reset" id="_um_password_reset" value="1" />
				<?php
				/**
				 * UM hook
				 *
				 * @type action
				 * @title um_reset_password_page_hidden_fields
				 * @description Password reset hidden fields
				 * @input_vars
				 * [{"var":"$args","type":"array","desc":"Password reset shortcode arguments"}]
				 * @change_log
				 * ["Since: 2.0"]
				 * @usage add_action( 'um_reset_password_page_hidden_fields', 'function_name', 10, 1 );
				 * @example
				 * <?php
				 * add_action( 'um_reset_password_page_hidden_fields', 'my_reset_password_page_hidden_fields', 10, 1 );
				 * function my_reset_password_page_hidden_fields( $args ) {
				 *     // your code here
				 * }
				 * ?>
				 */
				do_action( 'um_reset_password_page_hidden_fields', $args );
				if ( ! empty( $_GET['updated'] ) ) { ?>
					<div class="um-field um-field-block um-field-type_block">
						<div class="um-field-block">
							<div style="text-align:center;">
								<?php if ( 'expiredkey' === sanitize_key( $_GET['updated'] ) ) {
									esc_html_e( '비밀번호 초기화 링크가 만료되었습니다.', 'ultimate-member' );
								} elseif ( 'invalidkey' === sanitize_key( $_GET['updated'] ) ) {
									esc_html_e( '비밀번호 초기화 링크가 만료되었습니다.', 'ultimate-member' );
								} ?>
							</div>
						</div>
					</div>
                    <div class="flex center">
                        <a class="button" href="<?= $passlink ?>">
                        비밀번호 찾기
                        </a>
                    </div>
				<?php } else {
                    wp_redirect($passlink);
				    }
				/**
				 * UM hook
				 *
				 * @type action
				 * @title um_after_password_reset_fields
				 * @description Hook that runs after user reset their password
				 * @input_vars
				 * [{"var":"$args","type":"array","desc":"Form data"}]
				 * @change_log
				 * ["Since: 2.0"]
				 * @usage add_action( 'um_after_password_reset_fields', 'function_name', 10, 1 );
				 * @example
				 * <?php
				 * add_action( 'um_after_password_reset_fields', 'my_after_password_reset_fields', 10, 1 );
				 * function my_after_password_reset_fields( $args ) {
				 *     // your code here
				 * }
				 * ?>
				 */
				do_action( 'um_after_password_reset_fields', $args ); ?>
				<?php
				/**
				 * UM hook
				 *
				 * @type action
				 * @title um_reset_password_form
				 * @description Password reset display form
				 * @input_vars
				 * [{"var":"$args","type":"array","desc":"Password reset shortcode arguments"}]
				 * @change_log
				 * ["Since: 2.0"]
				 * @usage add_action( 'um_reset_password_form', 'function_name', 10, 1 );
				 * @example
				 * <?php
				 * add_action( 'um_reset_password_form', 'my_reset_password_form', 10, 1 );
				 * function my_reset_password_form( $args ) {
				 *     // your code here
				 * }
				 * ?>
				 */
				do_action( 'um_reset_password_form', $args );
				/**
				 * UM hook
				 *
				 * @type action
				 * @title um_after_form_fields
				 * @description Password reset after display form
				 * @input_vars
				 * [{"var":"$args","type":"array","desc":"Password reset shortcode arguments"}]
				 * @change_log
				 * ["Since: 2.0"]
				 * @usage add_action( 'um_after_form_fields', 'function_name', 10, 1 );
				 * @example
				 * <?php
				 * add_action( 'um_after_form_fields', 'my_after_form_fields', 10, 1 );
				 * function my_after_form_fields( $args ) {
				 *     // your code here
				 * }
				 * ?>
				 */
				do_action( 'um_after_form_fields', $args );
			} ?>
		</form>
	</div>
</div>
