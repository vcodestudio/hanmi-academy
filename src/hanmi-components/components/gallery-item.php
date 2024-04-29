<?php
    $post = $post ?? get_post();
    // create empty arg with default key + value
    $arg_default = [
        'link'=>'.',
        'location'=>'',
        'state'=>'',
        'title'=>'',
        'start'=>'',
        'end'=>''
    ];
    // merge default arg with given arg
    $arg = array_merge($arg_default,$arg);
?>
<a href="<?= $arg['link'] ?? '.' ?>" class="item row gap-1r">
    <div class="thumb">
        <?= img(_acf("thumb",$post),"large",_acf("thumb",$post)) ?>
    </div>
    <div class="meta row gap-16">
        <div class="stat">
            <h6 class="bold"><?= $arg['location'] ?></h6>
            <h6 class="bold"><?= $arg['state'] ?></h6>
        </div>
        <h4><?= $arg['title'] ?></h4>
        <h6 class="text-sub single-line"><?=$arg['start'].". ".yoil($arg['start']) ?> ~ <?=$arg['end'].". ".yoil($arg['end'])?></h6>
    </div>
</a>