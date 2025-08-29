<?php
    get_header();
    $src = get_stylesheet_directory()."/src/mainpay/";
    ?>
<div class="row gap-32 page-wrap">
    <div>
    <?php
        var_dump(CURLOPT_URL);
        require_once $src."call_api.php";
    ?>
    </div>
</div>
<?php
    get_footer();
?>