<?php
    $filters = $arg['filters'] ?? [];
?>
<form class="search flex gap-1r" method="get" action="<?= $arg['root'] ?? './' ?>">
    <?php
        foreach ($filters as $item) {
            ?>
    <input type="hidden" name="<?= $item[0] ?? $item ?>" value="<?= $item[1] ?? "" ?>" />
    <?php
        }
    ?>
    <input class="flex-auto" type="text" name="<?= $arg['prop'] ?? 'search' ?>"
        placeholder="<?= $arg['placeholder'] ?? '제목 또는 키워드를 입력하세요.' ?>" value="<?= $_GET["search"] ?? $_GET["s"] ?? "" ?>" />
    <input type="submit" class="button flex-none" value="검색" />
</form>