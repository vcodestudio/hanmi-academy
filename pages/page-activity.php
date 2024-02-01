<?php
get_header();
$query = $_GET;
$arg = [
    "post_type"=>'post_activity',
    "posts_per_page"=>9,
    "paged"=>$_GET["pages"] ?? 1,
    "tax_query"=>["AND"],
    "meta_query"=>["AND"]
];
$posts = new WP_Query($arg);
$parent = get_post(get_post()->post_parent);
$currentPost = get_post();
?>
<div class="row gap-32 page-wrap">
    <div class="row gap-1r">
        <h3>
            <?= $parent->post_title ?? "" ?>
        </h3>
        <p>
            <?= $parent->post_content ?>
        </p>
    </div>
    <div>
        <div class="col tab">
        <?php
            // get posts of same parent post
            $args = [
                "post_type"=>"page",
                "post_parent"=>get_post()->post_parent,
                "posts_per_page"=>-1,
                "order"=>"ASC",
            ];
            foreach(get_posts($args) as $post):
                if($post->post_status == "publish"):
            ?>
            <a href="<?= get_permalink($post) ?>"
                class="<?= ($currentPost->ID == $post->ID) ? "selected" : "" ?>">
                <?= $post->post_title ?>
            </a>
            <?php
                endif;
            endforeach;
            ?>
        </div>
    </div>
    <?php
        if($posts->have_posts()):
    ?>
    <div class="gallery-view col-3">
        <?php
        while($posts->have_posts()):$posts->the_post();
        ?>
        <div class="item row gap-24" gall>
            <code class="displaynone">
                <?php
                $imgs=[];
                foreach(_acf("gallery") as $img):
                    $imgs[] = img_src($img,"large");
                endforeach;
                echo implode(",",$imgs);
                ?>
            </code>
            <div class="thumb">
                <?= img(_acf("gallery")[0],"thumb") ?>
            </div>
            <div class="meta row gap-8">
                <h4><?= $post->post_title ?></h4>
                <h6 class="text-sub single-line">
                    <?= _acf("desc") ?>
                </h6>
            </div>
        </div>
        <?php
        endwhile; ?>
    </div>
    <div>
        <?= comp('pagination',['query'=>$posts]) ?>
    </div>
    <?php else: ?>
        <div class="row gap-32 center middle">
            <br/>
            <br/>
            <h2>
                검색된 활동이 없습니다.
            </h2>
            <br/>
        </div>
    <?php
        endif;
    ?>
</div>
<?php get_footer(); ?>