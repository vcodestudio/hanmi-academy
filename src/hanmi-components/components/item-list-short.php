<a href="#" class="item flex gap-24 reg">
<div class="flex-none">
    <img src="<?= _acf("thumb")["sizes"]["large"] ?? getImg() ?>"/>
</div>
<div class="flex-auto flex middle">
    <div class="row gap-16 single-line">
        <div class="divider">
            <h6><?= _acf("location")->name ?></h6>
            <h6><?= get_post_type_label(get_the_ID()) ?></h6>
        </div>
        <h4><?= get_the_title() ?></h4>
        <p class="fade">
            <?= _acf("start") ?> - <?= _acf("end") ?>
        </p>
    </div>
</div>
</a>