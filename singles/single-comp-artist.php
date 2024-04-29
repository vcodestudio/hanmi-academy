<?php
include_once DIR."/pages/portfolio/template.php";
$mh = new MH_P();
$mh->header();
?>
                    <div class="bg-black">
                        <?= comp("slider-banner",["imgs"=>[_acf("thumb")]]) ?>
                    </div>
                    <div class="gap-32 col-2 detail">
                        <div class="gap-12 row">
                            <?php
                                if($f=_acf("portrait")):
                            ?>
                            <img src="<?= $f['sizes']['large'] ?>" style="width:150px; height:auto;" />
                            <?php
                                endif;
                            ?>
                            <h3><?= get_the_title() ?></h3>
                            <p class="light-gray">
                                <?= _acf("job") ?>
                            </p>
                        </div>
                        <div class="gap-24 row">
                            <?php if($f=_acf("desc")):?>
                            <div class="gap-12 row">
                                <p>
                                    <?= $f ?>
                                </p>
                            </div>
                            <hr />
                            <?php endif; ?>
                            <div class="gap-24 metabox row">
                                <?php if($f=_acf("infos")):?>
                                <?php foreach($f as $i): ?>
                                <div class="flex gap-24">
                                    <p class="bold"><?= $i['title'] ?></p>
                                    <p class="gap-0 row">
                                        <?php
                                            if($ff=$i['links']):
                                            foreach($ff as $ii):
                                                if($iii = $ii["link"]):
                                        ?>
                                        <a href="<?= $iii['url'] ?? "" ?>" target="_blank"
                                            class="light hover-underline"><?= $iii['title'] ?? "" ?></a>
                                        <?php
                                                endif;
                                            endforeach;
                                        endif;
                                        ?>
                                    </p>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <?php if($f=_acf("photos")): ?>
                    <div class="gap-24 row">
                        <h3>작품</h3>
                        <div class="gap-24 col col-4">
                            <?php
                                foreach($f as $item):
                            ?>
                            <img src="<?= $item["sizes"]["large"] ?? getImg("sample.png") ?>" alt="<?= $item["title"] ?? "" ?>" class="w-full" zoom />
                            <?php
                                endforeach;
                            ?>
                        </div>
                    </div>
                    <hr />
                    <?php endif; ?>
                    <?php if($f=_acf("relative-publish")):?>
                    <div class="gap-24 row">
                        <h3>연계 도록</h3>
                        <div class="gap-24 book-list row">
                            <?php foreach($f as $item): ?>
                            <?= comp("book-item",[
                                // search media library with ID
                                "thumb_src"=>get_field("thumb",$item)["sizes"]["thumbnail"] ?? "",
                                    "link"=>get_permalink($item),
                                    "title"=>get_the_title($item),
                                    "author"=>get_field("author",$item),
                                    // "meta"=>implode("/",get_field("meta",$item)),
                                    "desc"=>get_field("desc",$item)
                                ]) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <hr />
                    <?php endif; ?>
                    <!-- <hr/> -->
                    <div class="flex">
                        <?php
                        // get wp referer
                        $referer = wp_get_referer();
                        // print prev link
                        if($referer && strpos($referer,"/portfolio") !== false):
                        ?>
                        <a href="javascript:window.history.back()" class="button">
                            목록으로
                        </a>
                        <?php
                        else:
                        ?>
                        <a href="/portfolio" class="button">
                            목록으로
                        </a>
                        <?php
                        endif;
                        ?>
                    </div>
<?php
    $mh->footer();
?>