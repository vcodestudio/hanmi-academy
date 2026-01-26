    <?php
    $posts = $arg["posts"];
    $query = $arg["query"] ?? [];
    while ($posts->have_posts()):
        $posts->the_post();
        $thumb = _acf("thumb");
        $thumb_url = $thumb ? ($thumb["sizes"]["large"] ?? $thumb["url"] ?? '') : '';
        if (empty($thumb_url)) continue; // 이미지가 없으면 스킵
        ?>
    <div class="program-card flex flex-col gap-6">
        <a href="<?= esc_url(get_permalink()) ?>" class="block">
            <div class="relative w-full">
                <img class="block w-full h-auto object-contain" src="<?= esc_url($thumb_url) ?>" alt="<?= esc_attr(get_the_title()) ?>" />
            </div>
        </a>
        <div class="flex flex-col gap-3">
            <?php
            $tag_items = [];
            $terms = ["location"];
            foreach ($terms as $term):
                if ($f = _acf($term)):
                    $filter_query = $query;
                    foreach ($terms as $t) {
                        unset($filter_query[$t]);
                    }
                    $filter_query[$term] = $f->slug;
                    $tag_items[] = [
                        "text" => $f->name,
                        "link" => "?" . http_build_query($filter_query)
                    ];
                endif;
            endforeach;
            
            if (!empty($tag_items)):
            ?>
            <div class="inline-flex gap-2 flex-wrap">
                <?php foreach ($tag_items as $tag_item): ?>
                    <span class="tag small no-hover"><?= esc_html($tag_item["text"]) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="flex flex-col gap-2">
                <a href="<?= esc_url(get_permalink()) ?>">
                    <div class="text-[1rem] leading-[1.2em] font-bold"><?= get_the_title() ?></div>
                </a>
                <div class="caption"><?= function_exists('getDateRange') ? getDateRange() : '' ?></div>
            </div>
        </div>
    </div>
    <?php
    endwhile;
    wp_reset_postdata();
?>

