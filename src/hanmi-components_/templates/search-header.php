<div class="s-col-2 gap-0 single-line">
    <?= comp("search-title",["label"=>$arg["label"],"count"=>$arg["count"]]) ?>
    <div class="col right">
        <?php if($arg['link'] ?? false): ?>
        <a href="<?= $arg["link"] ?>" class="button w clean more">
            전체보기
            <?= icon('chevron/right') ?>
        </a>
        <?php endif; ?>
    </div>
</div>