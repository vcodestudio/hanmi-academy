<?php
customSetPostViews();
$views = get_post_meta(get_the_ID(), "views", true);
?>
<div class="page-wrap row gap-32">
    <div class="row gap-12">
        <h3><?= get_the_title() ?></h3>
        <div class="divider">
            <p class="fade"><?= get_the_date("Y. m. d.") ?></p>
            <?php if($views): ?>
            <p class="fade">조회수 <?= number_format($views) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <hr/>
    <?php
        if(_acf("thumb")):
    ?>
    <div class="img-banner">
        <img src="<?= _acf("thumb")["sizes"]["detail_slider"] ?>"/>
    </div>
    <?php
        endif;
    ?>
    <div>
        <?= the_content() ?>
    </div>
    <hr/>
    <div class="flex">
        <a href="<?= getPage("notice")->permalink ?>" class="button">목록으로</a>
    </div>
</div>