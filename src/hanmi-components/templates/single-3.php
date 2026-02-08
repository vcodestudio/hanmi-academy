<?php
// 프로그램 ID
$program_id = get_the_ID();

// ACF 필드에서 상품 정보 가져오기
$product_price = intval(_acf("product_price") ?? 0);
$product_purchasable = _acf("product_purchasable") ?? false;
$product_stock = intval(_acf("product_stock") ?? 0); // 수강인원 제한

// 현재 신청자 수 가져오기
$current_applicants = intval(get_post_meta($program_id, 'program_applicants_count', true) ?? 0);

// 상품 정보가 있는지 확인 (판매 가능 체크박스가 체크되어 있으면 상품 정보 있음)
$has_product_info = $product_purchasable;

// 로그인 체크
$is_logged_in = is_user_logged_in();
$current_user = $is_logged_in ? wp_get_current_user() : null;

// 신청하기 버튼 URL 및 상태 결정
$order_url = home_url('/order?program_id=' . $program_id);
$can_apply = false;
$apply_message = '';
$needs_login = false;

if (!$is_logged_in) {
    // 로그인하지 않은 경우 - 버튼은 보여주되 로그인 필요 표시
    $login_url = home_url('/login?redirect_to=' . urlencode(get_permalink()));
    $needs_login = true;
    // 상품 정보가 있으면 버튼 활성화 (로그인 확인 후 이동)
    if ($has_product_info) {
        $can_apply = true;
    } else {
        $apply_message = '현재 신청할 수 없는 프로그램입니다.';
    }
} elseif (!$has_product_info) {
    // 상품 정보가 없는 경우
    $order_url = 'javascript:void(0)';
    $apply_message = '현재 신청할 수 없는 프로그램입니다.';
} else {
    // 정상적인 경우
    $can_apply = true;
    // 상품이 판매 가능한지 확인
    if (!$product_purchasable) {
        $apply_message = '현재 신청할 수 없는 프로그램입니다.';
        $can_apply = false;
    } elseif ($product_stock > 0 && $current_applicants >= $product_stock) {
        // 수강인원 제한이 있고, 현재 신청자 수가 제한을 초과한 경우
        $apply_message = '수강인원이 초과되어 마감되었습니다.';
        $can_apply = false;
    }
}
?>
<div class="row gap-32 page-wrap detail w-limit">
    <?= comp("slider-banner", ["imgs" => _acf("detail_imgs") ?: (_acf("thumb") ? [_acf("thumb")] : []), "forceSlider" => true, "showBullets" => true]) ?>
    <div class="col-2 gap-32 detail overflow-hidden">
        <div class="row gap-24 overflow-hidden">
            <div class="row gap-12">
                <h3 class="m:text-[1.25rem] m:leading-[1.75rem]"><?= get_the_title() ?></h3>
                <h6 class="light"><?= getDateRange() ?></h6>
            </div>
            <div class="flex">
                <?php if ($can_apply): ?>
                    <?php if ($needs_login): ?>
                        <a href="javascript:void(0)" 
                           class="button" 
                           onclick="var btn = document.querySelector('.header .wrap.pc a[href=&quot;javascript:void()&quot;]'); if(btn) btn.click();">
                            신청하기
                        </a>
                    <?php else: ?>
                        <a href="<?= esc_url($order_url) ?>" class="button">신청하기</a>
                    <?php endif; ?>
                <?php elseif ($apply_message): ?>
                    <p style="margin-top: 0; font-size: 0.875rem; color: #888;"><?= esc_html($apply_message) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="row gap-24 overflow-hidden">
            <?php if ($f = _acf("desc")): ?>
            <div class="row gap-12">
                <p>
                    <?= $f ?>
                </p>
            </div>
            <hr />
            <?php endif; ?>
            <div class="metabox row gap-24">
                <?php if ($f = _acf("location")): ?>
                <div class="flex gap-24">
                    <p class="bold">장소</p>
                    <p><?= _acf("location_text") &&
                    !empty(_acf("location_text"))
                    	? _acf("location_text")
                    	: "뮤지엄한미 $f->name" ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if ($f = _acf("meta")): ?>
                <?php foreach ($f as $i): ?>
                <div class="flex gap-24">
                    <p class="bold"><?= $i["title"] ?></p>
                    <p><?= $i["desc"] ?></p>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php if ($f = _acf("uploads")): ?>
                <div class="flex gap-24 min-w-0">
                    <p class="bold">참고자료</p>
                    <div class="row gap-16 min-w-0 overflow-hidden">
                        <?php foreach ($f as $i): ?>
                        <?php if (isset($i["item"]) && is_array($i["item"]) && isset($i["item"]["url"])): ?>
                        <a class="underline single-line block" href="<?= esc_url($i["item"]["url"]) ?>"
                            target="_blank"><?= esc_html($i["item"]["filename"] ?? basename($i["item"]["url"])) ?></a>
                        <div class="flex">
                            <?= comp("download", [
                            	"label" => "다운로드",
                            	"link" => esc_url($i["item"]["url"]),
                            ]) ?>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($f = get_field("detail")): ?>
    <hr />
    <div class="row gap-16">
        <h6>상세내용</h6>
        <p>
            <?= $f ?>
        </p>
    </div>
    <?php endif; ?>
    <hr />
    <div class="flex">
        <a href="<?= getPage("activity")->permalink ?>" class="button">
            목록으로
        </a>
    </div>
</div>