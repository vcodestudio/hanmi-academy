<?php
get_header();
$query = $_GET;

// 활동 포스트들의 시작일에서 최소 연도 계산
$min_year_query = new WP_Query([
    "post_type" => 'post_activity',
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

$arg = [
    "post_type"=>'post_activity',
    "posts_per_page"=>9,
    "paged"=>$_GET["pages"] ?? 1,
    "tax_query"=>["AND"],
    "meta_query"=>["AND"]
];

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
        $arg['meta_query'] = [$meta_or];
    }
}

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
    <div class="flex justify-between items-center">
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
        <div>
        <?php
            // 기간 버튼 (발행연도 필터와 동일한 토글 UX)
            echo comp('filter-toggle', ['label' => '기간']);
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
    
    <?php
        if($posts->have_posts()):
    ?>
    <div class="gallery-view col-3">
        <?php
        while($posts->have_posts()):$posts->the_post();
        ?>
        <div class="item row gap-24">
            <div class="thumb" gallery style="position: relative;">
                <?php 
                $gallery_imgs = _acf("gallery");
                foreach($gallery_imgs as $index => $img): ?>
                    <?php if($index === 0): ?>
                        <?= img($img, "thumb") ?>
                    <?php else: ?>
                        <img src="<?= $img['sizes']['thumb'] ?? $img['url'] ?>" alt="" style="display: none;">
                    <?php endif; ?>
                <?php endforeach; ?>
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