<?php
get_header();
$query = $_GET;
$args = [
    "s"=>$query["keyword"] ?? "",
    "post_type"=>'post_program',
    "posts_per_page"=>16,//16,
    "paged"=>$_GET["pages"] ?? 1,
    "tax_query"=>["AND"],
    "meta_query"=>["AND"]
];
if($f=$_GET["location"] ?? false) array_push($args["tax_query"],[
    "taxonomy"=>"location",
    "field"=>"slug",
    "terms"=>$f
]);
if($f=$_GET["date"] ?? false) {
    switch($f) {
        case "before":
            array_push($args["meta_query"],[
                "key"=>"start",
                "value"=>date("Y/m/d"),
                "compare"=>">",
                "type"=>"DATE"
            ]);
        break;
        case "current":
            array_push($args["meta_query"],[
                "key"=>"start",
                "value"=>date("Y/m/d"),
                "compare"=>"<=",
                "type"=>"DATE"
            ]);
            array_push($args["meta_query"],[
                "key"=>"end",
                "value"=>date("Y/m/d"),
                "compare"=>">",
                "type"=>"DATE"
            ]);
        break;
        case "closed":
            array_push($args["meta_query"],[
                "key"=>"end",
                "value"=>date("Y/m/d"),
                "compare"=>"<",
                "type"=>"DATE"
            ]);
        break;
    }
}
$cats = ["program_theme","job"];
foreach($cats as $cat) {
    if($f = ($query[$cat] ?? false)) {
        array_push($args["tax_query"],[
            "taxonomy"=>$cat,
            "field"=>"slug",
            "terms"=>$f
        ]);
    }
}
$c_arr = [
    ["","카테고리"]
];
$b_arr = [
    ["","대상"]
];
$b_args = get_terms(["taxonomy"=>"job"]);
$c_args = get_terms(["taxonomy"=>"program_theme"]);
foreach($c_args as $c) {
    array_push($c_arr,[$c->slug,$c->name]);
}
foreach($b_args as $b) {
    array_push($b_arr,[$b->slug,$b->name]);
}
$posts = new WP_Query($args);
?>
<div class="row gap-32 page-wrap">
    <div class="flex gap-8 middle">
        <h3>
            <?= (!empty($query["keyword"] ?? ""))?"[".$query["keyword"]."] ".get_the_title()." 검색결과":get_the_title() ?>
        </h3>
        <span class="text-sub light"><?= $posts->found_posts ?></span>
    </div>
    <div>
        <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit id temporibus veritatis ducimus,
        </p>
    </div>
    <div>
        <div class="col tab">
            <a href="." class="<?= (!($_GET['date'] ?? false)?"selected":"") ?>">전체</a>
            <a href="?date=current" class="<?= ((($_GET['date'] ?? false) == "current")?"selected":"") ?>">신청중</a>
            <a href="?date=before" class="<?= ((($_GET['date'] ?? false) == "before")?"selected":"") ?>">예정</a>
            <a href="?date=closed" class="<?= ((($_GET['date'] ?? false) == "closed")?"selected":"") ?>">마감</a>
        </div>
    </div>
    <div class="col-2 gap-1r">
        <div class="pc"></div>
        <div class="flex right p-left">
            <!-- <div class="col">
                
                <?= comp("select",[
                "type"=>"program_theme",
                "options"=>$c_arr,
                "change"=>"
                    this.queryLinkTo();
                "
            ]) ?>
                <?= comp("select",[
                "type"=>"job",
                "options"=>$b_arr,
                "change"=>"
                    this.queryLinkTo();
                "
            ]) ?>
            </div> -->
        </div>
    </div>
    <?php
        if($posts->have_posts()):
    ?>
    <div class="gallery-grid">
        <?=
            comp("program-item",["posts"=>$posts]);
        ?>
    </div>
    <?php
            else:
        ?>
    <div class="row gap-32 center middle">
        <br />
        <br />
        <h2>
            진행중인 프로그램이 없습니다.
        </h2>
        <br />
    </div>
    <?php
            endif;
        ?>
    <div>
        <?= comp('pagination',['query'=>$posts]) ?>
    </div>
</div>
<?php get_footer(); ?>