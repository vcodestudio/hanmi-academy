<a href="<?= $arg["link"] ?>" class="item flex gap-24 reg">
    <div class="flex-none">
        <img src="<?= $arg["thumb"]["sizes"]["thumbnail"] ?? getImg("empty.svg") ?>" />
    </div>
    <div class="flex-auto row gap-24 middle">
        <div class="row gap-12 single-line">
            <h4><?= $arg["title"] ?></h4>
            <p><?= $arg["author"] ?></p>
            <p class="fade">
                <?= $arg["meta"] ?>
            </p>
        </div>
        <p>
            <?= $arg["desc"] ?>
        </p>
    </div>
</a>