<?php
    $name = $arg["name"] ?? "";
    $label = $arg["label"] ?? "";
    $allows = $arg["allows"] ?? [""];
    $attrs = $arg["attrs"] ?? "";
    // if attrs is array, convert to string
    if(is_array($attrs)) {
        $attrs = implode(" ", $attrs);
    }
    // 용량은 최대 8mb
    $max_size = $arg["max_size"] ?? 1;
    $max_size = $max_size * 1024 * 1024;
    $desc = $arg["desc"] ?? "";
?>
<div class="flex items-center w-auto gap-8">
    <input
        type="file"
        name="<?= $name ?>"
        id="<?= $name ?>"
        class="hidden"
        accept="<?= implode(",", $allows) ?>"
        max-size="<?= $max_size ?>"
        <?= $attrs ?>
        change="
        const target = e.currentTarget;
        target.disabled = true;
        const maxSize = <?= $max_size ?>;
        if(target?.files[0]?.size > maxSize) {
            alert(`파일 용량이 너무 큽니다. 최대 ${maxSize / 1024 / 1024}MB까지 가능합니다.`);
            e.currentTarget.value = '';
        } else {
            target.parentElement.querySelector('label').textContent = target.files[0]?.name ?? '';
            target.disabled = false;
        }
        " />
    <button class="download w" click="
        const target = e.currentTarget.parentElement.querySelector('input');
        if(target.disabled) target.disabled = false;
        target.click();
    ">
        <?= $label ?>
    </button>
    <label>
        <?= $desc ?>
    </label>
</div>