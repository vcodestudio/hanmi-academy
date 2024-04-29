<?php
    $post = $post ?? get_post();
?>
<a href="<?= get_permalink() ?? '.' ?>" class="item row gap-1r">
    <div class="thumb">
        <?= img(_acf("thumb"),"large",_acf("thumb")) ?>
    </div>
    <div class="meta row gap-16">
        <h4><?= $post->post_title ?></h4>
        <h6 class="text-sub single-line"><?=_acf("start").". ".yoil(_acf("start")) ?> ~
            <?=_acf("end").". ".yoil(_acf("end"))?></h6>
    </div>
</a>