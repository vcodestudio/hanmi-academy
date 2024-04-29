<?php
    get_header();
?>
<div class="row gap-32 page-wrap">
    <div class="row gap-8">
        <h3>아카이브</h3>
        <p>Id enim scelerisque facilisi nunc faucibus. Arcu semper nunc eu fringilla egestas ultricies velit nullam diam. Lacus vitae dolor tellus egestas egestas diam.</p>
    </div>
    <div class="archive-list">
        <?php
            $posts = get_field("archives","option");
            foreach($posts as $post):
                $info = $post["info"];
        ?>
        <div class="item">
            <div class="col gap-16 a-title" click="archiveToggle(e)">
                <p>
                    <?= $info["title"] ?>
                    <br>
                    <span class="gray mob"><?= $info["project_name"] ?></span>
                </p>
                <p class="pc"><?= $info["project_name"] ?></p>
                <p><?= $info["date_start"] ?><br class="mob">~<?= $info["date_end"] ?></p>
                <div>
                    <?= icon("chevron_w/down","icon") ?>
                </div>
            </div>
            <div class="col gap-16 a-content">
                <div>
                    <?= $info["desc"] ?>
                </div>
                <div class="swiper w-full" data-slidesperview="2.2" data-mslidesperview="2.2" data-spacebetween="16">
                    <div class="swiper-wrapper">
                    <?php
                        foreach($post["gallery"] as $img):
                    ?>
                    <div class="swiper-slide static">
                        <img src="<?= $img["sizes"]["medium"] ?>" data-src="<?= $img["sizes"]["large"] ?>" alt="" zoom>
                        </div>
                    <?php
                        endforeach;
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
            endforeach;
        ?>
    </div>
</div>
<?php
    get_footer();
?>