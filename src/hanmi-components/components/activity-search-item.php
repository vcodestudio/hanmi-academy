<?php
    $post = $post ?? get_post();
    if($post ?? false):
?>
<div class="item row gap-1r" gall>
            <code class="displaynone">
                <?php
                $imgs=[];
                foreach(_acf("gallery",$post) as $img):
                    $imgs[] = img_src($img,"large");
                endforeach;
                echo implode(",",$imgs);
                ?>
            </code>
    <div class="thumb">
        <div>
            <img src="<?= _acf("gallery",$post)[0]["sizes"]["large"] ?>" />
        </div>
    </div>
    <div class="row gap-8">
        <h4><?= get_the_title($post) ?></h4>
        <h6 class="light"><?= _acf("author",$post) ?></h6>
    </div>
</div>
<?php
    endif;
?>