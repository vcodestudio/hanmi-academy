<?php
    $post = $post ?? get_post();
?>
<a href="<?= $arg['link'] ?? get_permalink($post) ?>" class="item row gap-12 single-line">
    <h4>
        <?= get_the_title($post) ?>
    </h4>
    <div class="light fade">
        <div class="flex divider">
            <h6 class="fade">
                <?= get_the_date("Y.m.d",$post) ?>
            </h6>
            <h6 class="fade">
                <?= get_the_author_meta("display_name") ?>
            </h6>
        </div>
    </div>
</a>