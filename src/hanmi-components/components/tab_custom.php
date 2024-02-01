<div class="col tab">
    <?php foreach($arg as $i=>$item): ?>
        <?php
            $selected = $item['selected'] ?? 0;
            ?>
            <a href="<?= $item['url'] ?>" class="<?= $selected?'selected':'' ?>"><?= $item['label'] ?></a>
    <?php endforeach; ?>
</div>