<a href="<?= $arg['link'] ?? '#' ?>" class="item row gap-12 single-line">
    <h4>
        <?= $arg["title"] ?>
    </h4>
    <div class="light fade">
        <div class="flex divider">
            <h6 class="fade">
                <?= $arg["date"] ?>
            </h6>
            <h6 class="fade">
                <?= $arg["author"] ?>
            </h6>
        </div>
    </div>
</a>