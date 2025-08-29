<?php
global $checkIndex;
$checkIndex = $checkIndex ?? 1;
$checkIndex++;
?>
<div class="checkbox" <?= comp_attr_str($arg['attr'] ?? []) ?>>
    <input type="checkbox" change="<?= $arg["change"] ?? '' ?>" id="c-<?= $checkIndex ?>" <?= comp_attr_str($arg['input_attr'] ?? []) ?>/>
    <label for="c-<?= $checkIndex ?>">
        <span class="check"></span>
        <?= $arg['label'] ?>
    </label>
</div>