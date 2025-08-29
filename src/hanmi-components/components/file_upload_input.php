<?php
    $name = $arg["name"] ?? "";
    $id = $arg["id"] ?? "";
    $allows = $arg["allows"] ?? [""];
    $callback_js = $arg["change"] ?? "";
    // 용량
    $max_size = $arg["max_size"] ?? 1;
    $max_size = $max_size * 1024 * 1024;
    $required = $arg["required"] ?? false;
?>
    <input
        type="file"
        name="<?= $name ?>"
        id="<?= $id ?>"
        class="hidden"
        accept="<?= implode(",", $allows) ?>"
        max-size="<?= $max_size ?>"
        <?= $required ? "required" : "" ?>
        change="
        const target = e.currentTarget;
        const maxSize = <?= $max_size ?>;
        if(target?.files[0]?.size > maxSize) {
            alert(`파일 용량이 너무 큽니다. 최대 ${maxSize / 1024 / 1024}MB까지 가능합니다.`);
            e.currentTarget.value = '';
        } else {
            <?= $callback_js ?>
        }
        " />