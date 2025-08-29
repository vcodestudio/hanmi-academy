<?php
get_header();
?>
<div class="row gap-32 page-wrap">
    <?= temp("lab-info-header") ?>
    <hr/>
    <div class="row gap-30">
        <div>
            <?= comp("tab_submenu") ?>
        </div>
        <div class="lab-info-table row gap-24">
            <?php foreach(_acf("content") as $i=>$item): ?>
            <div class="item flex gap-24">
                    <h4><?= $item['year'] ?></h4>
                    <div class="row gap-24">
                        <?php foreach($item['date'] as $date): ?>
                        <div class="col gap-24 nowrap">
                            <p class="bold"><?= $date['month'] ?></p>
                            <p><?= $date['text'] ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
            </div>
            <?php if($i < count(_acf("content"))-1): ?>
                <hr/>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>