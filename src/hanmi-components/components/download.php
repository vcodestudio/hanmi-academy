<a class="button w !py-[0.625rem] !px-[1.125rem] max-w-full overflow-hidden" href="<?= $arg['link'] ?? $arg["url"] ?? "." ?>" download <?= comp_attr_str($arg['attr'] ?? []) ?>>
    <span class="single-line block"><?= $arg['label'] ?></span>
</a>