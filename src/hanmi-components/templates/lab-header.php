<?php if($parent = get_post_parent()): ?>
<h1 class="title"><?= $parent->post_title ?></h1>
<div class="lab-header col gap-24">
    <!-- col-1-2 -->
    <!-- <div class="thumb">
        <img src="<?= HM::$lab_logo ?>" />
    </div> -->
    <div class="row gap-1r flex-auto">
        <p>
            <?= $parent->post_content ?>
        </p>
        <div>
            <?= comp("more",['label'=>'자세히 알아보기','link'=>getPage("lab-info")->permalink]) ?>
        </div>
    </div>
</div>
<hr />
<div>
    <?= comp("tab_submenu") ?>
</div>
<?php endif; ?>