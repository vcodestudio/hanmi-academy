<div class="radio flex middle gap-24">
    <?php
    $id = $arg['id'] ?? $arg["name"] ?? "radios";
    $items = $arg['item'] ?? $arg["options"] ?? $arg["items"] ?? [];
    $change = $arg["change"] ?? false;
    foreach($items as $key=>$value):
    ?>
    <div class="item">
        <input
            type="radio"
            name="<?= $id ?>"
            value="<?= $value['value'] ?? $value[0] ?? '' ?>"
            id="<?= "${id}-${key}" ?>"
            <?= comp_attr_str($value['input_attr'] ?? []) ?>
            <?= $value["ia"] ?? "" ?>
            <?= $change?"change=\"".$change."\"":"" ?>
            <?=
                (($q=(($arg["selected"] ?? false) == ($value['value'] ?? false)))?
                "checked":
                ($key == 0))?"checked":""
             ?>
        />
        <label class="flex middle gap-8" for="<?= "${id}-${key}" ?>">
            <div class="anchor"></div>
            <div class="single-line">
                <?= $value['label'] ?? $value[1] ?? '' ?>
            </div>
        </label>
    </div>
    <?php endforeach; ?>
</div>