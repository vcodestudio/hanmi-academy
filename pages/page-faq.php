<?php
get_header();
?>
<div class="row gap-32 page-wrap">
    <h1 class="title"><?= get_the_title() ?></h1>
    <div class="row gap-24">
        <div class="faq row gap-24">
            <?php foreach(_acf("content") as $key=>$cont): ?>
            <div class="item row gap-24">
                <div class="title flex gap-24 middle  cursor-pointer" click="$(e.currentTarget).parent().toggleClass('active')">
                    <h6 class="light text-sub"><?= str_pad($key+1,2,"0",STR_PAD_LEFT) ?></h6>
                    <h4 class="flex-auto"><?= $cont['title'] ?></h4>
                    <div class="arrow">
                        <?= icon("chevron/down","down") ?>
                        <?= icon("chevron/up","up") ?>
                    </div>
                </div>
                <div class="flex gap-24">
                    <div></div>
                    <div class="flex-auto text">
                        <?= $cont['text'] ?>
                    </div>
                </div>
                <?php if($link = $cont['link']): ?>
                <div class="flex gap-24">
                    <div></div>
                    <a href="<?= $link['url'] ?>" class="button nopad transparent clean more">
                        <?= $link['title'] ?>
                        <?= icon("chevron/right") ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>