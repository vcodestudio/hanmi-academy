<?php
get_header();
// wp_redirect( getPage("account-create-select")->permalink);
echo temp("account-header");
?>
    <div class="col-2 gap-32">
        <div class="img-cover">
            <img src="<?= getImg("account-create-banner.jpg") ?>"/>
        </div>
        <div class="row gap-40">
            <form method="post" class="row gap-1r">
                <h5 class="single-line">뮤지엄한미 웹 회원 <strong>무료</strong></h5>
                <p class="fade">
                웹회원은 뮤지엄한미 방문 예약 및 프로그램을 신청하실 수 있으며, 전시, 프로그램 등 미술관 소식을 받아보실 수 있습니다.
                </p>
                <input type="hidden" name="type" value="normal"/>
                <div class="flex">
                    <a class="button w" href="<?= getPage("account-create-select")->permalink ?>" class="w">
                        신청하기
                    </a>
                </div>
            </form>
                <?php
                    if(($p = getPage("membership-select")) && $p->post_status == "publish"):
                ?>
            <div class="row gap-1r">
                <h5 class="single-line">뮤지엄한미 멤버십 | MH Membership</h5>
                <p class="fade">
                사진과 문화예술에 관심 있는 분들을 위한 뮤지엄한미 멤버십을 운영합니다. 뮤지엄한미의 전시와 교육 등 여러 문화행사의 참여 할인과 함께 회원제만의 다양한 혜택을 드리니 많은 참여 바랍니다.
                </p>
                <div class="flex">
                    <!-- <a href="<?= $p->permalink ?>" class="button w">
                        신청하기
                    </a> -->
                    <a href="https://museumhanmi.or.kr/account/membership-select/" target="_blank" class="button w">
                        신청하기
                    </a>
                </div>
            </div>
                <?php
                    endif;
                ?>
            <div class="row gap-1r w_box">
                <h5 class="single-line">뮤지엄한미 후원하기</h5>
                <p class="fade">
                    후원하실 수 있습니다. 후원자에게는 다양한 혜택을 제공하고 있습니다.
                </p>
                <div class="flex top gap-8">
                    <?php if(getPage("form-patron")): ?>
                    <a class="button" href="<?= getPage("form-patron")->permalink ?>" id="counsel">
                        담당자에게 문의하기
                    </a>
                    <?php else: ?>
                    <p class="strong">museum@museumhanmi.or.kr</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo temp("account-footer");
get_footer();
?>