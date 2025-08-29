<?php
get_header();
?>
<div class="row gap-32 page-wrap">
    <?= temp("lab-header") ?>
    <div class="flex gap-24 lab_2 middle">
        <div class="flex-none w-stick"><img src="<?= getImg("pic-cul.png") ?>"/></div>
        <div class="row gap-16 flex-auto">
            <h4><?= get_the_title() ?></h4>
            <p>『사진+문화』는 한국사진문화연구소에서 정기적으로 발행하는 기관지로 한국사진사 관련자료를 공유하고 연구소의 프로젝트를 소개하기위해 기획되었습니다.</p>
        </div>
    </div>
    <div class="lab-pnc row gap-0 sliced-bottom">
    <?php
        $posts = new WP_Query([
            'post_type'=>'lab_2',
            'post_status'=>'publish',
            'posts_per_page'=>10,
            'paged'=>$_GET['pages'] ?? 1
        ]);
        while($posts->have_posts()):$posts->the_post();
        ?>
        <div class="item flex">
            <a href="<?= get_permalink() ?>"  class="row gap-4 flex-auto">
                <h6 class="light"><?= _acf("publish") ?></h6>
                <h4><?= get_the_title() ?></h4>
            </a>
            <div class="flex middle right flex-none">
                <?= comp("more",["label"=>"전문보기"]) ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <div>
    <?= comp('pagination',[
        'current'=>1,
        'max_page'=>20
    ]) ?>
    </div>
</div>
<?php get_footer(); ?>