<?php
customSetPostViews();
$views = get_post_meta(get_the_ID(), "views", true);
?>
<div class="row gap-32 page-wrap detail">
    <?php
    // 이미지 배열 준비: detail_imgs가 있으면 사용, 없으면 thumb 사용
    $imgs = _acf("detail_imgs") ?: (_acf("thumb") ? [_acf("thumb")] : []);
    if (!empty($imgs)):
    ?>
    <?= comp("slider-banner", ["imgs" => $imgs, "forceSlider" => true, "showBullets" => true]) ?>
    <?php endif; ?>
    <div class="row gap-24">
        <div class="row gap-12">
            <h3><?= get_the_title() ?></h3>
            <div class="divider">
                <p class="fade"><?= get_the_date("Y. m. d.") ?></p>
                <?php if($views): ?>
                <p class="fade">조회수 <?= number_format($views) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row gap-16">
        <?= the_content() ?>
    </div>
    <hr/>
    <div class="flex">
        <a href="<?= getPage("notice")->permalink ?>" class="button">목록으로</a>
    </div>
</div>