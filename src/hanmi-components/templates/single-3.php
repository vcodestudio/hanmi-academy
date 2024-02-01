<?php
    $product = _acf("product");
    // get woocommerce product info from id
    $product = wc_get_product( $product );
    // get price of it
    // $price = $product->get_price();
    // get stack of it
    // $stock = $product->get_stock_quantity();
    // get buy link of it
    // $link = $product->add_to_cart_url();
?>
<div class="row gap-32 page-wrap detail">
    <?= comp("slider-banner",["imgs"=>_acf("imgs")]) ?>
    <div class="col-2 gap-32 detail">
        <div class="row gap-24">
            <div class="row gap-12">
                <h3><?= get_the_title() ?></h3>
                <h6 class="light"><?= getDateRange() ?></h6>
            </div>
            <div class="flex">
                <?php if($f=$product): ?>
                <a href="<?= $f->add_to_cart_url() ?>" class="button" target="_blank">신청하기</a>
                <?php elseif($f=_acf("book-program")): ?>
                <a href="<?= $f ?>" class="button" target="_blank">신청하기</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="row gap-24">
            <?php if($f=_acf("desc")):?>
            <div class="row gap-12">
                <p>
                    <?= $f ?>
                </p>
            </div>
            <hr />
            <?php endif; ?>
            <div class="metabox row gap-24">
                <?php if($f=_acf("location")):?>
                <div class="flex gap-24">
                    <p class="bold">장소</p>
                    <p><?= (_acf("location_text") && !empty(_acf("location_text")))?_acf("location_text"):"뮤지엄한미 $f->name" ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if($f=_acf("meta")):?>
                <?php foreach($f as $i): ?>
                <div class="flex gap-24">
                    <p class="bold"><?= $i['title'] ?></p>
                    <p><?= $i['desc'] ?></p>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php
                    if($f=_acf("uploads")):
                ?>
                <div class="flex gap-24">
                    <p class="bold">참고자료</p>
                    <div class="row gap-16">
                        <?php
                    foreach($f as $i):
                ?>
                        <a class="underline" href="<?= $i['item']['url'] ?>"
                            target="_blank"><?= $i['item']['filename'] ?></a>
                        <div class="flex">
                            <?= comp("download",['label'=>'다운로드','link'=>$i['item']['url']]) ?>
                        </div>
                        <?php
                endforeach;
                ?>
                    </div>
                </div>
                <?php
                    endif;
                ?>
            </div>
        </div>
    </div>
    <?php
        if($f=get_field("detail")):
    ?>
    <hr />
    <div class="row gap-16">
        <h6>상세내용</h6>
        <p>
            <?= $f ?>
        </p>
    </div>
    <?php
        endif;
    ?>
    <hr />
    <div class="flex">
        <a href="<?= getPage("activity")->permalink ?>" class="button">
            목록으로
        </a>
    </div>
</div>