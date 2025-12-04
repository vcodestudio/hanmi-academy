<?php
function cptui_register_my_cpts() {

	/**
	 * Post Type: 프로그램 관리.
	 */

	$labels = [
		"name" => esc_html__( "프로그램 관리", "hanmi-academy" ),
		"singular_name" => esc_html__( "프로그램", "hanmi-academy" ),
	];

	$args = [
		"label" => esc_html__( "프로그램 관리", "hanmi-academy" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "post_program", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"show_in_graphql" => false,
	];

	register_post_type( "post_program", $args );

	/**
	 * Post Type: 전시관리.
	 */

	$labels = [
		"name" => esc_html__( "전시관리", "hanmi-academy" ),
		"singular_name" => esc_html__( "전시", "hanmi-academy" ),
	];

	$args = [
		"label" => esc_html__( "전시관리", "hanmi-academy" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "post_exhibition", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"show_in_graphql" => false,
	];

	register_post_type( "post_exhibition", $args );

	/**
	 * Post Type: 공지사항.
	 */

	$labels = [
		"name" => esc_html__( "공지사항", "hanmi-academy" ),
		"singular_name" => esc_html__( "공지사항", "hanmi-academy" ),
	];

	$args = [
		"label" => esc_html__( "공지사항", "hanmi-academy" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "post_notice", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"show_in_graphql" => false,
	];

	register_post_type( "post_notice", $args );

	/**
	 * Post Type: 활동사진.
	 */

	$labels = [
		"name" => esc_html__( "활동사진", "hanmi-academy" ),
		"singular_name" => esc_html__( "활동사진", "hanmi-academy" ),
	];

	$args = [
		"label" => esc_html__( "활동사진", "hanmi-academy" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "post_activity", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"show_in_graphql" => false,
	];

	register_post_type( "post_activity", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );

?>