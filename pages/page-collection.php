<?php
/**
 * @link https://vcode-studio.com/
 *
 * @package Vcode-studio
 */
get_header();
$currentCat = $_GET['category'] ?? 'photo';
$args = [
    'post_type'=>'post',
    'post_status'=>'publish',
    'posts_per_page'=>16,//16
    'paged'=>$_GET['pages'] ?? 1,
    's'=>$_GET['search'] ?? '',
    'tax_query'=>[
        'relation'=>'AND',
    ],
    'meta_query'=>[
        'relation'=>'AND',
    ]
];

array_push($args['tax_query'],[
    'taxonomy'=>'category',
    'field'=>'slug',
    'terms'=>$currentCat
]);

if($_GET['artists'] ?? false) {
    array_push($args['tax_query'],[
        'taxonomy'=>'artist',
        'field'=>'slug',
        'terms'=>explode(",",$_GET['artists']),
        'operator'=>'IN'
    ]);
}
if($_GET['process'] ?? false) {
    array_push($args['tax_query'],[
        'taxonomy'=>'process',
        'field'=>'slug',
        'terms'=>explode(",",$_GET['process']),
        'operator'=>'IN'
    ]);
}
if(isset($_GET["from"]) && isset($_GET["to"])) {
    array_push($args['meta_query'],[
        'key'=>'profile_year',
        'value'=>[$_GET['from'],$_GET['to']],
        'compare'=>'BETWEEN',
        'type'=>'NUMERIC'
    ]);
}

//
$isList = (isset($_GET["mode"]) && $_GET["mode"] == "list");

//커스텀필드 필터(작성해야함)
?>
<div class="row gap-32 v-archive page-wrap">
    <h3 class="title">소장품</h3>
    <div class="col category">
        <?php foreach(get_field('category','option') as $cat): ?>
            <?php if($cat->slug !== 'empty'): ?>
                <a href="./?category=<?= $cat->slug ?>" class="<?= ($cat->slug==$currentCat)?'selected':'' ?>"><?= $cat->name ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="col col-2 gap-1r search">
        <?= comp("search",["filters"=>[["category",$currentCat]]]) ?>
        <div>
            <?= comp("filter-toggle",["label"=>"상세필터"]) ?>
            <?php
            if(false):
        ?>
            <div class="button_set select">
                <a class="button w aspect">
                    <?= icon('list') ?>
                </a>
                <a class="button w aspect">
                    <?= icon('grid') ?>
                </a>
            </div>
            <?php
            endif;
        ?>
        </div>
    </div>
    <?= comp("filters",[
        "filter"=>[
            ["아티스트","artist",get_terms(['taxonomy'=>'artist','hide_empty'=>true])],
            ["프로세스","process",get_terms(['taxonomy'=>'process','hide_empty'=>true])],
            ["기간","publish",[1880,2022]]
        ],
        "query"=>[
            "category=$currentCat"
        ]
    ]) ?>
    <?php
        if(!$isList):
    ?>
    <div class="gallery">
            <?php
            $posts = new WP_Query($args);
            if($posts->have_posts()):
            while($posts->have_posts()):
                $posts->the_post();
            ?>
            <a href="<?= get_the_permalink() ?>" class="item">
                <div class="thumb">
                    <img src="<?= _acf("thumb")["sizes"]["gallery_thumb"] ?? getImg("error.svg") ?>"/>
                </div>
                <div class="meta">
                    <h4><?= get_the_title() ?></h4>
                    <?php
                        if($f=_acf('profile_year')):
                    ?>
                    <p class="year"><?= $f ?></p>
                    <?php
                        endif;
                    ?>
                    <?php
                        if($f=_acf('artist')):
                            function anames($arr) {
                                return $arr->name;
                            }
                            $f = array_map("anames",$f);
                    ?>
                    <p><?= implode(", ",$f) ?></p>
                    <?php
                        else:
                            ?>
                            <p>
                                <?= _acf("artist_str") ?>
                            </p>
                            <?php
                        endif;
                    ?>
                </div>
            </a>
            <?php
            endwhile;
            else:
            ?>
            검색결과가 없습니다.
            <?php
            endif;
            // wp_reset_postdata();
            ?>
    </div>
    <?php
        else:
    ?>
    <div class="list row">
        <div class="col right">
            <div class="button w clean" :disabled="(utils.list < 1)" style="padding-right:0;padding-left:0;background:#fff" @click.self="listExport">
                <?= icon('share') ?>
                선택파일 내보내기
            </div>
        </div>
        <div class="col">
        <table>
            <tbody>
                <tr>
                    <td>
                        <div class="checkbox" id="list-all" ref="list_all" data-ids="<?= implode(",",array_map("post_ids",$posts->posts)) ?>">
                            <input type="checkbox" :checked="list_check"/>
                            <label @click="listAllCheck">
                                <span class="check"></span>
                            </label>
                        </div>
                    </td>
                    <td>분류번호</td>
                    <td>이미지</td>
                    <td>작품명 / 제작연도 / 작가명</td>
                    <td>보관위치</td>
                    <td>작품관리카드</td>
                    <td>컨디션리포트</td>
                </tr>
            <?php while($posts->have_posts()):$posts->the_post(); ?>
                <tr>
                    <td>
                        <div class="checkbox">
                            <input type="checkbox" id="list-<?= get_the_ID() ?>" v-model="utils.list" :value="<?= get_the_ID() ?>"/>
                            <label for="list-<?= get_the_ID() ?>">
                                <span class="check"></span>
                            </label>
                        </div>
                    </td>
                    <!-- 분류번호 -->
                    <td>
                        <?= _acf('profile')['id'] ?>
                    </td>
                    <!-- 썸네일 -->
                    <td class="thumb">
                        <img src="<?= get_img('thumbnail') ?>"/>
                    </td>
                    <!-- 작가명 -->
                    <td class="bio">
                            <p class="bold"><?= get_the_title() ?></p>
                            <p class="year"><?= _acf('profile')['year'] ?></p>
                            <p><?= _acf('artist')->name ?></p>
                    </td>
                    <td class="location" @click.self="(e)=>e.target.classList.toggle('show')">
                        <p>
                            <?= _acf('profile')['location'] ?>
                        </p>
                        <?php if($qr = _acf('info')['qr']): ?>
                        <hr/>
                        <a href="javascript:;">
                            <?= icon('QR') ?>
                            QR 스캔
                        </a>
                        <img src="<?= $qr['url'] ?>" class="qr"/>
                        <?php endif; ?>
                    </td>
                    <!-- 작품관리카드 -->
                    <td class="download">
                        <?php if($f = _acf('info_download')['i_1']): ?>
                        <a href="<?= $f['url']?>" download>
                            다운로드
                        </a>
                        <p>
                            최종 업데이트<br/>
                            <?= dateFormat($f['modified']) ?>
                        </p>
                        <?php endif; ?>
                    </td>
                    <!-- 컨디션 리포트 -->
                    <td class="download">
                        <?php if($f = _acf('info_download')['i_2']): ?>
                        <a href="<?= $f['url'] ?>" download>
                            다운로드
                        </a>
                        <p>
                            최종 업데이트<br/>
                            <?= dateFormat($f['modified']) ?>
                        </p>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
    <?php
        endif;
    ?>
    <?= comp('pagination',["query"=>$posts]) ?>
</div>
<?php
wp_reset_postdata();
get_footer();