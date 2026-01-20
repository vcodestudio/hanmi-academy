    <?php
    $posts = $arg["posts"];
    while ($posts->have_posts()):
        $posts->the_post(); ?>
    <div class="program-card flex flex-col gap-[1.5rem]">
        <a href="<?= esc_url(get_permalink()) ?>" class="block">
            <div class="relative w-full" style="">
                <div class="relative w-full">
                    <img class="block w-full h-auto object-contain" src="<?= _acf("thumb")["sizes"]["large"] ?>" alt="<?= esc_attr(get_the_title()) ?>" />
                    <?php
                    $deadline_status = _acf("deadline_status");
                    if ($deadline_status && $deadline_status !== 'none'):
                        $deadline_labels = [
                            'soon' => ['마감', '임박'],
                            'closed' => ['마감', '']
                        ];
                        if (isset($deadline_labels[$deadline_status])):
                    ?>
                    <div class="rounded-full bg-black w-[4.3rem] h-[4.3rem] absolute left-4 bottom-4 text-ascent items-center justify-center flex">
                        <div class="flex flex-col text-center text-[#5fb800] text-[1rem] font-bold leading-[1.375rem] tracking-[-0.0125rem]">
                            <?php if ($deadline_labels[$deadline_status][0]): ?>
                            <p class="mb-0"><?= $deadline_labels[$deadline_status][0] ?></p>
                            <?php endif; ?>
                            <?php if ($deadline_labels[$deadline_status][1]): ?>
                            <p><?= $deadline_labels[$deadline_status][1] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                        endif;
                    endif;
                    ?>
                </div>
            </div>
        </a>
        <div class="flex flex-col gap-[0.5rem]">
            <?php
            $tag_items = [];
            if (_acf("product_purchasable")) {
                $tag_items[] = [
                    "text" => "신청중",
                    "link" => "?date=before"
                ];
            }
            $tax_tags = [
                [
                    "slug" => "course",
                    "label" => null
                ],
                [
                    "slug" => "participant",
                    "label" => null
                ]
            ];
            foreach ($tax_tags as $tax_tag):
                $terms = get_the_terms(get_the_ID(), $tax_tag["slug"]);
                if ($terms && !is_wp_error($terms)):
                    foreach ($terms as $term):
                        $tag_items[] = [
                            "text" => $tax_tag["label"] ?: $term->name,
                            "link" => "?{$tax_tag['slug']}={$term->slug}"
                        ];
                    endforeach;
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

