<?php
    $name = $arg["section"] ?? "";
    $is_open = $arg["is_open"] ?? false;

    // get post name
    $post_name = get_post_field( "post_name", get_the_ID() );
    if($post_name !== "portfolio") $name = $post_name;
?>
<div class="comp-wrap">
    <div class="bg bg-3">
        <div class="bg bg-2">
            <div class="bg bg-1">
                <!-- 01 -->
                <div class=" row gap-30">
                    <div class="flex flex-wrap gap-24 middle w_fit">
                        <div class="flex flex-auto gap-24">
                            <a href="/portfolio" class="<?= (!$name)?"underline":"" ?>">개요</a>
                            <a href="/portfolio?section=talent" class="<?= ($name == "talent")?"underline":"" ?>">24/25 MH Talent
                                Portfolio</a>
                            <a href="/portfolio?section=archive" class="<?= ($name == "archive")?"underline":"" ?>">아카이브</a>
                        </div>
                        <!-- 종료 -->
                        <div class="flex flex-none gap-24 middle">
                            <?php
                                $user = wp_get_current_user();
                                $posts = get_posts([
                                    "post_type"=>"mh_portfolio",
                                    "author"=>$user->ID,
                                    "post_status"=>"publish",
                                    "posts_per_page"=>1,
                                ]);
                                if(is_user_logged_in() && !empty($posts)):
                            ?>
                            <a href="<?= get_permalink( $posts[0] ) ?>">마이페이지</a>
                            <?php
                                elseif($is_open):
                            ?>
                            <a href="/portfolio/insert">작품 등록</a>
                            <?php
                                endif;
                            ?>
                        </div>
                    </div>