<?php
    get_header();
?>
<style>
    html,body,iframe {
        background:#000;
    }
    .header,
    .footer {
        display: none !important;
    }
    .vimeo_wrap {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        lefT: 0;
        display:flex;
        align-items:center;
    }
    .vimeo_wrap>div {
        width: 100%;
    }
    .btn_wrap {
        position:absolute;
        top:0;
        left:0;
        width:100%;
        height:100%;
        z-index:1;
        display:flex;
        padding:2rem;
        justify-content:center;
        align-items:flex-end;
    }
    #vm {
        padding:0px!important;
        width:100%;
        height:100%;
    }
</style>
<?php
        $f = explode("/",get_field("front_video","option"));
        $f = $f[array_key_last($f)];
        $f = explode("?",$f);
        $f = $f[0];
        $f = is_numeric($f)?$f:false;
?>
<div class="vimeo_wrap">
    <div id="vm" style="padding:56.25% 0 0 0;position:relative;">
    <?php
        if($f):
    ?>
    <iframe
            src="https://player.vimeo.com/video/<?= $f ?>?h=5e0c51e792&api=1&autoplay=1&loop=1&color=ffffff&title=0&byline=0&portrait=0&background=1&muted=1"
            style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
            allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
    <?php
        endif;
    ?>
    </div>
    <div class="btn_wrap">
        <a href="/" class="button">
            메인페이지로 이동
        </a>
    </div>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script>
        const player = new Vimeo.Player(vm);
        player.play();
        player.on("ended",e=>{
            vm.style.display = "none";
            location.href="/";
        });
    </script>
</div>
<?php
    get_footer();
?>