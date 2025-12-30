    <?php
    $posts = $arg["posts"];
    $query = $arg["query"] ?? [];
    while ($posts->have_posts()):
        $posts->the_post(); ?>
    <div class="program-card flex flex-col gap-6">
        <a href="<?= esc_url(get_permalink()) ?>" class="block">
            <div class="relative w-full">
                <img class="block w-full h-auto object-contain" src="<?= _acf("thumb")["sizes"]["large"] ?>" alt="<?= esc_attr(get_the_title()) ?>" />
            </div>
        </a>
        <div class="flex flex-col gap-3">
            <?php
            $terms = ["location"];
            $has_tags = false;
            foreach ($terms as $term):
                if ($f = _acf($term)):
                    $has_tags = true;
                    break;
                endif;
            endforeach;
            
            if ($has_tags):
            ?>
            <div class="inline-flex gap-2 flex-wrap">
                <?php
                foreach ($terms as $term):
                    if ($f = _acf($term)): ?>
                        <span class="tag small no-hover"><?= $f->name ?></span>
                    <?php
                    endif; ?>
                <?php
                endforeach;
                ?>
            </div>
            <?php endif; ?>
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
