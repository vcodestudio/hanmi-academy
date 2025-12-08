<?php
    get_header();
    include_once DIR."/pages/portfolio/template.php";
    $mh = new MH_P();
    $mh->header();

    $user = wp_get_current_user();
    $user_obj = get_field("user_object",$user);
    $is_reviewer = (($user_obj && $user_obj->post_type == "comp-reviewer") || current_user_can('administrator'));
    $is_author = (($user->ID == get_post()->post_author) || current_user_can('administrator'));
    $cur_idx = $_GET['series'] ?? 0;
    $cur = get_field("series")[$cur_idx] ?? false;

    $user_type = get_field("type",$user->ID);
    $author = get_user_by( "id", get_post()->post_author );
    if(!is_user_logged_in()) {
        wp_redirect( "/login" );
        exit;
    }

    // check if user is logged in + current user is author + or admin + or reviewer(type)
    $is_correct_author = $user->ID == get_post()->post_author || current_user_can('administrator') || $is_reviewer;
    if(!$is_correct_author) {
        wp_redirect(home_url());
        exit;
    }

    if(isset($_POST["review"])) {
        $review = $_POST["review"];
        $reviewed = get_field("reviewed") ?? [];
        if(!$reviewed) $reviewed = [];
        if($review) {
            if(!in_array($user->ID,$reviewed))
                $reviewed[] = $user->ID;
        } else {
            // remove from array
            $reviewed = array_diff($reviewed,[$user->ID]);
        }
        update_field("reviewed",$reviewed);
    }
?>
<div class="page-wrap">
    <div class="flex flex-col gap-32">
        <?php
            if($is_reviewer):
        ?>
        <!-- get post author's display name -->
        <h3><?= $author->display_name ?></h3>
        <div class="gap-8 row">
            <table class="table">
                <tbody>
                    <?php
                    // is admin
                        if( current_user_can('administrator') ):
                    ?>
                    <tr>
                        <td>아이디</td>
                        <td>
                            <?= $author->user_login ?? "" ?>
                        </td>
                    </tr>
                    <?php
                        endif;
                    ?>
                    <tr>
                        <td>생년월일</td>
                        <td>
                            <?= get_field("birth","user_".$author->ID ?? 0) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>성별</td>
                        <td>
                            <?php
                                $gender = get_field_object("gender","user_".$author->ID);
                                $gender = isset($gender["choices"])?$gender["choices"][get_field("gender","user_".$author->ID)]:"-";
                                echo $gender;
                            ?>
                        </td>
                    </tr>
                    <?php
                        if($f=get_field("website")):
                    ?>
                    <tr>
                        <td>웹사이트</td>
                        <td>
                            <a href="<?= $f ?>" target="_blank" style="text-decoration:underline;">
                                <?= $f ?>
                            </a>
                        </td>
                    </tr>
                    <?php
                        endif;
                    ?>
                    <tr>
                        <td>제출자료</td>
                        <td>
                            <div class="flex gap-8">
                                <?php
                                    $downloads = ["application"=>"참여신청서","resume"=>"작가 CV","portfolio"=>"포트폴리오"];
                                    foreach($downloads as $download=>$label):
                                        if($f=get_field($download)):
                                ?>
                                <a href="<?= $f["url"] ?>" class="button w" download>
                                    <span><?= $label ?></span>
                                </a>
                                <?php
                                endif;
                                    endforeach;
                                ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
            else:
        ?>
        <h3>마이페이지</h3>
        <?php
            endif;
        ?>
        <hr />
        <div class="flex gap-12">
            <?php
                $series_ = get_field("series")?get_field("series"):[];
                foreach($series_ as $idx=>$series):
            ?>
            <a href="./?series=<?= $idx ?>" class="<?= ($cur_idx == $idx)?"underline":"" ?>">연작 <?= ($idx+1) ?></a>
            <?php
                endforeach;
            ?>
        </div>
<?php
    if($cur):
?>
        <div class="gap-24 col col-2">
            <?php
                if($cur["series_kr"] ?? false):
            ?>
            <div class="flex flex-col gap-8">
                <!-- <p class="bold">국문</p> -->
                <?php
                    if($f=$cur["title_kr"]):
                ?>
                <p class="bold">
                    <?= $f ?>
                </p>
                <?php
                    endif;
                ?>
                <p>
                    <?= $cur["series_kr"] ?? "" ?>
                </p>
            </div>
            <?php
                endif;
                if($cur["series_en"] ?? false):
            ?>
            <div class="flex flex-col gap-8">
                <!-- <p class="bold">영문</p> -->
                <?php
                    if($f=$cur["title_en"]):
                ?>
                <p class="bold">
                    <?= $f ?>
                </p>
                <?php
                    endif;
                ?>
                <p>
                    <?= $cur["series_en"] ?? "" ?>
                </p>
            </div>
            <?php
                endif;
            ?>
        </div>
        <!-- gallery -->
        <div class="gap-24 col col-4" gallery>
            <?php
            if(isset($cur["imgs"]) && is_array($cur["imgs"])):
                foreach($cur["imgs"] as $img):
            ?>
            <img src="<?= $img["url"] ?>" alt="<?= $img["caption"] ?>" class="block w-full" />
            <?php
                endforeach;
            endif;
            ?>
        </div>
        <?php
    else:
        ?>
        <h3 class="text-center">연작이 없습니다.</h3>
        <?php
    endif; // cur
?>
        <!-- 수정 -->
        <?php
            if(($is_author && (get_field("can_edit") || get_field("portfolio_active","option"))) || current_user_can('administrator')):
        ?>
        <hr/>
        <div class="flex gap-24">
            <a href="/portfolio/insert" class="button">제출 수정하기</a>
        </div>
        <?php
            endif;
        ?>
        <hr />
        <!-- comment -->
        <div class="flex flex-col gap-24">
            <h3>
                <?php
                    if($is_reviewer):
                ?>
                작가 리뷰 작성
                <?php
                    else:
                ?>
                내가 받은 리뷰
                <?php
                    endif;
                ?>
            </h3>
            <?php
                // if has any comments in this post
                $com_args = [
                    'post_id' => get_the_ID(),
                ];
                // // if cur user is reviewer, show cur user's comments
                // if($is_reviewer) {
                //     $com_args['user_id'] = $user->ID;
                // }
                if($comments = get_comments($com_args)):
            ?>
            <div class="flex flex-col gap-8">
                <?php
                    foreach($comments as $comment):
                        $reviewer_obj = get_field("user_object","user_".$comment->user_id);
                        $post_id = get_the_ID();
                        $comment_id = $comment->comment_ID;
                        $nonce = wp_create_nonce( "delete-comment_$comment_id" ); // nonce 생성
                        $redirect = get_permalink(); // 삭제 후 이동할 페이지
                        $delete_link = admin_url( "comment.php?action=deletecomment&p=$post_id&c=$comment_id&_wpnonce=$nonce&redirect_to=$redirect" ); // 삭제 링크 생성
                        
                        $comment_user_correct = ($comment->user_id == $user->ID) || current_user_can('administrator') || $is_author;
                        if($comment_user_correct):
                ?>
                <div class="flex flex-col gap-8 ">
                    <div class="flex w-full gap-12">
                        <?php
                            if(get_field("thumb",$reviewer_obj)):
                        ?>
                        <div class="flex-none square">
                            <img src="<?= get_field("thumb",$reviewer_obj)["url"] ?>"/>
                        </div>
                        <?php
                            endif;
                        ?>
                        <div class="flex-auto">
                            <p class="bold"><?= $comment->comment_author ?>
                                <?php
                                    if($is_reviewer):
                                ?>
                                <a href="<?= $delete_link ?>" style="text-decoration:underline">[삭제]</a>
                                <?php
                                    endif;
                                ?>
                            </p>
                            <p><?= $comment->comment_content ?></p>
                        </div>
                    </div>
                </div>
                <?php
                    endif;
                    endforeach;
                ?>
            </div>
            <?php
                else:
            ?>
            <p>아직 리뷰가 없습니다.</p>
            <?php
                endif;
            ?>

            <?php
            // 리뷰달기
                if($is_reviewer):
                    // prev post
                    $prev_post = get_previous_post();
                    // next post
                    $next_post = get_next_post();

                    // add comment to this post
            ?>
            <form action="/wp-comments-post.php" method="post" id="commentform" class="comment-form">
                <textarea id="comment" name="comment" cols="45" rows="8" maxlength="300" class="w-full"
                    required="required"></textarea>
                    <input type="hidden" name="comment_post_ID" value="<?= get_the_ID() ?>" id="comment_post_ID">
                    <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                    <div class="flex justify-between w-full">
                        <input name="submit" type="submit" id="submit" class="submit" value="댓글 등록">
                    </div>
            </form>
            <div class="gap-12 col col-3">
            <?php
                if($next_post):
            ?>
            <a href="<?= get_permalink($next_post) ?>" class="button w">이전 포트폴리오</a>
            <?php
                else:
            ?>
            <div></div>
            <?php
                endif;
            ?>
                <a href="/portfolio/portfolio-list" class="button">참여작가 목록</a>
            <?php
                if($prev_post):
            ?>
                <a href="<?= get_permalink($prev_post) ?>" class="button w">다음 포트폴리오</a>
            <?php
                else:
            ?>
            <div></div>
            <?php
                endif;
            ?>
            </div>
            <hr/>
            <?php
                $is_reviewed = in_array($user->ID, (get_field("reviewed")?get_field("reviewed"):[]));
            ?>
            <form action="./" method="post" class="flex justify-end gap-12">  
                <?php
                    if(!$is_reviewed):
                ?>
                <input type="hidden" name="review" value="1"/>
                <input type="submit" value="리뷰 완료">
                <?php
                    else:
                ?>
                <input type="hidden" name="review" value="0"/>
                <input type="submit" class="w" value="리뷰 취소">
                <?php
                    endif;
                ?>
            </form>
            <?php
                endif;
            ?>
        </div>
        <hr />
        <!-- notice -->
        <?php
            if(!$is_reviewer || current_user_can( "administrator")):
        ?>
        <div>
            <h3>24/25 MH Talent Portfolio 일정</h3>
            <div>
                <?= _acf("4_notice","option") ?>
            </div>
        </div>
        <?php
            endif;
        ?>


    </div>

</div>
<?php
    $mh->footer();
    get_footer();
?>