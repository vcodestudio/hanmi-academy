<?php
include_once DIR."/pages/portfolio/template.php";
$mh = new MH_P();
$mh->header();
?>
                    <div class="gap-32 col-1-3 detail">
                        <div class="gap-12 row">
                            <?php
                                if($f=_acf("thumb")):
                            ?>
                            <img src="<?= $f['sizes']['large'] ?>" class="block w-full" />
                            <?php
                                endif;
                            ?>

                        </div>
                        <div class="gap-16 row">
                            <div class="gap-4 row">
                                <h3><?= get_the_title() ?></h3>
                                    <h6 class="light-gray">
                                        <?= _acf("job") ?>
                                    </h6>
                            </div>
                            <?php if($f=_acf("desc")):?>
                                <p>
                                    <?= $f ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr />
                    <div class="flex">
                        <?php
                        // get wp referer
                        $referer = wp_get_referer();
                        // print prev link
                        if($referer && strpos($referer,"/portfolio") !== false):
                        ?>
                        <a href="javascript:window.history.back()" class="button">
                            목록으로
                        </a>
                        <?php
                        else:
                        ?>
                        <a href="/portfolio" class="button">
                            목록으로
                        </a>
                        <?php
                        endif;
                        ?>
                    </div>
    <?php
    $mh->footer();
    ?>