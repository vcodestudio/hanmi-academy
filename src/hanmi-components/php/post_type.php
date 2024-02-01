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
        
        // tax
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
             * Taxonomy: 자격요건.
             */
        
            $labels = [
                "name" => esc_html__( "자격요건", "hanmi-academy" ),
                "singular_name" => esc_html__( "자격요건", "hanmi-academy" ),
            ];
        
            
            $args = [
                "label" => esc_html__( "자격요건", "hanmi-academy" ),
                "labels" => $labels,
                "public" => true,
                "publicly_queryable" => true,
                "hierarchical" => false,
                "show_ui" => true,
                "show_in_menu" => true,
                "show_in_nav_menus" => true,
                "query_var" => true,
                "rewrite" => [ 'slug' => 'job', 'with_front' => true, ],
                "show_admin_column" => false,
                "show_in_rest" => true,
                "show_tagcloud" => false,
                "rest_base" => "job",
                "rest_controller_class" => "WP_REST_Terms_Controller",
                "rest_namespace" => "wp/v2",
                "show_in_quick_edit" => false,
                "sort" => false,
                "show_in_graphql" => false,
            ];
            register_taxonomy( "job", [ "post_program" ], $args );
        
            /**
             * Taxonomy: 테마.
             */
        
            $labels = [
                "name" => esc_html__( "테마", "hanmi-academy" ),
                "singular_name" => esc_html__( "테마", "hanmi-academy" ),
            ];
        
            
            $args = [
                "label" => esc_html__( "테마", "hanmi-academy" ),
                "labels" => $labels,
                "public" => true,
                "publicly_queryable" => true,
                "hierarchical" => false,
                "show_ui" => true,
                "show_in_menu" => true,
                "show_in_nav_menus" => true,
                "query_var" => true,
                "rewrite" => [ 'slug' => 'program_theme', 'with_front' => true, ],
                "show_admin_column" => false,
                "show_in_rest" => true,
                "show_tagcloud" => false,
                "rest_base" => "program_theme",
                "rest_controller_class" => "WP_REST_Terms_Controller",
                "rest_namespace" => "wp/v2",
                "show_in_quick_edit" => false,
                "sort" => false,
                "show_in_graphql" => false,
            ];
            register_taxonomy( "program_theme", [ "post_program" ], $args );
        
            /**
             * Taxonomy: 색상.
             */
        
            $labels = [
                "name" => esc_html__( "색상", "hanmi-academy" ),
                "singular_name" => esc_html__( "색상", "hanmi-academy" ),
            ];
        
            
            $args = [
                "label" => esc_html__( "색상", "hanmi-academy" ),
                "labels" => $labels,
                "public" => true,
                "publicly_queryable" => true,
                "hierarchical" => false,
                "show_ui" => true,
                "show_in_menu" => true,
                "show_in_nav_menus" => true,
                "query_var" => true,
                "rewrite" => [ 'slug' => 'colorchip', 'with_front' => true, ],
                "show_admin_column" => false,
                "show_in_rest" => true,
                "show_tagcloud" => false,
                "rest_base" => "colorchip",
                "rest_controller_class" => "WP_REST_Terms_Controller",
                "rest_namespace" => "wp/v2",
                "show_in_quick_edit" => false,
                "sort" => false,
                "show_in_graphql" => false,
            ];
            register_taxonomy( "colorchip", [ "post_program", "post_exhibition" ], $args );
        }
        add_action( 'init', 'cptui_register_my_taxes' );
?>