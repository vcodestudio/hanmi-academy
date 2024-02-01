<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="um <?php echo esc_attr( $this->get_class( $mode ) ); ?> um-<?php echo esc_attr( $form_id ); ?>">
    <div class="um-form">
        <form method="post" action="" class="row gap-24">
            <input type="hidden" name="_um_password_change" id="_um_password_change" value="1" />
            <input type="hidden" name="login" value="<?php echo esc_attr( $args['login'] ); ?>" />
            <input type="hidden" name="rp_key" value="<?php echo esc_attr( $rp_key ); ?>" />
            <?php
			/**
			 * UM hook
			 *
			 * @type action
			 * @title um_change_password_page_hidden_fields
			 * @description Password change hidden fields
			 * @input_vars
			 * [{"var":"$args","type":"array","desc":"Password change shortcode arguments"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage add_action( 'um_change_password_page_hidden_fields', 'function_name', 10, 1 );
			 * @example
			 * <?php
			 * add_action( 'um_change_password_page_hidden_fields', 'my_change_password_page_hidden_fields', 10, 1 );
			 * function my_change_password_page_hidden_fields( $args ) {
			 *     // your code here
			 * }
			 * ?>
            */
            do_action( 'um_change_password_page_hidden_fields', $args );
            $fields = UM()->builtin()->get_specific_fields( 'user_password' );
            UM()->fields()->set_mode = 'password';
            $output = null;
            foreach ( $fields as $key => $data ) {
            $output .= UM()->fields()->edit_field( $key, $data );
            }
            // echo $output;
            ?>
            <div id="um_field_um_password_id_user_password"
                class="um-field um-field-password  um-field-user_password um-field-password um-field-type_password"
                data-key="user_password">
                <div class="um-field-label"><label for="user_password">새 비밀번호</label>
                    <div class="um-clear"></div>
                </div>
                <div class="um-field-area"><input class="um-form-field valid " type="password" name="user_password"
                        id="user_password" value="" placeholder="" data-validate="" data-key="user_password">
                </div>
            </div>
            <div id="um_field_um_password_id_confirm_user_password"
                class=" um-field-password  um-field-user_password um-field-password um-field-type_password"
                data-key="confirm_user_password">
                <div class="um-field-label"><label for="confirm_user_password">비밀번호 확인</label>
                    <div class="um-clear"></div>
                </div>
                <div class="um-field-area"><input class="um-form-field valid " type="password"
                        name="confirm_user_password" id="confirm_user_password" value="" placeholder=""
                        data-validate="" data-key="confirm_user_password"></div>
            </div>
            <div class="flex center">
                <button type="submit">비밀번호 변경</button>
            </div>
            <?php
			/**
			 * UM hook
			 *
			 * @type action
			 * @title um_change_password_form
			 * @description Password change form content
			 * @input_vars
			 * [{"var":"$args","type":"array","desc":"Password change shortcode arguments"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage add_action( 'um_change_password_form', 'function_name', 10, 1 );
			 * @example
			 * <?php
			 * add_action( 'um_change_password_form', 'my_change_password_form', 10, 1 );
			 * function my_change_password_form( $args ) {
			 *     // your code here
			 * }
			 * ?>
            */
            do_action( 'um_change_password_form', $args );
            /**
            * UM hook
            *
            * @type action
            * @title um_after_form_fields
            * @description Password change after form content
            * @input_vars
            * [{"var":"$args","type":"array","desc":"Password change shortcode arguments"}]
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
            do_action( 'um_after_form_fields', $args ); ?>
        </form>
    </div>
</div>