<?php
    $post = $post ?? get_post();
    if($post ?? false):
?>
<a class="item row gap-1r" href="<?= get_permalink($post) ?>">
    <div class="thumb">
        <div>
            <img src="<?= _acf("thumb",$post)["sizes"]["large"] ?>" />
        </div>
    </div>
    <div class="row gap-8">
        <h4><?= get_the_title($post) ?></h4>
        <h6 class="light"><?= _acf("author",$post) ?></h6>
        <h6 class="light fade"><?= _acf("publish",$post) ?> 발행</h6>
    </div>
</a>
<?php
    endif;
?>