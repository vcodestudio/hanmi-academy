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
        <div class="faq row gap-24">
            <?php foreach(_acf("content") as $key=>$item): ?>
            <div class="item row gap-24">
                <div class="title flex gap-24 middle  cursor-pointer" click="$(e.currentTarget).parent().toggleClass('active')">
                    <h6 class="light text-sub"><?= str_pad($key+1,2,"0",STR_PAD_LEFT) ?></h6>
                    <h4 class="flex-auto"><?= $item['title'] ?></h4>
                    <div class="arrow">
                        <?= icon("chevron/down","down") ?>
                        <?= icon("chevron/up","up") ?>
                    </div>
                </div>
                <div class="flex gap-24">
                    <div></div>
                    <div class="flex-auto text">
                        <?= $item['content'] ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>