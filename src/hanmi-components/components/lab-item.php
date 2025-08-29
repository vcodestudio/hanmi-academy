<?php
    $post = $post ?? get_post();
?>
<a href="<?= get_permalink($post) ?>" class="item col-2 gap-24">
    <div class="thumb">
        <img src="<?= _acf("thumb")["sizes"]["large"] ?? getImg() ?>" />
    </div>
    <div class="row gap-8">
        <p class="light">
            <?= get_the_title($post) ?>
        </p>
        <h4 class="b">
            <?= get_the_title($post) ?>
        </h4>
        <p class="light">
            <?= mb_substr(_acf("desc"),0,150,"utf-8") ?> ...
        </p>
    </div>
</a>