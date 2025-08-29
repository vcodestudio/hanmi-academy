    <?php
    $posts = $arg["posts"];
    while ($posts->have_posts()):
        $posts->the_post(); ?>
    <div class="item" style="background-image:url(<?= _acf("thumb")["sizes"][
        "large"
    ] ?>)">
        <div class="color" style="background-color:<?= _acf("color") ??
            "#000" ?>"></div>
        <div class="meta row gap-1r single-line">
            <div class="col gap-8">
                <?php
                $terms = ["location", "program_theme", "job"];
                foreach ($terms as $term):
                    if ($f = _acf($term)):
                        if (isset($query)) {
                            foreach ($terms as $t) {
                                unset($query[$t]);
                            }
                        } ?>
                        <a
                            <?php if (isset($query)):
                                $query[$term] = $f->slug; ?>
                            href="?<?= http_build_query($query) ?>"
                            <?php
                            endif; ?>
                            class="tag small ghost"><?= $f->name ?></a>
                    <?php
                    endif; ?>
                <?php
                endforeach;
                ?>
            </div>
            <a href="<?= get_permalink() ?>">
                <h4 class="w">
                    <?= get_the_title() ?>
                </h4>
            </a>
            <h6><?= getDateRange() ?></h6>
            <?php if ($f = _acf("product")):
                // get total quantity
                $prod = wc_get_product($f);
                $stock = $prod->get_stock_quantity();
                if ($stock < 5): ?>
                <div class="alert">
                    마감<br/>
                    임박
                </div>
            <?php endif;
            endif; ?>
            <a class="next" href="<?= get_permalink() ?>">
                <?= icon("arrow/right_w") ?>
            </a>
            <a class="link" href="<?= get_permalink() ?>">
            </a>
        </div>
    </div>
    <?php
    endwhile;
    wp_reset_postdata();


?>
