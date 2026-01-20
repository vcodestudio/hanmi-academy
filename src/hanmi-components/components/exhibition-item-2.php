    <?php
    $posts = $arg["posts"];
    $query = $arg["query"] ?? [];
    while ($posts->have_posts()):
        $posts->the_post();
        $thumb = _acf("thumb");
        $thumb_url = $thumb ? ($thumb["sizes"]["large"] ?? $thumb["url"] ?? '') : '';
        if (empty($thumb_url)) continue; // 이미지가 없으면 스킵
        ?>
    <div class="program-card flex flex-col gap-[1.5rem]">
        <a href="<?= esc_url(get_permalink()) ?>" class="block">
            <div class="relative w-full">
                <img class="block w-full h-auto object-contain" src="<?= esc_url($thumb_url) ?>" alt="<?= esc_attr(get_the_title()) ?>" />
            </div>
        </a>
        <div class="flex flex-col gap-[0.5rem]">
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
            <div class="flex items-center gap-[0.5rem]">
                <?php
                $tag_count = count($tag_items);
                foreach ($tag_items as $index => $tag_item):
                ?>
                    <span class="text-[1rem] font-bold leading-[1.5rem] tracking-[-0.0125rem] text-black no-hover">
                        <?= esc_html($tag_item["text"]) ?>
                    </span>
                    <?php if ($index < $tag_count - 1): ?>
                    <div class="bg-[rgba(0,0,0,0.1)] h-[1rem] w-px"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="flex flex-col">
                <a href="<?= esc_url(get_permalink()) ?>">
                    <div class="text-[1.5rem] leading-[2.25rem] font-bold tracking-[-0.0125rem] text-black"><?= get_the_title() ?></div>
                </a>
            </div>
        </div>
    </div>
    <?php
    endwhile;
    wp_reset_postdata();


?>

