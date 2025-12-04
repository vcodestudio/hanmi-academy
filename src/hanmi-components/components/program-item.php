    <?php
    $posts = $arg["posts"];
    while ($posts->have_posts()):
        $posts->the_post(); ?>
    <div class="program-card flex flex-col gap-6">
        <a href="<?= esc_url(get_permalink()) ?>" class="block">
            <div class="relative w-full" style="">
                <div class="relative w-full">
                    <img class="w-full h-auto object-contain" src="<?= _acf("thumb")["sizes"]["large"] ?>" alt="<?= esc_attr(get_the_title()) ?>" />
                    <?php
                    $deadline_status = _acf("deadline_status");
                    if ($deadline_status && $deadline_status !== 'none'):
                        $deadline_labels = [
                            'soon' => '마감임박',
                            'closed' => '마감'
                        ];
                        if (isset($deadline_labels[$deadline_status])):
                    ?>
                    <div class="rounded-full bg-black w-[4.3rem] h-[4.3rem] absolute left-4 bottom-4 text-ascent items-center justify-center flex">
                        <span class="block max-w-[2.2rem] text-center break-all">
                            <?= $deadline_labels[$deadline_status] ?>
                        </span>    
                    </div>
                    <?php
                        endif;
                    endif;
                    ?>
                </div>
            </div>
        </a>
        <div class="flex flex-col gap-3">
            <div class="inline-flex gap-2">
                <?php
                $tax_tags = [
                    [
                        "slug" => "application",
                        "class" => "gray"
                    ],
                    [
                        "slug" => "course",
                        "class" => ""
                    ],
                    [
                        "slug" => "participant",
                        "class" => ""
                    ]
                ];
                foreach ($tax_tags as $tax_tag):
                    $terms = get_the_terms(get_the_ID(), $tax_tag["slug"]);
                    if ($terms && !is_wp_error($terms)):
                        foreach ($terms as $term):
                            echo '<a href="?'.$tax_tag["slug"].'='.$term->slug.'" class="tag small '.$tax_tag["class"].'">'.$term->name.'</a>';
                        endforeach;
                    endif;
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
