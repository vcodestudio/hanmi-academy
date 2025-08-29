<?php
    $display = $arg["display"] ?? true;
    if($display):
?>
<div class="infobox">
<?= icon("info") ?>
<?= $arg["label"] ?>
</div>
<?php
    endif;
?>