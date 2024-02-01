<div class="floater_space">
    <div class="floater">
        <div target="_blank" class="item w-limit flex gap-1r middle">
            <div class="body col gap-0 center flex-auto">
                <img src="<?= getImg("main_h.svg") ?>" />
                <div class="thumb" style="background-image:url(<?= getImg("floater.png") ?>)">
                    <?= comp("more_w",["label"=>"뮤지엄한미 관람 예약하기"]) ?>
                </div>
                <img src="<?= getImg("main_i.svg") ?>" />
            </div>
            <div class="text mob flex-none">
                <?= comp("more_w",["label"=>"뮤지엄한미 관람 예약하기"]) ?>
            </div>
            <a href="<?= _acf("book_link","option") ?>" class="abs_fit"></a>
        </div>
    </div>
</div>