<?php
    get_header();
    ?>
<div class="page-wrap row gap-32">
    <p class="max-height:300px">
    <?php
        include DIR_SRC."/php/bulk.php";
    ?>
    </p>
    <form action="/bulk" method="post" enctype="multipart/form-data" class="row gap-1r non-stretch">
        <h3>업로드</h3>
        <input type="text" name="ptype" placeholder="포스트 타입"/>
        <input type="file" name="csv"/>
        <button type="submit" name="submit">
            CSV 업로드
        </button>
    </form>
    <?php
        echo date("Ymd");
    ?>
</div>
<?php
    get_footer();
?>