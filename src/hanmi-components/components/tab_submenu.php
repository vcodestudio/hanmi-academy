<?php
$menu_items = wp_get_nav_menu_items( 'main' );
$parent = current(wp_filter_object_list( $menu_items, array( 'object_id' => get_queried_object_id())))->menu_item_parent ?? 0;
$posts = wp_filter_object_list($menu_items, ['menu_item_parent'=>$parent]);
?>
<div class="col tab">
    <?php foreach($posts as $i=>$item): ?>
        <?php
            $selected = (get_the_ID() == $item->object_id);
            if(get_post($item->object_id)->post_status == "publish"):
        ?>
            <a href="<?= $item->url ?>" class="<?= $selected?'selected':'' ?>"><?= $item->title ?></a>
    <?php
    endif;
    endforeach;
    ?>
</div>