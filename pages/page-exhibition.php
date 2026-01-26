<?php
get_header();
$query = $_GET;

// 전시 포스트들의 시작일에서 최소 연도 계산
$min_year_query = new WP_Query([
    "post_type" => 'post_exhibition',
    "post_status" => 'publish',
    "posts_per_page" => -1,
    "meta_query" => [
        [
            'key' => 'start',
            'compare' => 'EXISTS'
        ]
    ]
]);
$min_year = 1880; // 기본값
$years = [];
if ($min_year_query->have_posts()) {
    while ($min_year_query->have_posts()) {
        $min_year_query->the_post();
        $start_date = get_field('start');
        if ($start_date) {
            // Y.m.d 형식에서 연도 추출
            $year = (int) substr($start_date, 0, 4);
            if ($year > 0) {
                $years[] = $year;
            }
        }
    }
    wp_reset_postdata();
    if (!empty($years)) {
        $min_year = min($years);
    }
}
$max_year = date('Y');

$args = [
	"s" => $query["keyword"] ?? "",
	"post_type" => "post_exhibition",
	"posts_per_page" => 16,
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
// date 파라미터가 없으면 기본값으로 "all" (전체) 적용
$date_filter = $_GET["date"] ?? "all";
if ($date_filter === "all") {
	// 전체는 필터 없음
} else {
	switch ($date_filter) {
		case "before":
			array_push($args["meta_query"], [
				"key" => "start",
				"value" => date("Y.m.d"),
				"compare" => ">",
				"type" => "CHAR",
			]);
			break;
		case "current":
			array_push($args["meta_query"], [
				"key" => "start",
				"value" => date("Y.m.d"),
				"compare" => "<=",
				"type" => "CHAR",
			]);
			array_push($args["meta_query"], [
				"key" => "end",
				"value" => date("Y.m.d"),
				"compare" => ">=",
				"type" => "CHAR",
			]);
			break;
		case "closed":
			array_push($args["meta_query"], [
				"key" => "end",
				"value" => date("Y.m.d"),
				"compare" => "<",
				"type" => "CHAR",
			]);
			break;
	}
}

// 기간 필터 처리 (from/to 파라미터)
$has_range = (isset($_GET['from']) && $_GET['from'] !== '' && isset($_GET['to']) && $_GET['to'] !== '');
if ($has_range) {
    $from = intval($_GET['from']);
    $to = intval($_GET['to']);
    if ($from && $to && $to >= $from) {
        // start 메타 필드에서 연도 추출하여 범위 매칭 (OR LIKE)
        $range = ($to - $from + 1);
        $years = [];
        for ($y = 0; $y < $range; $y++) {
            $years[] = (string)($from + $y);
        }
        
        $meta_or = ['relation' => 'OR'];
        foreach ($years as $yy) {
            $meta_or[] = [
                'key' => 'start',
                'value' => $yy,
                'compare' => 'LIKE',
            ];
        }
        // 기존 meta_query와 병합
        if (!empty($args['meta_query']) && $args['meta_query'][0] !== 'AND') {
            $args['meta_query'] = array_merge([['relation' => 'AND']], $args['meta_query'], [$meta_or]);
        } else {
            $args['meta_query'][] = $meta_or;
        }
    }
}

$posts = new WP_Query($args);
?>
<div class="row gap-32 page-wrap">
    <div class="flex gap-8 middle">
        <h3>
            <?= !empty($query["keyword"] ?? "")
            	? "[" . $query["keyword"] . "] " . get_the_title() . " 검색결과"
            	: get_the_title() ?>
        </h3>
        <span class="text-sub light"><?= $posts->found_posts ?></span>
    </div>
    <div class="flex justify-between items-center flex-wrap gap-y-8">
        <div class="col tab">
            <a href="." class="<?= ($date_filter == "all" || !$date_filter)
            	? "selected"
            	: "" ?>">전체</a>
            <a href="?date=current" class="<?= ($date_filter == "current")
            	? "selected"
            	: "" ?>">진행중</a>
            <a href="?date=before" class="<?= ($date_filter == "before")
            	? "selected"
            	: "" ?>">예정</a>
            <a href="?date=closed" class="<?= ($date_filter == "closed")
            	? "selected"
            	: "" ?>">종료</a>
        </div>
        <div class="m:w-full m:justify-end m:flex">
        <?php
            // 기간 버튼 (활동 페이지와 동일한 토글 UX)
            echo comp('filter-toggle', ['label' => '전시기간']);
        ?>
        </div>
    </div>
    
    <!-- 기간 필터 영역 -->
    <div class="tw-flex tw-flex-col tw-gap-8 tw-w-full">
        <!-- 기간 필터 UI (토글 버튼 아래에 표시) -->
        <?php
        // 필터 적용 시에도 현재 페이지네이션 유지
        $persist = [];
        if (!empty($_GET['pages'])) { 
            $persist[] = 'pages=' . urlencode($_GET['pages']); 
        }
        ?>
        
        <?= comp('filters', [
            'filter' => [
                ['기간', 'publish', [$min_year, $max_year]]
            ],
            'query' => $persist,
            'class' => '!tw-border-b-0 !tw-border-t-0'
        ]) ?>
    
        <div class="tw-self-stretch tw-h-px tw-bg-black/10"></div>
    </div>
    <?php if ($posts->have_posts()): ?>
    <div class="gallery-grid">
        <?= comp("exhibition-item-2", ["posts" => $posts, "query" => $query]) ?>
    </div>
    <?php else: ?>
    <div class="row gap-32 center middle">
        <br />
        <br />
        <h2>
            진행중인 전시가 없습니다.
        </h2>
        <br />
    </div>
    <?php endif; ?>
    <div>
        <?= comp("pagination", ["query" => $posts]) ?>
    </div>
</div>
<?php get_footer(); ?>
