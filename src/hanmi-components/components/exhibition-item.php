    <?php
    $posts = $arg["posts"];
    $query = $arg["query"] ?? [];
    while ($posts->have_posts()):
        $posts->the_post(); ?>
    <div class="program-card flex flex-col gap-6">
        <?php $color = _acf("color") ?? "#E8A433"; ?>
        <a href="<?= esc_url(get_permalink()) ?>" class="block">
            <div class="relative w-full" style="background: <?= $color ?>;">
                <div class="relative w-full">
                    <img class="w-full h-auto object-contain" src="<?= _acf("thumb")["sizes"]["large"] ?>" alt="<?= esc_attr(get_the_title()) ?>" />
                    <div class="absolute inset-0" style="opacity:.8; background: <?= $color ?>;"></div>
                </div>
            </div>
        </a>
        <div class="flex flex-col gap-3">
            <div class="inline-flex gap-2">
                <?php
                $terms = ["location"];
                foreach ($terms as $term):
                    if ($f = _acf($term)):
                        $filter_query = $query;
                        foreach ($terms as $t) {
                            unset($filter_query[$t]);
                        }
                        $filter_query[$term] = $f->slug; ?>
                        <a href="?<?= http_build_query($filter_query) ?>" class="tag small"><?= $f->name ?></a>
                    <?php
                    endif; ?>
                <?php
                endforeach;
                ?>
            </div>
            <div class="flex flex-col gap-2">
                <a href="<?= esc_url(get_permalink()) ?>">
                    <div class="text-[1rem] leading-[1.2em] font-bold"><?= get_the_title() ?></div>
                </a>
                <div class="caption"><?= function_exists('getDateRange') ? getDateRange() : get_start_end_format(get_post()) ?></div>
            </div>
        </div>
    </div>
    <?php
    endwhile;
    wp_reset_postdata();


?>
