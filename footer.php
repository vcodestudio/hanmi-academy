</div>

<div id="panel" class="panel">
	<div class="item row gap-32">
		<div class="col gap-1r">
			<h3 class="flex-auto title single-line"> </h3>
			<div class="close flex-none flex middle" click="popup(false)">
				<?= icon("close") ?>
			</div>
		</div>
		<div class="cont">

		</div>
	</div>
	
</div>

<div class="footer">
	<div class="wrap row gap-36">
		<div class="row gap-24">
			<div>
				<img src="<?= HM::$logo_w ?>" />
			</div>
			<p>
				가현문화재단이 개관한 국내 최초 사진전문 미술관으로 시작한 한미사진미술관은, <br />
				국내 사진사의 체계화와 사진문화예술의 활성화를 위해 최선의 노력을 다해왔습니다.
			</p>
		</div>
		<div class="col-2 gap-28 m-col-1">
			<div class="col gap-28 m-col-1">
				<?php foreach(get_field("footer_menu","option") as $item):
				if($link=$item['link']): ?>
				<a href="<?= $link['url'] ?>" target="<?= $link['target'] ?>"><?= $link['title'] ?></a>
				<?php
				endif;
				endforeach;
				?>
			</div>
			<div class="col right gap-28 m-col-1">
				<?php foreach(get_field("footer_menu_right","option") as $item):
				if($link=$item['link']): ?>
				<a href="<?= $link['url'] ?>" target="<?= $link['target'] ?>"><?= $link['title'] ?></a>
				<?php
				endif;
				endforeach;
				?>
			</div>
		</div>
	</div>
</div>
<!-- overlay -->
<div class="img_overlay" click="if(e.target.classList.contains('img_overlay')) $('.img_overlay').toggleClass('active')">
	<div class="row gap-12 zoom_">
		<div class="col right">
			<button click="$('.img_overlay').toggleClass('active')">
				<?= icon("close_w") ?>
			</button>
		</div>
		<img class="img" src="<?= getImg("sample.png") ?>" />
	</div>
	<div class="row gap-12 gall_">
		<div class="col right">
			<button click="$('.img_overlay').toggleClass('active')">
				<?= icon("close_w") ?>
			</button>
		</div>
		<div class="swiper ov_gall" data-slidesperview="1" data-spacebetween="0">
			<div class="swiper-wrapper">
				
			</div>
		<div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
		</div>
		<p class="w desc">
			
		</p>
	</div>
</div>
</body>
<?php wp_footer(); ?>
</html>