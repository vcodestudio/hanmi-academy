<div class="chip" <?= comp_attr_str($arg['attr'] ?? []) ?>>
    <input type="radio" id="<?= $arg['id'] ?>" <?= comp_attr_str($arg['input_attr'] ?? []) ?>/>
    <label class="button r w" for="<?= $arg['id'] ?>">
        <?= $arg['label'] ?>
    </label>
</div>