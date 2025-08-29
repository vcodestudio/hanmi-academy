<div class="flex right filter-toggle">
    <div class="col">
        <button class="w toggle" click="filter.utils.filterOpen ^= 1">
            <?= $arg["label"] ?? "상세필터" ?>
            <?= icon('chevron/up','up') ?>
            <?= icon('chevron/down','down') ?>
        </button>
    </div>
</div>