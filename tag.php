<?php
get_header();
$currentCat = $_GET['category'] ?? 'photo';
$term = get_queried_object();
$args = [
    'post_type'=>'post',
    'post_status'=>'publish',
    'posts_per_page'=>16,//16
    'paged'=>$_GET['page'] ?? 1,
    's'=>$_GET['s'] ?? '',
    'tax_query'=>[
        'relation'=>'AND',
        [
            'taxonomy'=>'post_tag',
            'field'=>'slug',
            'terms'=>$term->slug
        ]
    ],
];
$cats = [];
foreach(get_field('category','option') as $cat) {
    $_arg = $args;
    array_push($_arg['tax_query'],[
        'taxonomy'=>'category',
        'field'=>'slug',
        'terms'=>$cat->slug
    ]);
    $_quer = new WP_Query($_arg);
    $cats[$cat->slug] = $_quer->found_posts;
}
if($currentCat) array_push($args['tax_query'],[
    'taxonomy'=>'category',
    'field'=>'slug',
    'terms'=>$currentCat
]);
?>
<div class="row gap-32 v-filter main">
    <?= var_dump(get_queried_object()) ?>
    <h3 class="title">'<?= $term->name ?>' 관련 소장품입니다.</h3>
    <div class="col">
        <?php foreach(get_field('category','option') as $cat): ?>
            <?php if($cat->slug !== 'empty'): ?>
                <a href="./?category=<?= $cat->slug ?>" class="button w r <?= ($cat->slug==$currentCat)?'selected':'' ?>" <?=($cats[$cat->slug]<1)?'disabled="disabled"':'' ?>>
                <?= $cat->name ?>(<?= $cats[$cat->slug] ?>)
            </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="gallery" v-show="utils.isGallery">
            <?php
            $posts = new WP_Query($args);
            if($posts->have_posts()):
            while($posts->have_posts()):
                $posts->the_post();
            ?>
            <a href="<?= get_the_permalink() ?>" class="item">
                <div class="thumb">
                    <img src="<?= get_img('thumbnail') ?>"/>
                </div>
                <div class="meta">
                    <h4><?= get_the_title() ?></h4>
                    <p class="year"><?= _acf('profile')['year'] ?></p>
                    <p><?= _acf('artist')->name ?></p>
                </div>
            </a>
            <?php
            endwhile;
            else:
            ?>
            검색결과가 없습니다.
            <?php
            endif;
            ?>
    </div>
    <div class="list row" v-show="!utils.isGallery">
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
    <?= comp('pagination',[
        'current'=>$args['paged'] ?? 1,
        'max_page'=>$posts->max_num_pages
    ]) ?>
</div>
<?php
wp_reset_postdata();
get_footer();