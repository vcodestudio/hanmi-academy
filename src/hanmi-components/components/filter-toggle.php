<div class="flex right filter-toggle">
    <div class="col">
        <button class="toggle" :class="{open: filter.utils.filterOpen}" click="filter.utils.filterOpen ^= 1">
            <span><?= $arg["label"] ?? "상세필터" ?></span>
        </button>
    </div>
</div>