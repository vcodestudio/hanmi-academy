<?php
function cptui_register_my_taxes() {

	/**
	 * Taxonomy: 작가정보.
	 */

	$labels = [
		"name" => esc_html__( "작가정보", "hanmi-academy" ),
		"singular_name" => esc_html__( "작가정보", "hanmi-academy" ),
	];

	
	$args = [
		"label" => esc_html__( "작가정보", "hanmi-academy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'artist', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "artist",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "artist", [ "post" ], $args );

	/**
	 * Taxonomy: 프로세스 / 재료.
	 */

	$labels = [
		"name" => esc_html__( "프로세스 / 재료", "hanmi-academy" ),
		"singular_name" => esc_html__( "프로세스 / 재료", "hanmi-academy" ),
	];

	
	$args = [
		"label" => esc_html__( "프로세스 / 재료", "hanmi-academy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'process', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "process",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "process", [ "post" ], $args );

	/**
	 * Taxonomy: 페이지템플릿.
	 */

	$labels = [
		"name" => esc_html__( "페이지템플릿", "hanmi-academy" ),
		"singular_name" => esc_html__( "페이지템플릿", "hanmi-academy" ),
	];

	
	$args = [
		"label" => esc_html__( "페이지템플릿", "hanmi-academy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'pages', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "pages",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "pages", [ "page" ], $args );

	/**
	 * Taxonomy: 저자.
	 */

	$labels = [
		"name" => esc_html__( "저자", "hanmi-academy" ),
		"singular_name" => esc_html__( "저자", "hanmi-academy" ),
	];

	
	$args = [
		"label" => esc_html__( "저자", "hanmi-academy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'publish_author', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "publish_author",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "publish_author", [ "post_book" ], $args );

	/**
	 * Taxonomy: 도서 카테고리.
	 */

	$labels = [
		"name" => esc_html__( "도서 카테고리", "hanmi-academy" ),
		"singular_name" => esc_html__( "도서 카테고리", "hanmi-academy" ),
	];

	
	$args = [
		"label" => esc_html__( "도서 카테고리", "hanmi-academy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'publish_category', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "publish_category",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "publish_category", [ "post_book" ], $args );

	/**
	 * Taxonomy: 장소.
	 */

	$labels = [
		"name" => esc_html__( "장소", "hanmi-academy" ),
		"singular_name" => esc_html__( "장소", "hanmi-academy" ),
	];

	
	$args = [
		"label" => esc_html__( "장소", "hanmi-academy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'location', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "location",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "location", [ "post_program", "post_exhibition" ], $args );


	/**
	 * Taxonomy: 신청.
	 */

	$labels = [
		"name" => esc_html__( "신청", "hanmi-academy" ),
		"singular_name" => esc_html__( "신청", "hanmi-academy" ),
	];

	
	$args = [
		"label" => esc_html__( "신청", "hanmi-academy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'application', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "application",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "application", [ "post_program" ], $args );

	/**
	 * Taxonomy: 과정.
	 */

	$labels = [
		"name" => esc_html__( "과정", "hanmi-academy" ),
		"singular_name" => esc_html__( "과정", "hanmi-academy" ),
	];

	
	$args = [
		"label" => esc_html__( "과정", "hanmi-academy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'course', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "course",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "course", [ "post_program" ], $args );

	/**
	 * Taxonomy: 참여대상.
	 */

	$labels = [
		"name" => esc_html__( "참여대상", "hanmi-academy" ),
		"singular_name" => esc_html__( "참여대상", "hanmi-academy" ),
	];

	
	$args = [
		"label" => esc_html__( "참여대상", "hanmi-academy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'participant', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "participant",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "participant", [ "post_program" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes' );
?>