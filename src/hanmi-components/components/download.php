<a class="button w !py-[0.625rem] !px-[1.125rem]" href="<?= $arg['link'] ?? $arg["url"] ?? "." ?>" download <?= comp_attr_str($arg['attr'] ?? []) ?>>
    <span><?= $arg['label'] ?></span>
</a>