<?php
    function HM_Pagination($current=1,$max=1) {
    $query = $_GET;
    unset($query['pages']);
    $query = http_build_query($query,'',"&");
    $query = (!empty($query))?$query."&":'';
    $cp = $current;
    $mp = $max;
    $range = 3;//each side
    
    $nav = [];
    for($i=max($cp-$range,1);$i<=min($mp,$cp+$range);$i++) {
        $nav[] = $i;
    }
    if(!empty($nav)):
    ?>
    <div class="pagination">
        <a href="./?<?= $query ?>pages=1" class="<?= ($nav[0] > 1)?'':'disabled' ?>" to="1">
            <?= icon('chevron-double/left') ?>
        </a>
        <a href="./?<?= $query ?>pages=<?= $nav[0]-1 ?>" class="<?= ($nav[0] > 1)?'':'disabled' ?>" to="<?= $nav[0]-1 ?>">
            <?= icon('chevron/left') ?>    
        </a>
        <?php foreach($nav as $n): ?>
            <a href="./?<?= $query ?>pages=<?= $n ?>" class="<?= ($cp == $n)?'selected':'' ?>">
                <?= $n ?>
            </a>
        <?php endforeach; ?>
        <a href="./?<?= $query ?>pages=<?= $nav[count($nav)-1] + 1 ?>" class="<?= ($nav[count($nav)-1] < $mp)?'':'disabled' ?>" to="<?= $nav[count($nav)-1] + 1 ?>">
            <?= icon('chevron/right') ?>    
        </a>
        <a href="./?<?= $query ?>pages=<?= $mp ?>" class="<?= ($nav[count($nav)-1] < $mp)?'':'disabled' ?>" to="<?= $mp ?>">
            <?= icon('chevron-double/right') ?>    
        </a>
    </div>
    <?php
    endif;
    }
    global $posts;
    $arg['query']=$arg['query']??$posts;
    $cur = $arg['query']->query['paged'] ?? 1;
    $max = $arg['query']->max_num_pages ?? 1;
    HM_Pagination($cur,$max);
    ?>