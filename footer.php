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
				<?= get_field("footer_description", "option") ?>
			</p>
		</div>
		<div class="row gap-28 footer-menu-wrapper">
			<div class="col gap-28 footer-menu">
				<?php foreach(get_field("footer_menu","option") as $item):
				if($link=$item['link']): ?>
				<a class="mobile:py-[10px]" href="<?= $link['url'] ?>" target="<?= $link['target'] ?>"><?= $link['title'] ?></a>
				<?php
				endif;
				endforeach;
				?>
			</div>
		</div>
		<div class="row gap-12 footer-contact">
			<?php if($phone_number = get_field("footer_phone_number","option")): ?>
			<p>
				<span class="footer-contact-label">대표번호 | </span>
				<span class="footer-contact-value"><?= $phone_number ?></span>
			</p>
			<?php endif; ?>
			<p>
				<span class="footer-contact-label">대표메일 | </span>
				<a href="mailto:academy@museumhanmi.or.kr" class="footer-contact-value" style="font-weight: normal;">academy@museumhanmi.or.kr</a>
			</p>
			<?php 
			$instagram_url = get_field("footer_instagram_url", "option");
			if (!$instagram_url) {
				$instagram_url = "https://www.instagram.com/museumhanmi_academy";
			}
			?>
			<p>
				<span class="footer-contact-label">Instagram | </span>
				<a href="<?= esc_url($instagram_url) ?>" target="_blank" rel="noopener noreferrer" class="footer-contact-value" style="font-weight: normal;">@museumhanmi_academy</a>
			</p>
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
		<p class="text-white caption"></p>
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
<!-- gallery -->
<div class="img_overlay gall_overlay bg-black">
	<div class="gap-0 row">
		<div class="col right">
			<button class="" click="document.querySelector('.img_overlay.gall_overlay').classList.remove('active')">
				<?= icon("close_w") ?>
			</button>
		</div>
		<img class="img" src="<?= getImg("sample.png") ?>" click="window.open(e.currentTarget.src)" style="cursor:zoom-in" />
		<div class="flex">
			<button class="rect" click="gallOv.move(-1)">
				<?= icon("arrow/left_w") ?>
			</button>
			<p class="flex-auto p-2 text-white bg-black caption"></p>
			<button class="rect" click="gallOv.move(1)">
				<?= icon("arrow/right_w") ?>
			</button>
		</div>
	</div>
</div>
</body>
<?php wp_footer(); ?>
</html>