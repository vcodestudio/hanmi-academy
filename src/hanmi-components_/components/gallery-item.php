<?php
    $post = $post ?? get_post();
?>
<a href="<?= $arg['link'] ?? '.' ?>" class="item row gap-1r">
            <div class="thumb">
                <?= img(_acf("thumb",$post),"large",_acf("thumb",$post)) ?>
            </div>
            <div class="meta row gap-16">
                <div class="stat">
                    <?php if(isset($arg["location"])): ?><h6 class="bold"><?= $arg['location'] ?></h6><?php endif; ?>
                    <?php
                        if(isset($arg["state"])):
                    ?>
                        <h6 class="bold"><?= $arg['state'] ?></h6>
                        <?php
                            endif;
                        ?>
                </div>
                <h4><?= $arg['title'] ?></h4>
                <h6 class="text-sub single-line"><?=$arg['start'].". ".yoil($arg['start']) ?> ~ <?=$arg['end'].". ".yoil($arg['end'])?></h6>
            </div>
</a>