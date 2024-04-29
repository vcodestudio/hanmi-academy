<?php
    $name = $arg["name"] ?? "";
    $label = $arg["label"] ?? "";
    $id = $arg["id"] ?? "";
    $value = $arg["value"] ?? "";
    $required = $arg["required"] ?? false;
    $checked = $arg["checked"] ?? false;
?>
<div class="checkbox" <?= comp_attr_str($arg['attr'] ?? []) ?>>
    <input type="checkbox" id="<?= $id ?>" name="<?= $name ?>" value="<?= $value ?>"
    <?= $checked?"checked":"" ?>
    <?= $required?"required":"" ?>
    />
    <label for="<?= $id ?>">
        <span class="check"></span>
        <?= $arg['label'] ?>
    </label>
</div>