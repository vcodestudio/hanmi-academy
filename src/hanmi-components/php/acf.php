<?php
    if( function_exists('acf_add_local_field_group') ):

        acf_add_local_field_group(array(
            'key' => 'group_63691aeadf746',
            'title' => 'faq',
            'fields' => array(
                array(
                    'key' => 'field_63691aebb30e0',
                    'label' => '문항',
                    'name' => 'content',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_63691b02b30e1',
                            'label' => '제목',
                            'name' => 'title',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'parent_repeater' => 'field_63691aebb30e0',
                        ),
                        array(
                            'key' => 'field_63691b17b30e2',
                            'label' => '내용',
                            'name' => 'text',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'delay' => 0,
                            'tabs' => 'all',
                            'toolbar' => 'full',
                            'media_upload' => 1,
                            'parent_repeater' => 'field_63691aebb30e0',
                        ),
                        array(
                            'key' => 'field_63691b22b30e3',
                            'label' => '링크',
                            'name' => 'link',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'parent_repeater' => 'field_63691aebb30e0',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page',
                        'operator' => '==',
                        'value' => '181',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'permalink',
                1 => 'the_content',
                2 => 'excerpt',
                3 => 'discussion',
                4 => 'comments',
                5 => 'revisions',
                6 => 'slug',
                7 => 'author',
                8 => 'format',
                9 => 'page_attributes',
                10 => 'featured_image',
                11 => 'categories',
                12 => 'tags',
                13 => 'send-trackbacks',
            ),
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        acf_add_local_field_group(array(
            'key' => 'group_6369a169d4c3d',
            'title' => '공지사항',
            'fields' => array(
                array(
                    'key' => 'field_63a6721361f5f',
                    'label' => '대표이미지',
                    'name' => 'thumb',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'preview_size' => 'medium',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'post_notice',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'acf_after_title',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'permalink',
                1 => 'excerpt',
                2 => 'discussion',
                3 => 'comments',
                4 => 'revisions',
                5 => 'slug',
                6 => 'author',
                7 => 'format',
                8 => 'page_attributes',
                9 => 'featured_image',
                10 => 'categories',
                11 => 'tags',
                12 => 'send-trackbacks',
            ),
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        acf_add_local_field_group(array(
            'key' => 'group_6368fe2a146a8',
            'title' => '관람안내',
            'fields' => array(
                array(
                    'key' => 'field_6368fe2a6aed0',
                    'label' => '내용',
                    'name' => 'content',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_6368fe506aed1',
                            'label' => '제목',
                            'name' => 'title',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'parent_repeater' => 'field_6368fe2a6aed0',
                        ),
                        array(
                            'key' => 'field_6368fe5e6aed2',
                            'label' => 'text',
                            'name' => 'text',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'delay' => 1,
                            'tabs' => 'all',
                            'toolbar' => 'full',
                            'media_upload' => 1,
                            'parent_repeater' => 'field_6368fe2a6aed0',
                        ),
                        array(
                            'key' => 'field_6368ffafba9c1',
                            'label' => 'isTextBox',
                            'name' => 'isTextBox',
                            'type' => 'true_false',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '10',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '',
                            'default_value' => 1,
                            'ui' => 0,
                            'ui_on_text' => '',
                            'ui_off_text' => '',
                            'parent_repeater' => 'field_6368fe2a6aed0',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page',
                        'operator' => '==',
                        'value' => '179',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'permalink',
                1 => 'the_content',
                2 => 'excerpt',
                3 => 'discussion',
                4 => 'comments',
                5 => 'revisions',
                6 => 'slug',
                7 => 'author',
                8 => 'format',
                9 => 'page_attributes',
                10 => 'featured_image',
                11 => 'categories',
                12 => 'tags',
                13 => 'send-trackbacks',
            ),
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        acf_add_local_field_group(array(
            'key' => 'group_639d4c9b3d635',
            'title' => '대문페이지',
            'fields' => array(
                array(
                    'key' => 'field_639d4c9b52f6a',
                    'label' => '비디오ID',
                    'name' => 'id',
                    'type' => 'text',
                    'instructions' => '비메오 비디오 ID를 입력해주세요.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page',
                        'operator' => '==',
                        'value' => '1366',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'permalink',
                1 => 'excerpt',
                2 => 'discussion',
                3 => 'comments',
                4 => 'revisions',
                5 => 'slug',
                6 => 'author',
                7 => 'format',
                8 => 'page_attributes',
                9 => 'featured_image',
                10 => 'categories',
                11 => 'tags',
                12 => 'send-trackbacks',
            ),
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        acf_add_local_field_group(array(
            'key' => 'group_6344d688cd3ee',
            'title' => '사이트 설정',
            'fields' => array(
                array(
                    'key' => 'field_6376d3989d62c',
                    'label' => '사이트 전체',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_636922122579a',
                    'label' => '상단메뉴',
                    'name' => 'menu',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_639a7b42aef86',
                            'label' => '메뉴이름',
                            'name' => 'label',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'parent_repeater' => 'field_636922122579a',
                        ),
                        array(
                            'key' => 'field_639a7aee8839b',
                            'label' => '페이지 링크',
                            'name' => 'page',
                            'type' => 'page_link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array(
                                0 => 'page',
                            ),
                            'taxonomy' => '',
                            'allow_archives' => 1,
                            'multiple' => 0,
                            'allow_null' => 0,
                            'parent_repeater' => 'field_636922122579a',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_63763d1329f77',
                    'label' => '전시장 순서',
                    'name' => 'locations',
                    'type' => 'taxonomy',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'taxonomy' => 'location',
                    'add_term' => 0,
                    'save_terms' => 1,
                    'load_terms' => 1,
                    'return_format' => 'object',
                    'field_type' => 'multi_select',
                    'allow_null' => 0,
                    'multiple' => 0,
                ),
                array(
                    'key' => 'field_636940f42894d',
                    'label' => '하단메뉴',
                    'name' => 'footer_menu',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_636941092894e',
                            'label' => 'link',
                            'name' => 'link',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'parent_repeater' => 'field_636940f42894d',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_6482921a8a56e',
                    'label' => '하단메뉴-우측',
                    'name' => 'footer_menu_right',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_6482921a8a56f',
                            'label' => 'link',
                            'name' => 'link',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'parent_repeater' => 'field_6482921a8a56e',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_6376d3c52761a',
                    'label' => '메인페이지',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_6376d36189135',
                    'label' => '메인 슬라이더',
                    'name' => 'main_slider',
                    'type' => 'post_object',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'post_exhibition',
                        1 => 'post_program',
                    ),
                    'taxonomy' => '',
                    'return_format' => 'object',
                    'multiple' => 1,
                    'allow_null' => 0,
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_63981baeb6b98',
                    'label' => '회원가입',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_639819fb8c081',
                    'label' => '이용약관',
                    'name' => 'account-policy',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '제 1 장 총칙
        
        
        
        제 1조 (목적)
        
        
        
        본 약관은 서비스(이하 "회사"라 한다)는 홈페이지에서 제공하는 서비스(이하 "서비스"라 한다)를 제공함에 있어 회사와 이용자의 권리, 의무 및 책임사항을 규정함을 목적으로 합니다.
        
        
        
        제 2조 (용어의 정의)
        
        
        
        1. 본 약관에서 사용하는 용어의 정의는 다음과 같습니다.
        
        \'서비스\'란 회사가 이용자에게 서비스를 제공하기 위하여 컴퓨터 등 정보통신설비를 이용하여 구성한 가상의 공간을 의미하며, 서비스 자체를 의미하기도 합니다.
        
        \'회원(이하 "회원"이라 한다)\'이란 개인정보를 제공하여 회원등록을 한 자로서 홈페이지의 정보를 지속적으로 제공받으며 홈페이지가 제공하는 서비스를 계속적으로 이용할 수 있는 자를 말합니다.
        
        \'아이디(이하 "ID"라 한다)\'란 회원의 식별과 회원의 서비스 이용을 위하여 회원이 선정하고 회사가 승인하는 회원 고유의 계정 정보를 의미합니다.
        
        \'비밀번호\'란 회원이 부여 받은 ID와 일치된 회원임을 확인하고, 회원의 개인정보를 보호하기 위하여 회원이 정한 문자와 숫자의 조합을 의미합니다.
        
        \'회원탈퇴(이하 "탈퇴"라 한다)\'란 회원이 이용계약을 해지하는 것을 의미합니다.
        
        
        
        2. 본 약관에서 사용하는 용어의 정의는 제1항에서 정하는 것을 제외하고는 관계법령 및 서비스 별 안내에서 정하는 바에 의합니다.
        
        
        
        제 3조 (이용약관의 효력 및 변경)
        
        
        
        1. 회사는 본 약관의 내용을 회원이 쉽게 알 수 있도록 각 서비스 사이트의 초기 서비스화면에 게시합니다.
        
        2. 회사는 약관의 규제에 관한 법률, 전자거래기본법, 전자 서명법, 정보통신망 이용촉진 및 정보보호 등에 관한 법률 등 관련법을 위배하지 않는 범위에서 본 약관을 개정할 수 있습니다.
        
        3. 회사는 본 약관을 개정할 경우에는 적용일자 및 개정사유를 명시하여 현행 약관과 함께 회사가 제공하는 서비스 사이트의 초기 화면에 그 적용일자 7일 이전부터 적용일자 전일까지 공지합니다.
        
        다만, 회원에게 불리하게 약관내용을 변경하는 경우에는 최소한 30일 이상의 사전 유예기간을 두고 공지합니다. 이 경우 회사는 개정 전 내용과 개정 후 내용을 명확하게 비교하여 회원이 알기 쉽도록 표시합니다.
        
        4. 회원은 개정된 약관에 대해 거부할 권리가 있습니다. 회원은 개정된 약관에 동의하지 않을 경우 서비스 이용을 중단하고 회원등록을 해지할 수 있습니다.
        
        단, 개정된 약관의 효력 발생일 이후에도 서비스를 계속 이용할 경우에는 약관의 변경사항에 동의한 것으로 간주합니다.
        
        5. 변경된 약관에 대한 정보를 알지 못해 발생하는 회원 피해는 회사가 책임지지 않습니다.
        
        
        
        제 4조 (약관 외 준칙)
        
        
        
        1. 본 약관은 회사가 제공하는 서비스에 관해 별도의 정책 및 운영규칙과 함께 적용됩니다.
        
        2. 본 약관에 명시되지 아니한 사항과 본 약관의 해석에 관하여는 관계법령에 따릅니다.
        
        
        
        제 2 장 이용약관 체결
        
        
        
        제 5조 (이용계약의 성립)
        
        
        
        1. 이용계약은 회원의 본 이용약관 내용에 대한 동의와 이용신청에 대하여 회사의 이용승낙으로 성립합니다.
        
        2. 본 이용약관에 대한 동의는 회원등록 당시 본 약관을 읽고 "위 서비스 약관에 동의합니다"의 대화 창에 표시를 한 후 등록하기 단추를 누름으로써 의사표시를 한 것으로 간주합니다.
        
        
        
        제 6조 (서비스 이용 신청)
        
        
        
        1. 회원으로 가입하여 본 서비스를 이용하고자 하는 이용고객은 회사에서 요청하는 제반 정보(이름, 메일주소, 연락처, 주소 등)를 제공하여야 합니다.
        
        2. 타인의 명의(이름 및 메일주소 등)를 도용하여 이용신청을 한 회원의 모든 ID는 삭제되며, 관계법령에 따라 처벌을 받을 수 있습니다.
        
        3. 만 14세 미만의 아동이 회원으로 가입하기 위해서는 반드시 개인정보의 수집 및 이용목적에 대하여 충분히 숙지하고 법정대리인(부모)의 동의를 받아야 합니다.
        
        법정대리인의 허락을 받지 않은 14세 미만의 아동에 대해서는 회원에서 제외할 수 있습니다.
        
        
        
        제 7조 (개인정보의 보호 및 사용)
        
        
        
        1. 회사는 관계법령이 정하는 바에 따라 회원등록정보를 포함한 회원의 개인정보를 보호하기 위해 노력합니다.
        
        회원 개인 정보의 보호 및 사용에 대해서는 관련법령 및 회사의 개인정보보호정책이 적용됩니다.
        
        단, 회사의 공식사이트 이외의 웹에서 링크된 사이트에서는 회사의 개인정보보호정책이 적용되지 않습니다.
        
        또한 회사는 회원의 귀책사유로 인해 노출된 정보에 대해서 일체의 책임을 지지 않습니다.
        
        2. 회사는 이용자에게 제공하는 서비스의 양적, 질적 향상을 위하여 이용자의 개인정보를 제휴사에게 제공, 공유할 수 있으며, 이 때에는 반드시 이용자의 동의를 받아 필요한 최소한의 정보를 제공, 공유하며 누구에게 어떤 목적으로 어떤 정보를 제공, 공유하는지 명시합니다.
        
        3. 회원은 원하는 경우 언제든 회사에 제공한 개인정보의 수집과 이용에 대한 동의를 철회할 수 있으며, 동의의 철회는 구독해지 회원 탈퇴를 하는 것으로 이루어집니다.
        
        4. 회사는 수탁자를 통해 개별 이벤트, 행사가 진행될 경우, 해당 이벤트 참여 신청 페이지를 통해 명시적으로 개인정보 취급위탁에 대한 회원 동의를 받도록 하겠습니다.
        
        
        
        제 8조 (이용신청의 승낙과 제한)
        
        
        
        1. 회사는 제 6조의 규정에 의거 이용신청고객에 대하여 업무 수행상 또는 기술상 지장이 없는 경우에 원칙적으로 접수순서에 따라 서비스 이용을 승낙합니다.
        
        
        
        2. 회사는 다음 각 호의 내용에 해당하는 경우 이용신청에 대한 승낙을 제한할 수 있고, 그 사유가 해소 될 때까지 승낙을 유보할 수 있습니다.
        
        i.회사의 서비스 관련 설비에 여유가 없는 경우
        
        ii.회사의 기술상 지장이 있는 경우
        
        iii.기타 회사의 사정상 필요하다고 인정되는 경우
        
        
        
        3. 회사는 다음 각 호의 내용에 해당하는 경우 이용신청에 대한 승낙을 하지 아니할 수도 있습니다.
        
        i.실명이 아니거나 타인의 명의를 이용하여 신청한 경우
        
        ii.이용계약 신청서의 내용을 허위로 기재한 경우
        
        iii.사회의 안녕과 질서, 미풍양속을 저해할 목적으로 신청한 경우
        
        iv.부정한 용도로 본 서비스를 이용하고자 하는 경우
        
        v.영리를 추구할 목적으로 본 서비스를 이용하고자 하는 경우
        
        vi.기타 회사가 정한 등록신청 요건이 미비된 경우
        
        vii.본 서비스와 경쟁관계가 있는 이용자가 신청하는 경우
        
        viii.기타 규정한 제반 사항을 위반하며 신청하는 경우
        
        
        
        4. 회사는 이용신청고객이 관계법령에서 규정하는 미성년자일 경우에 서비스 별 안내에서 정하는 바에 따라 승낙을 보류할 수 있습니다.
        
        
        
        제 9조 (회원 아이디 부여 및 변경 등)
        
        
        
        1. 회사는 회원에 대하여 본 약관에 정하는 바에 따라 회원 ID를 부여합니다.
        
        2. 회원이 원할 경우 회원 ID는 변경이 가능하며, 이 때 새로운 회원 ID는 다른 회원의 ID와 중복되지 않아야 합니다.
        
        3. 회원은 해당 홈페이지로 링크된 메뉴를 통하여 자신의 개인정보를 관리할 수 있는 페이지를 열람할 수 있으며, 해당 페이지에서 언제든지 본인의 개인정보를 열람하고 수정할 수 있습니다.
        
        4. 회사가 제공하는 서비스의 회원 ID는 회원 본인의 동의 하에 회사가 운영하는 자사 사이트의 회원 ID와 연결될 수 있습니다.
        
        5. 회원 ID는 다음 각 호에 해당하는 경우에 회원 또는 회사의 요청으로 변경할 수 있습니다.
        
        i.회원 ID가 회원의 전화번호 또는 주민등록번호 등으로 등록되어 사생활 침해가 우려되는 경우
        
        ii.타인에게 혐오감을 주거나 미풍양속에 어긋나는 경우
        
        iii.기타 합리적인 사유가 있는 경우
        
        6. 회원 ID 및 비밀번호의 관리책임은 회원에게 있습니다. 이를 소홀이 관리하여 발생하는 서비스 이용상의 손해 또는 제3자에 의한 부정이용 등에 대한 책임은 회원에게 있으며 회사는 그에 대한 책임을 일절 지지 않습니다.
        
        7. 기타 회원개인정보 관리 및 변경에 관한 사항은 서비스 별 안내에 정하는 바에 의합니다.
        
        
        
        제3장 계약 당사자의 의무
        
        
        
        제 10조 (회사의 의무)
        
        
        
        1. 회사는 관련법과 본 약관이 금지하거나 공서양속에 반하는 행위를 하지 않으며, 본 약관이 정하는 바에 따라 지속적이고, 안정적으로 서비스를 제공하기 위하여 최선을 다하여야 합니다.
        
        2. 회사는 회원이 안전하게 서비스를 이용할 수 있도록 회원의 개인정보보호를 위한 보안시스템을 구축하며 개인정보 보호정책을 공시하고 준수합니다.
        
        3. 회사는 수신거절의 의사를 명백히 표시한 회원에 대해서는 회원이 원하지 않는 영리목적의 광고성 전자우편(이메일)을 발송하지 않습니다.
        
        4. 회사는 이용계약의 체결, 계약사항의 변경 및 해지 등 회원과의 계약관계절차 및 내용 등에 있어 회원에게 편의를 제공하도록 노력합니다. 회사 관리자에게 회원이 구독해지 신청메일을 보낼 경우 회원 본인임을 확인한 후 즉시 구독해지 처리를 합니다.
        
        5. 회사는 이용고객으로부터 제기되는 의견이나 불만이 정당하다고 객관적으로 인정될 경우에는 적절한 절차를 거쳐 즉시 처리하여야 합니다.
        
        다만, 즉시 처리가 곤란한 경우는 이용자에게 그 사유와 처리 일정을 통보하여야 합니다.
        
        
        
        제 11조 (회원 ID 및 비밀번호에 대한 의무)
        
        
        
        1. 회사가 관계법령 및 개인정보보호정책에 의하여 그 책임을 지는 경우를 제외하고, 회원 ID와 비밀번호의 관리책임은 회원에게 있습니다.
        
        2. 회원은 자신의 ID 및 비밀번호를 제3자가 이용하게 해서는 안됩니다.
        
        3. 회원이 자신의 ID 및 비밀번호를 도용 당하거나 제3자가 사용하고 있음을 인지한 경우에는 바로 회사에 통보하고 회사의 안내가 있는 경우에는 그에 따라야 합니다.
        
        4. 회사는 회원이 상기 제1항, 제2항, 제3항을 위반하여 회원에게 발생한 손해에 대하여 책임을 일절 지지 않습니다.
        
        
        
        제 12조 (회원의 의무)
        
        
        
        1. 회원은 본 약관에 규정하는 사항과 기타 회사가 정한 제반 규정, 서비스 이용안내 또는 공지사항 등 회사가 공지 또는 통지하는 사항 및 관계법령을 준수하여야 하며, 기타 회사의 업무에 방해가 되는 행위, 회사의 명예를 손상 시키는 행위를 하여서는 안됩니다.
        
        2. 회사가 관계법령 및 개인정보보호정책에 의하여 그 책임을 지는 경우를 제외하고 제11조의 관리소홀, 부정사용에 의하여 발생되는 모든 결과에 대한 책임은 회원에게 있습니다.
        
        3. 회원은 회사의 사전승낙 없이 서비스를 이용하여 영업활동을 할 수 없으며, 회원 간 또는 회원과 제3자 간에 서비스를 매개로 한 물품거래 및 서비스의 이용과 관련하여 기대하는 이익 등에 관하여 발생한 손해에 대하여 회사는 책임을 지지 않습니다. 이와 같은 영업활동으로 회사가 손해를 입은 경우 회원은 회사에 대하여 손해배상의 의무를 지며, 회사는 해당 회원에 대해 서비스 이용제한 및 적법한 절차를 거쳐 손해배상 등을 청구할 수 있습니다.
        
        4. 회원은 회사의 명시적인 동의가 없는 한 서비스의 이용권한, 기타 이용계약 상의 지위를 타인에게 양도, 증여할 수 없으며, 이를 담보로 제공할 수 없습니다.
        
        5. 회원은 서비스 이용과 관련하여 제22조 제1항의 각 호에 해당하는 행위를 하여서는 안됩니다.
        
        6. 이용자는 회원가입 신청 또는 회원정보 변경 시 모든 사항을 사실에 근거하여 작성하여야 하며, 허위 또는 타인의 정보를 등록할 경우 일체의 권리를 주장할 수 없습니다.
        
        7. 회원은 주소, 연락처, 전자우편 주소 등 이용계약사항이 변경된 경우에 해당 절차를 거쳐 이를 회사에 즉시 알려야 합니다.
        
        8. 회원은 회사 및 제 3자의 지적 재산권을 침해해서는 안됩니다.
        
        9. 회원은 다음 각 호에 해당하는 행위를 하여서는 안되며, 해당 행위를 하는 경우에 회사는 회원의 서비스 이용제한 및 적법 조치를 포함한 제재를 가할 수 있습니다.
        
        i.회원가입 신청 또는 회원정보 변경 시 허위내용을 등록하는 행위
        
        ii.다른 이용자의 ID, 비밀번호, 주민등록번호를 도용하는 행위
        
        iii.이용자 ID를 타인과 거래하는 행위
        
        iv.회사의 운영진, 직원 또는 관계자를 사칭하는 행위
        
        v.회사로부터 특별한 권리를 부여 받지 않고 회사의 클라이언트 프로그램을 변경하거나, 회사의 서버를 해킹하거나, 웹사이트 또는 게시된 정보의 일부분 또는 전체를 임의로 변경하는 행위
        
        vi.서비스에 위해를 가하거나 고의로 방해하는 행위
        
        vii.본 서비스를 통해 얻은 정보를 회사의 사전 승낙 없이 서비스 이용 외의 목적으로 복제하거나, 이를 출판 및 방송 등에 사용하거나, 제 3자에게 제공하는 행위
        
        viii.회사 또는 제 3자의 저작권 등 기타 지적재산권을 침해하는 내용을 전송, 게시, 전자우편 또는 기타의 방법으로 타인에게 유포하는 경우
        
        ix.공공질서 및 미풍양속에 위반되는 저속, 음란한 내용의 정보, 문장, 도형, 음향, 동영상을 전송, 게시, 전자우편 또는 기타의 방법으로 타인에게 유포하는 행위
        
        x.모욕적이거나 개인신상에 대한 내용이어서 타인의 명예나 프라이버시를 침해할 수 있는 내용을 전송, 게시, 전자우편 또는 기타의 방법으로 타인에게 유포하는 행위
        
        xi.다른 이용자를 희롱 또는 위협하거나, 특정 이용자에게 지속적으로 고통 또는 불편을 주는 행위
        
        xii.회사의 승인을 받지 않고 다른 사용자의 개인정보를 수집 또는 저장하는 행위
        
        xiii.범죄와 결부된다고 객관적으로 판단되는 행위
        
        xiv.본 약관을 포함하여 기타 회사가 정한 제반 규정 또는 이용 조건을 위반하는 행위
        
        xv.기타 관계법령에 위배되는 행위
        
        
        
        제 4 장 서비스 이용
        
        
        
        제 13조 (서비스의 제공 및 변경)
        
        
        
        1. 회사는 회사가 제공하는 서비스에서 진행하는 콘텐츠와 이벤트 등의 모든 서비스를 회원에게 제공합니다.
        
        2. 회사에서 제공하는 서비스는 기본적으로 무료입니다. 유료서비스 추가 시 사전 공지하며 이용에 대한 사항은 회사가 별도로 정한 서비스 약관 및 정책 또는 운영규칙에 따릅니다.
        
        3. 회사는 서비스 변경 시 그 변경될 서비스의 내용 및 제공일자를 제 14조에 정한 방법으로 회원에게 통지합니다.
        
        
        
        제 14조 (정보의 제공 및 통지)
        
        
        
        1. 회사는 회원이 서비스 이용 중 필요하다고 인정되는 다양한 정보를 공지사항 혹은 전자우편 등의 방법으로 회원에게 제공할 수 있습니다.
        
        2. 회사는 불특정다수 회원에 대한 통지를 하는 경우 7일 이상 공지 게시판에 게시함으로써 개별 통지에 갈음할 수 있습니다.
        
        
        
        제 15조 (게시물의 저작권 및 관리)
        
        
        
        1. 회사는 회원의 게시물을 소중하게 생각하며 변조, 훼손, 삭제되지 않도록 최선을 다하여 보호합니다.
        
        다만, 다음 각 호에 해당하는 게시물이나 자료의 경우 사전통지 없이 삭제하거나 이동 또는 등록 거부를 할 수 있으며, 해당 회원의 자격을 제한, 정지 또는 상실시킬 수 있습니다.
        
        i.다른 회원 또는 제3자에게 심한 모욕을 주거나 명예를 손상시키는 내용인 경우
        
        ii.공공질서 및 미풍양속에 위반되는 내용을 유포하거나 링크시키는 경우
        
        iii.불법복제 또는 해킹을 조장하는 내용인 경우
        
        iv.영리를 목적으로 하는 광고일 경우
        
        v.범죄적 행위에 결부된다고 인정되는 내용인 경우
        
        vi.회사나 다른 회원의 저작권 혹은 제3자의 저작권 등 기타 권리를 침해하는 내용인 경우
        
        vii.회사에서 규정한 게시물 원칙에 어긋나거나, 게시판 성격에 부합하지 않는 경우
        
        viii.스팸성 게시물인 경우
        
        ix.기타 관계법령에 위배된다고 판단되는 경우
        
        2. 회사가 작성한 저작물에 대한 저작권 및 기타 지적재산권은 회사에 귀속됩니다.
        
        3. 회원이 서비스 화면 내에 게시한 게시물의 저작권은 게시한 회원에게 귀속됩니다. 또한 회사는 게시자의 동의 없이 게시물을 상업적으로 이용할 수 없습니다.
        
        다만, 비영리 목적인 경우는 그러하지 아니하며, 또한 본 사이트 내에서의 게재권을 갖습니다.
        
        4. 회원은 서비스를 이용하여 취득한 정보를 회사의 사전 승낙 없이 임의가공, 판매, 복제, 송신, 출판, 배포, 방송 기타 방법에 의하여 영리목적으로 이용하거나 제3자에게 이용하게 하여서는 안됩니다.
        
        
        
        제 16조 (광고게재 및 광고주와의 거래)
        
        
        
        1. 회사가 제공하는 서비스 내에 다양한 배너와 링크(Link)를 포함할 수 있으며, 이는 광고주와의 계약관계에 의하거나 제공받은 콘텐츠의 출처를 밝히기 위한 조치입니다.
        
        회원은 서비스 이용 시 노출되는 광고게재에 대해 동의합니다.
        
        2. 서비스 내에 포함되어 있는 링크를 클릭하여 타 사이트의 페이지로 옮겨갈 경우 해당 사이트의 개인정보보호정책은 회사와 무관합니다.
        
        
        
        제 17조 (서비스 이용시간)
        
        
        
        1. 서비스 이용은 회사의 업무상 또는 기술상 특별한 지장이 없는 한 연중무휴, 1일 24시간 운영을 원칙으로 합니다. 다만, 회사는 시스템 정기점검, 증설 및 교체를 위해 회사가 정한 날이나 시간에 서비스를 일시 중단할 수 있으며, 예정되어 있는 작업으로 인한 서비스 일시 중단은 회사가 제공하는 서비스를 통해 사전에 공지합니다.
        
        2. 회사는 서비스를 일정 범위로 분할하여 각 범위 별로 이용가능시간을 별도로 지정할 수 있습니다. 다만, 이 경우 그 내용을 공지합니다.
        
        
        
        제 18조 (서비스 제공의 중단 등)
        
        
        
        1. 회사는 다음 각 호의 내용에 해당하는 경우 서비스 제공의 일부 혹은 전부를 제한하거나 중단할 수 있습니다.
        
        i.정보통신설비의 보수 점검, 교체 및 고장 등 공사로 인한 부득이 한 경우
        
        ii.기간통신사업자가 전기통신서비스를 중단했을 경우
        
        iii.서비스 이용의 폭주 등으로 정상적인 서비스 이용에 지장이 있는 경우
        
        iv.국가비상사태 등 기타 불가항력적인 사유가 있는 경우
        
        2. 전항에 의한 서비스 중단의 경우에는 회사는 제14조에 정한 방법으로 그 사유 및 기간 등을 공지합니다. 다만, 회사가 통제할 수 없는 사유로 발생한 서비스의 중단 (시스템관리자의 고의, 과실 없는 디스크 장애, 시스템다운 등)에 대하여 사전공지가 불가능한 경우에는 예외로 합니다.
        
        
        
        제 19조 (회원 ID 관리)
        
        
        
        1. 회원 ID와 비밀번호에 관한 모든 관리책임은 회원에게 있습니다.
        
        2. 회사는 회원 ID에 의하여 제반 회원 관리업무를 수행하므로 회원이 ID를 변경하고자 하는 경우 회사가 인정할 만한 사유가 없는 한 ID의 변경을 제한할 수 있습니다.
        
        3. 회원이 등록한 회원 ID 및 비밀번호에 의하여 발생되는 사용상의 과실 또는 제3자에 의한 부정사용 등에 대한 모든 책임은 해당 이용고객에게 있습니다.
        
        
        
        제 20조 (정보의 제공)
        
        
        
        1. 회사는 회원에게 서비스 이용에 필요가 있다고 인정되는 각종 정보에 대해서 전자우편이나 서신우편 등의 방법으로 회원에게 제공할 수 있습니다.
        
        2. 회사는 서비스 개선 및 회원 대상의 서비스 소개 등의 목적으로 회원의 동의 하에 추가적인 개인 정보를 요구할 수 있습니다.
        
        
        
        제5장 계약해지 및 이용제한
        
        
        
        제 21조 (계약 변경 및 해지)
        
        
        
        회원이 이용계약을 해지하고자 하는 경우에는 회원 본인이 사이트 내의 개인정보관리 페이지를 통해 등록해지 신청을 하여야 합니다.
        
        
        
        제 22조 (서비스 이용 제한)
        
        
        
        1. 회원은 본 약관 제11조, 제12조 내용을 위반하거나 다음 각 호에 해당하는 행위를 하는 경우에 회사는 회원의 서비스 이용을 제한할 수 있습니다.
        
        i.미풍양속을 저해하는 비속한 ID 및 별명 사용
        
        ii.타 이용자에게 심한 모욕을 주거나, 서비스 이용을 방해한 경우
        
        iii.정보통신 윤리위원회 등 관련 공공기관의 시정 요구가 있는 경우
        
        iv.3개월 이상 서비스를 이용한 적이 없는 경우
        
        v.불법 게시물을 게재한 경우
        
        • 상용소프트웨어나 크랙파일을 올린 경우
        
        • 정보통신윤리위원회의 심의세칙 제 7조에 어긋나는 음란물을 게재한 경우
        
        • 반국가적 행위의 수행을 목적으로 하는 내용을 포함한 경우
        
        • 저작권이 있는 글을 무단 복제하거나 mp3를 게재한 경우
        
        vi.기타 정상적인 서비스 운영에 방해가 될 경우
        
        2. 상기 이용제한 규정에 따라 서비스를 이용하는 회원에게 서비스 이용에 대하여 별도 공지 없이 서비스 이용의 일시 정지, 초기화, 이용계약 해지 등을 불량이용자 처리규정에 따라 취할 수 있으며, 회원은 전항의 귀책사유로 인하여 회사나 다른 회원에게 입힌 손해를 배상할 책임이 있습니다.
        
        
        
        제 6 장 손해배상 및 기타사항
        
        
        
        제 23조 (손해배상)
        
        
        
        회사는 무료로 제공하는 서비스의 이용과 관련하여 개인정보보호정책에서 정하는 내용에 해당하지 않는 사항에 대하여는 어떠한 손해도 책임을 지지 않습니다.
        
        
        
        제 24조 (면책조항)
        
        
        
        1. 회사는 천재지변, 전쟁 및 기타 불가항력, 회사의 합리적인 통제범위를 벗어난 사유로 인하여 서비스를 제공할 수 없는 경우에는 그에 대한 책임이 면제됩니다.
        
        2. 회사는 기간통신 사업자가 전기통신 서비스를 중지하거나 정상적으로 제공하지 아니하여 손해가 발생한 경우 책임이 면제됩니다.
        
        3. 회사는 서비스용 설비의 보수, 교체, 정기점검, 공사 등 부득이한 사유로 발생한 손해에 대한 책임이 면제됩니다.
        
        4. 회사는 이용자의 귀책사유로 인한 서비스 이용의 장애 또는 손해에 대하여 책임을 지지 않습니다.
        
        5. 회사는 이용자의 컴퓨터 오류에 의해 손해가 발생한 경우, 또는 회원이 신상정보 및 전자우편 주소를 부실하게 기재하여 손해가 발생한 경우 책임을 지지 않습니다.
        
        6. 회사는 회원이 서비스를 이용하여 기대하는 수익을 얻지 못하거나 상실한 것에 대하여 책임을 지지 않습니다.
        
        
        
        제 25조 (분쟁의 해결)
        
        
        
        1. 회사와 회원은 서비스와 관련하여 발생한 분쟁을 원만하게 해결하기 위하여 필요한 노력을 합니다.
        
        2. 회사는 회원으로부터 제출되는 불만사항 및 의견을 우선적으로 처리합니다. 다만, 신속한 처리가 곤란한 경우에는 회원에게 그 사유와 처리일정을 즉시 통보합니다.
        
        
        
        제 26조 (재판권 및 준거법)
        
        
        
        회사와 회원 간에 서비스 이용으로 발생한 분쟁에 대하여는 대한민국법을 적용하며, 본 분쟁으로 인하여 소송이 제기될 경우 민사소송법 상의 관할을 가지는 대한민국의 법원에 제기합니다.',
                    'maxlength' => '',
                    'rows' => '',
                    'placeholder' => '이용약관',
                    'new_lines' => 'br',
                ),
                array(
                    'key' => 'field_63981a278c082',
                    'label' => '개인정보취급방침',
                    'name' => 'account-policy_2',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '1. 개인정보 수집 및 이용 목적
        
        - 회원가입 의사의 확인, 이용자 본인여부 확인, 이용자 식별, 회원 탈퇴 의사의 확인 등 회원관리
        
        - 법령 및 이용약관을 위반하는 회원에 대한 이용 제한 조치, 부정 이용 행위를 포함하여 서비스의 원활한 운영에 지장을 주는 행위에 대한 방지 및 제재, 계정도용 및 부정거래 방지, 약관 개정 등의 고지사항 전달, 분쟁조정을 위한 기록 보존, 민원처리 등 이용자 보호 및 서비스 운영
        
        2. 개인정보 수집 항목
        
        - 이름, 아이디(이메일), 비밀번호, 휴대폰번호, 주소 정보
        
        ※ PC 웹, 모바일 웹/앱을 통한 서비스 이용 과정에서 모바일앱 바코드번호, IP 주소, 쿠키, 방문일시, 서비스 이용기록, 모바일 기기정보(UUID, 광고식별자 등)가 기기식별, 장애대응, 서비스 이용 통계를 위해 자동 생성되어 수집될 수 있습니다. 이와 같이 수집된 정보는 개인정보와의 연계 여부 등에 따라 개인정보에 해당할 수도 있고, 개인정보에 해당하지 않을 수도 있습니다.
        
        3. 개인정보 보유 및 이용기간
        
        - 회원 탈퇴 시 또는 법정 의무 보유기간 종료 시로부터 7영업일 이내
        
        ※ 회원은 위와 같은 개인정보의 수집 및 이용을 거부할 수 있습니다. 단, 개인정보의 수집 및 이용에 동의하지 않을 경우 회원가입이 불가합니다.',
                    'maxlength' => '',
                    'rows' => '',
                    'placeholder' => '이용약관',
                    'new_lines' => 'br',
                ),
                array(
                    'key' => 'field_64893545b4b5a',
                    'label' => '맴버쉽',
                    'name' => 'membership',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_6489354fb4b5b',
                            'label' => '이름',
                            'name' => 'title',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'parent_repeater' => 'field_64893545b4b5a',
                        ),
                        array(
                            'key' => 'field_64893563b4b5c',
                            'label' => '가격',
                            'name' => 'price',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'parent_repeater' => 'field_64893545b4b5a',
                        ),
                        array(
                            'key' => 'field_6489357ab4b5d',
                            'label' => '내용',
                            'name' => 'content',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'delay' => 0,
                            'tabs' => 'all',
                            'toolbar' => 'full',
                            'media_upload' => 1,
                            'parent_repeater' => 'field_64893545b4b5a',
                        ),
                        array(
                            'key' => 'field_64893586b4b5e',
                            'label' => '상품',
                            'name' => 'product',
                            'type' => 'post_object',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array(
                                0 => 'product',
                            ),
                            'taxonomy' => array(
                                0 => 'product_type:subscription',
                            ),
                            'return_format' => 'object',
                            'multiple' => 0,
                            'allow_null' => 0,
                            'ui' => 1,
                            'parent_repeater' => 'field_64893545b4b5a',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64c20b315b67c',
                    'label' => '아카이브',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64c20b4081e6c',
                    'label' => '아카이브 목록',
                    'name' => 'archives',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64c20c599079a',
                            'label' => '정보',
                            'name' => 'info',
                            'type' => 'group',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'layout' => 'block',
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_64c20b4881e6d',
                                    'label' => '프로젝트 이름',
                                    'name' => 'title',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '33',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'maxlength' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                ),
                                array(
                                    'key' => 'field_64c20b5781e6e',
                                    'label' => '행사 이름',
                                    'name' => 'project_name',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '33',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'maxlength' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                ),
                                array(
                                    'key' => 'field_64c20bd7b6e8d',
                                    'label' => '시작일',
                                    'name' => 'date_start',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '50',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'maxlength' => '',
                                    'placeholder' => '0000.00.00',
                                    'prepend' => '',
                                    'append' => '',
                                ),
                                array(
                                    'key' => 'field_64c20d2a2c02f',
                                    'label' => '종료일',
                                    'name' => 'date_end',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '50',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'maxlength' => '',
                                    'placeholder' => '0000.00.00',
                                    'prepend' => '',
                                    'append' => '',
                                ),
                                array(
                                    'key' => 'field_64c20bcbb6e8c',
                                    'label' => '본문설명',
                                    'name' => 'desc',
                                    'type' => 'textarea',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'maxlength' => '',
                                    'rows' => '',
                                    'placeholder' => '',
                                    'new_lines' => 'br',
                                ),
                            ),
                            'parent_repeater' => 'field_64c20b4081e6c',
                        ),
                        array(
                            'key' => 'field_64c20b7481e6f',
                            'label' => '갤러리',
                            'name' => 'gallery',
                            'type' => 'gallery',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'library' => 'all',
                            'min' => '',
                            'max' => '',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                            'insert' => 'append',
                            'preview_size' => 'medium',
                            'parent_repeater' => 'field_64c20b4081e6c',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-general-settings',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        acf_add_local_field_group(array(
            'key' => 'group_6369a1ce0c825',
            'title' => '전시',
            'fields' => array(
                array(
                    'key' => 'field_63a160feb2161',
                    'label' => '이미지 설정',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_63766292ac188',
                    'label' => '썸네일',
                    'name' => 'thumb',
                    'type' => 'image',
                    'instructions' => '권장사이즈 : 760 x 1020',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'preview_size' => 'gallery_thumb',
                ),
                array(
                    'key' => 'field_63a27c08b7431',
                    'label' => '대표색상',
                    'name' => 'color',
                    'type' => 'color_picker',
                    'instructions' => '권장사이즈 : 760 x 1020',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '#000',
                    'enable_opacity' => 0,
                    'return_format' => 'string',
                ),
                array(
                    'key' => 'field_63a16297d8bc3',
                    'label' => '메인페이지 - 하이라이트 슬라이더',
                    'name' => 'thumb_main_slider',
                    'type' => 'image',
                    'instructions' => '권장사이즈 : 3840 x 2160',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_649136839bbda',
                    'label' => '상세이미지 - 전시 포스터',
                    'name' => 'poster',
                    'type' => 'image',
                    'instructions' => '권장사이즈 : 3840 x 2160',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_637662b3ac189',
                    'label' => '상세이미지',
                    'name' => 'imgs',
                    'type' => 'gallery',
                    'instructions' => '권장사이즈 : 2400 x 960',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min' => '',
                    'max' => '',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'insert' => 'append',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_63a161141507b',
                    'label' => '전시정보',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_63763ab1149d3',
                    'label' => '장소',
                    'name' => 'location',
                    'type' => 'taxonomy',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'taxonomy' => 'location',
                    'add_term' => 0,
                    'save_terms' => 1,
                    'load_terms' => 1,
                    'return_format' => 'object',
                    'field_type' => 'radio',
                    'allow_null' => 0,
                    'multiple' => 0,
                ),
                array(
                    'key' => 'field_64361915b4a25',
                    'label' => '장소 텍스트',
                    'name' => 'location_text',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_6376690e366e5',
                    'label' => '예약하기 링크',
                    'name' => 'book',
                    'type' => 'url',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_6376697f59839',
                    'label' => '전시 설명',
                    'name' => 'desc',
                    'type' => 'wysiwyg',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'delay' => 0,
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                ),
                array(
                    'key' => 'field_6369a1cef8585',
                    'label' => '시작일',
                    'name' => 'start',
                    'type' => 'date_picker',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'display_format' => 'Y.m.d',
                    'return_format' => 'Y.m.d',
                    'first_day' => 1,
                ),
                array(
                    'key' => 'field_6369a20df8586',
                    'label' => '종료일',
                    'name' => 'end',
                    'type' => 'date_picker',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'display_format' => 'Y.m.d',
                    'return_format' => 'Y.m.d',
                    'first_day' => 1,
                ),
                array(
                    'key' => 'field_6369a21ff8588',
                    'label' => '관람료',
                    'name' => 'price',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_6369a223f8589',
                    'label' => '참여작가',
                    'name' => 'artists',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'placeholder' => '',
                    'new_lines' => '',
                ),
                array(
                    'key' => 'field_6369a22af858a',
                    'label' => '작가 정보다운로드',
                    'name' => 'download-artist',
                    'type' => 'file',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min_size' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array(
                    'key' => 'field_6369a239f858b',
                    'label' => '보도자료',
                    'name' => 'download-news',
                    'type' => 'file',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min_size' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array(
                    'key' => 'field_6369a241f858c',
                    'label' => '연계 프로그램',
                    'name' => 'relative-program',
                    'type' => 'post_object',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'post_program',
                    ),
                    'taxonomy' => '',
                    'return_format' => 'object',
                    'multiple' => 1,
                    'allow_null' => 0,
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_6369a284f858d',
                    'label' => '연계 도록',
                    'name' => 'relative-publish',
                    'type' => 'post_object',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'post_book',
                    ),
                    'taxonomy' => '',
                    'return_format' => 'object',
                    'multiple' => 1,
                    'allow_null' => 0,
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_6369a2a4f858e',
                    'label' => '관련 기사',
                    'name' => 'news',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_6369a2b9f858f',
                            'label' => '링크 및 제목',
                            'name' => 'link',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'parent_repeater' => 'field_6369a2a4f858e',
                        ),
                        array(
                            'key' => 'field_6369a2caf8590',
                            'label' => '출처',
                            'name' => 'author',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'parent_repeater' => 'field_6369a2a4f858e',
                        ),
                        array(
                            'key' => 'field_6376614a51208',
                            'label' => '날짜',
                            'name' => 'date',
                            'type' => 'date_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'display_format' => 'Y.m.d',
                            'return_format' => 'Y.m.d',
                            'first_day' => 1,
                            'parent_repeater' => 'field_6369a2a4f858e',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_63766865e6eba',
                    'label' => '라이센스',
                    'name' => 'license',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_6392f16319fbd',
                    'label' => '상설전시 여부',
                    'name' => 'permanent',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'post_exhibition',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'permalink',
                1 => 'the_content',
                2 => 'excerpt',
                3 => 'discussion',
                4 => 'comments',
                5 => 'revisions',
                6 => 'slug',
                7 => 'author',
                8 => 'format',
                9 => 'page_attributes',
                10 => 'featured_image',
                11 => 'categories',
                12 => 'tags',
                13 => 'send-trackbacks',
            ),
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        acf_add_local_field_group(array(
            'key' => 'group_63766a26ce607',
            'title' => '프로그램',
            'fields' => array(
                array(
                    'key' => 'field_63a1638881b5d',
                    'label' => '이미지 설정',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_637671cf58007',
                    'label' => '대표색상',
                    'name' => 'color',
                    'type' => 'color_picker',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'enable_opacity' => 0,
                    'return_format' => 'string',
                ),
                array(
                    'key' => 'field_63766a26da200',
                    'label' => '썸네일',
                    'name' => 'thumb',
                    'type' => 'image',
                    'instructions' => '권장 사이즈 : 760 x 1020
        
        * 이 이미지는 해당 프로그램의 기본 이미지 입니다.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_63a1640381b5f',
                    'label' => '메인 페이지 - 하이라이트',
                    'name' => 'thumb_main_slider',
                    'type' => 'image',
                    'instructions' => '권장 사이즈 : 3840 x 2160',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_63766a26ddca4',
                    'label' => '상세이미지',
                    'name' => 'imgs',
                    'type' => 'gallery',
                    'instructions' => '권장사이즈 : 2400 x 960',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min' => '',
                    'max' => '',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'insert' => 'append',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_63a1639081b5e',
                    'label' => '프로그램 정보',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_63766a26d6761',
                    'label' => '장소',
                    'name' => 'location',
                    'type' => 'taxonomy',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'taxonomy' => 'location',
                    'add_term' => 0,
                    'save_terms' => 1,
                    'load_terms' => 1,
                    'return_format' => 'object',
                    'field_type' => 'radio',
                    'allow_null' => 0,
                    'multiple' => 0,
                ),
                array(
                    'key' => 'field_637670ef9b709',
                    'label' => '전시테마',
                    'name' => 'program_theme',
                    'type' => 'taxonomy',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'taxonomy' => 'program_theme',
                    'add_term' => 0,
                    'save_terms' => 1,
                    'load_terms' => 1,
                    'return_format' => 'object',
                    'field_type' => 'radio',
                    'allow_null' => 0,
                    'multiple' => 0,
                ),
                array(
                    'key' => 'field_637671119b70a',
                    'label' => '자격요건',
                    'name' => 'job',
                    'type' => 'taxonomy',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                    'taxonomy' => 'job',
                    'add_term' => 0,
                    'save_terms' => 1,
                    'load_terms' => 1,
                    'return_format' => 'object',
                    'field_type' => 'radio',
                    'allow_null' => 0,
                    'multiple' => 0,
                ),
                array(
                    'key' => 'field_63766a26e1766',
                    'label' => '신청하기 링크',
                    'name' => 'book-program',
                    'type' => 'url',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_63766a26e8de2',
                    'label' => '프로그램 설명',
                    'name' => 'desc',
                    'type' => 'wysiwyg',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'delay' => 0,
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                ),
                array(
                    'key' => 'field_63766a26ecae7',
                    'label' => '시작일',
                    'name' => 'start',
                    'type' => 'date_picker',
                    'instructions' => '하루인 경우 이곳만 작성하시면 됩니다.',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'display_format' => 'Y.m.d',
                    'return_format' => 'Y.m.d',
                    'first_day' => 1,
                ),
                array(
                    'key' => 'field_63766a26f05bb',
                    'label' => '종료일',
                    'name' => 'end',
                    'type' => 'date_picker',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'display_format' => 'Y.m.d',
                    'return_format' => 'Y.m.d',
                    'first_day' => 1,
                ),
                array(
                    'key' => 'field_63766b14a8302',
                    'label' => '정보',
                    'name' => 'meta',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_63766b27a8303',
                            'label' => '항목',
                            'name' => 'title',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'parent_repeater' => 'field_63766b14a8302',
                        ),
                        array(
                            'key' => 'field_63766b36a8304',
                            'label' => '내용',
                            'name' => 'desc',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'parent_repeater' => 'field_63766b14a8302',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64820008ece3c',
                    'label' => '상세정보',
                    'name' => 'detail',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'placeholder' => '',
                    'new_lines' => '',
                ),
                array(
                    'key' => 'field_648200cea8149',
                    'label' => '상세자료',
                    'name' => 'uploads',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_648200e6a814a',
                            'label' => '자료',
                            'name' => 'item',
                            'type' => 'file',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'library' => 'all',
                            'min_size' => '',
                            'max_size' => '',
                            'mime_types' => '',
                            'parent_repeater' => 'field_648200cea8149',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64891e1b91d2d',
                    'label' => '상품정보연동',
                    'name' => 'product',
                    'type' => 'post_object',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'product',
                    ),
                    'taxonomy' => array(
                        0 => 'product_type:simple',
                    ),
                    'return_format' => 'object',
                    'multiple' => 0,
                    'allow_null' => 1,
                    'ui' => 1,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'post_program',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'permalink',
                1 => 'the_content',
                2 => 'excerpt',
                3 => 'discussion',
                4 => 'comments',
                5 => 'revisions',
                6 => 'slug',
                7 => 'author',
                8 => 'format',
                9 => 'page_attributes',
                10 => 'featured_image',
                11 => 'categories',
                12 => 'tags',
                13 => 'send-trackbacks',
            ),
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        acf_add_local_field_group(array(
            'key' => 'group_6376722f5cb07',
            'title' => '프로그램 컬러',
            'fields' => array(
                array(
                    'key' => 'field_6376722f47436',
                    'label' => '색상',
                    'name' => 'color',
                    'type' => 'color_picker',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'enable_opacity' => 0,
                    'return_format' => 'string',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'colorchip',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        acf_add_local_field_group(array(
            'key' => 'group_6482d44b88234',
            'title' => '활동사진',
            'fields' => array(
                array(
                    'key' => 'field_6482d44b53ede',
                    'label' => '설명',
                    'name' => 'desc',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_6482d45853edf',
                    'label' => '갤러리',
                    'name' => 'gallery',
                    'type' => 'gallery',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'library' => 'all',
                    'min' => '',
                    'max' => '',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'insert' => 'append',
                    'preview_size' => 'medium',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'post_activity',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'permalink',
                1 => 'the_content',
                2 => 'excerpt',
                3 => 'discussion',
                4 => 'comments',
                5 => 'revisions',
                6 => 'slug',
                7 => 'author',
                8 => 'format',
                9 => 'page_attributes',
                10 => 'featured_image',
                11 => 'categories',
                12 => 'tags',
                13 => 'send-trackbacks',
            ),
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        acf_add_local_field_group(array(
            'key' => 'group_63942ea198399',
            'title' => '회원정보',
            'fields' => array(
                array(
                    'key' => 'field_63942ea2fd208',
                    'label' => '전화번호',
                    'name' => 'tel',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => 11,
                    'placeholder' => '전화번호',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_63942f09fd209',
                    'label' => '주소',
                    'name' => 'address',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_63942f5cfd20a',
                    'label' => '성별',
                    'name' => 'gender',
                    'type' => 'radio',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'male' => '남',
                        'female' => '여',
                    ),
                    'default_value' => 'male',
                    'return_format' => 'value',
                    'allow_null' => 0,
                    'other_choice' => 0,
                    'layout' => 'horizontal',
                    'save_other_choice' => 0,
                ),
                array(
                    'key' => 'field_639f096d66824',
                    'label' => '생년월일',
                    'name' => 'birth',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_63a0a187554f1',
                    'label' => '회원유형',
                    'name' => 'type',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'user_form',
                        'operator' => '==',
                        'value' => 'all',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
        endif;		
?>