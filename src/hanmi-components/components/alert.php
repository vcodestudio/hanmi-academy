<?php
    $display = $arg["display"] ?? true;
    if($display):
?>
<div class="infobox alert">
<?= icon("alert_") ?>
<?= $arg["label"] ?>
</div>
<?php
    endif;
?>