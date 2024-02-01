<?php
    get_header();
?>
<div class="page-wrap row gap-32 v-init">
    <div class="row gap-12">
        <div class="flex gap-8 bottom">
            <h3>뮤지엄한미 멤버십</h3>
            <p>|</p>
            <h4>MH Membership</h4>
        </div>
        <div class="row gap-12">
            <?php
                the_content();
            ?>
        </div>
    </div>
    <div class="col-3 gap-24">
        <!-- <div class="row gap-24 pannel card">
            <input type="hidden" value="adult" name="age" />
            <div class="row gap-12">
                <h5>
                    <strong>
                        뮤지엄한미 웹 회원 무료
                    </strong>
                </h5>
                <p>무료</p>
                <ul class="list">
                웹회원은 뮤지엄한미 방문 예약 및 프로그램을 신청하실 수 있으며, 전시, 프로그램 등 미술관 소식을 받아보실 수 있습니다.
                </ul>
            </div>
            <div class="flex center bottom h_fit">
                <p>
                    <a href="<?= getPage("account-create-select")->permalink ?>" class="w button" type="submit">
                        가입하기
                    </a>
                </p>
            </div>
        </div> -->
        <?php
            $prods = get_field("membership","options");
            foreach($prods as $f):
                if($f['product']):
                $product = wc_get_product($f['product']);
                // add cart
                $link = $product->add_to_cart_url();
                ?>
        <div class="row gap-24 pannel card">
            <input type="hidden" value="adult" name="age" />
            <div class="row gap-12">
                <h5>
                    <strong>
                        <?= $f["title"] ?>
                    </strong>
                </h5>
                <p><?= $f["price"] ?></p>
                <ul class="list">
                    <?= $f["content"] ?>
                </ul>
            </div>
            <div class="flex center bottom h_fit">
                <p>
                    <!-- <a href="<?= $link ?>" class="w button" type="submit">
                        가입하기
                    </a> -->
                </p>
            </div>
        </div>
        <?php
                endif;
            endforeach;
        ?>
    </div>
    <div></div>
    <?php
    get_footer();
?>