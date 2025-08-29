<?php
get_header();
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
                    <button type="submit" href="<?= getPage("account-create-select")->permalink ?>" class="w">
                        신청하기
                    </button>
                </div>
            </form>
            <!-- <div class="row gap-1r disabled">
                <h5 class="single-line">뮤지엄한미 맴버십 <strong>10만원 / 1년</strong></h5>
                <p class="fade">
                멤버십을 가입하시면 다양한 기획전시 프리뷰 및 전용 프로그램을 제공하며, 이용 시설 10% 할인 등 멤버십만을 위한 다양한 혜택을 누리실 수 있습니다.
                </p>
                <div class="flex">
                    <a href="#" class="button w">
                        신청하기
                    </a>
                </div>
            </div> -->
            <div class="row gap-1r pannel">
                <h5 class="single-line">뮤지엄한미 후원하기</h5>
                <p class="fade">
                후원하실 수 있습니다. 후원자에게는 다양한 혜택을 제공하고 있습니다.                </p>
                <div class="flex">
                    <a href="#" class="button">
                        담당자에게 문의하기
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo temp("account-footer");
get_footer();
?>