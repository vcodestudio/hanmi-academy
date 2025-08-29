<?php
    if($parent = get_post_parent()):
?>
<h1 class="title">뮤지엄한미 연구소</h1>
<div class="row gap-24">
    <div class="img-banner">
        <img src="<?= _acf("thumb",$parent)["sizes"]["large"] ?>"/>
    </div>
    <div class="col gap-24">
    <!-- col-2-1 -->
        <div class="flex middle">
        <?= $parent->post_content ?>
        </div>
        <!-- <div>
            <img src="<?= HM::$lab_logo ?>"/>
        </div> -->
    </div>
</div>
<?php
    endif;
?>