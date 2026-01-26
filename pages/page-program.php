<?php
get_header();
$query = $_GET;
$args = [
	"s" => $query["keyword"] ?? "",
	"post_type" => "post_program",
	"posts_per_page" => 16, //16,
	"paged" => $_GET["pages"] ?? 1,
	"tax_query" => ["AND"],
	"meta_query" => ["AND"],
];
if ($f = $_GET["location"] ?? false) {
	array_push($args["tax_query"], [
		"taxonomy" => "location",
		"field" => "slug",
		"terms" => $f,
	]);
}
// date 파라미터가 없으면 기본값으로 "before" (신청중) 적용
$date_filter = $_GET["date"] ?? "before";
if ($date_filter === "all") {
	// 전체는 필터 없음
} else {
	switch ($date_filter) {
		case "before":
			array_push($args["meta_query"], [
				"key" => "product_purchasable",
				"value" => "1",
				"compare" => "=",
			]);
			break;
		case "current":
			array_push($args["meta_query"], [
				"key" => "start",
				"value" => date("Y/m/d"),
				"compare" => "<=",
				"type" => "DATE",
			]);
			array_push($args["meta_query"], [
				"key" => "end",
				"value" => date("Y/m/d"),
				"compare" => ">",
				"type" => "DATE",
			]);
			break;
		case "closed":
			array_push($args["meta_query"], [
				"key" => "end",
				"value" => date("Y/m/d"),
				"compare" => "<",
				"type" => "DATE",
			]);
			break;
	}
}
$cats = ["course", "participant"];
foreach ($cats as $cat) {
	if ($f = $query[$cat] ?? false) {
		array_push($args["tax_query"], [
			"taxonomy" => $cat,
			"field" => "slug",
			"terms" => $f,
		]);
	}
}
$c_arr = [["", "전체"]];
$b_arr = [["", "전체"]];
$b_args = get_terms(["taxonomy" => "participant"]);
$c_args = get_terms(["taxonomy" => "course"]);
foreach ($c_args as $c) {
	array_push($c_arr, [$c->slug, $c->name]);
}
foreach ($b_args as $b) {
	array_push($b_arr, [$b->slug, $b->name]);
}
$posts = new WP_Query($args);
?>
<div class="row gap-32 m:gap-24 page-wrap">
    <div class="flex gap-8 middle">
        <h3 class="single-line">
            <?= !empty($query["keyword"] ?? "")
            	? "[" . $query["keyword"] . "] " . get_the_title() . " 검색결과"
            	: get_the_title() ?>
        </h3>
        <span class="text-sub light"><?= $posts->found_posts ?></span>
    </div>
    <div class="flex justify-between items-center flex-wrap gap-y-4">
        <div class="col tab">
            <a href="?date=before" class="<?= ($date_filter == "before")
            	? "selected"
            	: "" ?>">신청중</a>
            <a href="?date=current" class="<?= ($date_filter == "current")
            	? "selected"
            	: "" ?>">진행중</a>
            <a href="?date=closed" class="<?= ($date_filter == "closed")
            	? "selected"
            	: "" ?>">종료</a>
            <a href="?date=all" class="<?= ($date_filter == "all")
            	? "selected"
            	: "" ?>">전체</a>
        </div>
        <div class="flex gap-1r">
            <?= comp("select", [
            	"type" => "course",
            	"options" => $c_arr,
            	"defaultLabel" => "과정",
            	"change" => "
                this.queryLinkTo();
            ",
            ]) ?>
            <?= comp("select", [
            	"type" => "participant",
            	"options" => $b_arr,
            	"defaultLabel" => "참여대상",
            	"change" => "
                this.queryLinkTo();
            ",
            ]) ?>
        </div>
    </div>
    <?php if ($posts->have_posts()): ?>
    <div class="gallery-grid m:gap-16">
        <?= comp("program-item-2", ["posts" => $posts]) ?>
    </div>
    <?php else: ?>
    <div class="row gap-32 center middle">
        <br />
        <br />
        <h2>
            <?php
            switch ($date_filter) {
                case "before":
                    echo "신청중인 프로그램이 없습니다.";
                    break;
                case "current":
                    echo "진행중인 프로그램이 없습니다.";
                    break;
                case "closed":
                    echo "종료된 프로그램이 없습니다.";
                    break;
                case "all":
                default:
                    echo "프로그램이 없습니다.";
                    break;
            }
            ?>
        </h2>
        <br />
    </div>
    <?php endif; ?>
    <div>
        <?= comp("pagination", ["query" => $posts]) ?>
    </div>
</div>
<?php get_footer(); ?>
