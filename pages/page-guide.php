<?php
get_header();
?>
<div class="row gap-32 page-wrap">
    <h3><?= get_the_title() ?></h3>
    <div>
        <?php
        $fields = _acf('content');
        $tabs = [];
        foreach($fields as $i=>$cont):
            $tabs[] = [
                'label'=>$cont['title'],
                'url'=>"#section-${i}",
            ];
        endforeach;
        ?>
        <?= comp('tab_custom',$tabs) ?>
    </div>
    <?php foreach($fields as $i=>$cont):?>
    <div class="col-2 gap-32" id="section-<?= $i ?>">
        <h4><?= $cont['title'] ?></h4>
        <div class="<?= $cont['isTextBox']?'tbox':'' ?> row gap-24">
            <?= $cont['text'] ?>
        </div>
    </div>
    <hr/>
    <?php endforeach; ?>
</div>
<?php get_footer(); ?>