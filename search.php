<?php
    get_header();
    $title = get_the_title();
    $type=$_GET['post_type'] ?? '';
    // function is_type($str) {return ($str == $type || $str == 'all');}
    $s = $_GET["s"] ?? "";
    $arg = [
        "post_status"=>"publish",
        "posts_per_page"=>5,
        "s"=>$s
    ];
?>
<div class="page-wrap row gap-32">
    <h3 class="title">
        통합검색
    </h3>
    <div class="col-2 gap-24">
        <div class="flex gap-16">
            <div class="flex-none">
                <?= comp("select",[
                    'name'=>'post_type',
                    'options'=>[
                        ['','통합검색'],
                        ['post_exhibition','전시'],
                        ['post_program','프로그램'],
                        ["post_activity","활동"],
                        ['post_notice','공지사항']
                    ],
                    "change"=>"
                        $(`form.search input[name=post_type]`).val(this.selected);
                    "
                ]) ?>
            </div>
            <div class="flex-auto">
                <?= comp("search",['root'=>'/','prop'=>'s','filters'=>[['post_type',$_GET["post_type"] ?? ""]]]) ?>
            </div>
        </div>
    </div>
    <div></div>
    <?php
        if($_GET["s"] ?? false):
            $total_ = 0;
            $posts = [
                "post_exhibition"=>array_merge($arg,["post_type"=>"post_exhibition"]),
                "post_program"=>array_merge($arg,["post_type"=>"post_program"]),
                "post_activity"=>array_merge($arg,["post_type"=>"post_activity","posts_per_page"=>4]),
                "post_notice"=>array_merge($arg,["post_type"=>"post_notice"])
            ];
            foreach($posts as $key=>$post) {
                if(($type == "" || $type == $key) == false) unset($posts[$key]);
            }
            if(!empty($type)) {
                $posts = array_map(function($value) {
                    $post_ = $value;
                    $post_["posts_per_page"] = 8;
                    $post_["paged"] = $_GET["pages"] ?? 1;
                    return $post_;
                },$posts);
            }
            foreach($posts as $key=>$post) {
                $posts[$key] = new WP_Query($post);
                $total_ += $posts[$key]->found_posts;
            }
    ?>
    <?php
    if(($f = $posts["post_exhibition"] ?? false) && $f->have_posts()):
    ?>
    <div class="row gap-30">
        <div>
            <?= temp("search-header",["label"=>"전시","count"=>$f->found_posts,"link"=>"/?post_type=post_exhibition&s=$s"]) ?>
        </div>
        <div class="item-list row gap-24">
            <?php while($f->have_posts()):$f->the_post(); ?>
            <?= comp("item-list-short") ?>
            <?php endwhile; ?>
        </div>
    </div>
    <hr/>
    <?php
        endif;
        if(($f = $posts["post_program"] ?? false) && $f->have_posts()):
    ?>
    <div class="row gap-30">
        <div>
        <?= temp("search-header",["label"=>"프로그램","count"=>$f->found_posts,"link"=>"/?post_type=post_program&s=$s"]) ?>
        </div>
        <div class="item-list row gap-24">
            <?php while($f->have_posts()):$f->the_post(); ?>
            <?= comp("item-list-short") ?>
            <?php endwhile; ?>
        </div>
    </div>
    <hr/>
    <?php
        endif;
        if(false):
    ?>
    <?php
        endif;
        if(($f = $posts["post_activity"] ?? false) && $f->have_posts()):
    ?>
    <div class="row gap-30">
        <div>
        <?= temp("search-header",["label"=>"활동","count"=>$f->found_posts,"link"=>"/?post_type=post_activity&s=$s"]) ?>
        </div>
        <div class="activity-list col-4 gap-24">
            <?php while($f->have_posts()):$f->the_post(); ?>
            <?= comp("activity-search-item") ?>
            <?php endwhile; ?>
        </div>
    </div>
    <hr/>
    <?php
        endif;
        if(($f = $posts["post_notice"] ?? false) && $f->have_posts()):
    ?>
    <div class="row gap-30">
        <div>
        <?= temp("search-header",["label"=>"공지사항","count"=>$f->found_posts,"link"=>"/?post_type=post_notice&s=$s"]) ?>
        </div>
        <div class="board sliced-bottom">
            <?php while($f->have_posts()):$f->the_post(); ?>
                <?= comp("board-item-short",["label"=>".."]) ?>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
        endif;
        if($total_ == 0):
        ?>
        <div class="page-wrap row gap-32 center middle">
        <br/><br/>
        <h3>
            검색결과가 없습니다.
        </h3>
    </div>
        <?php
        endif;
    ?>
    <?php
        else:
    ?>
    <div class="page-wrap row gap-32 center middle">
        <br/><br/>
        <h3>
            검색어를 입력해주세요.
        </h3>
    </div>
    <?php
        endif;
    ?>

    <?php
        if(!empty($type) && isset($posts[$type])) {
            ?>
            <?= comp("pagination",["query"=>$posts[$type]]) ?>
            <style>
                .page-wrap .button.more.clean {
                    display:none;
                }
            </style>
            <?php
        }
    ?>
</div>
<?php
get_footer();
?>