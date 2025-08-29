<?php
    $row_class="field col gap-24 p-gap-8";
    $col_class="field-input row gap-1r non-stretch";
    $item_class="field-item flex gap-1r middle";
?>
<div class="<?= $row_class ?>">
<h6>인증번호</h6>
<div class="<?= $col_class ?>">
    <div class="<?= $item_class ?>">
        <input type="text" name="cert_numb" placeholder="6자리 번호입력"/>
    </div>
    <p class="caption" id="email_caption"></p>
</div>
</div>