<div class="row gap-32">

    <div class="row gap-32 detail w-limit">
        <?= comp("slider-banner", ["imgs" => _acf("imgs")]) ?>
        <div class="col-2 gap-32 detail">
            <div class="row gap-24">
                <div class="row gap-12">
                    <h3><?= get_the_title() ?></h3>
                    <h6 class="light"><?= getDateRange() ?></h6>
                </div>
            </div>
            <div class="row gap-24">
                <div class="metabox row gap-24">
                    <div class="flex gap-24">
                        <p class="bold">Title</p>
                        <p>Description</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gap-32 _content">
        <!-- 내용 -->
        <div class="w-limit">
                                <!-- 전시소개 -->
                    <h4>전시소개</h4>
        </div>
        <div class="col-1-3 gap-32 w-limit">
            <?php if ($f = _acf("poster")): ?>
            <div>
                <?= img($f, "large") ?>
            </div>
            <?php endif; ?>
            <div class="row gap-16">
                <div class="row gap-8">
                    <h4><?= get_the_title() ?></h4>
                    <p>2022. 04. 06. 목 - 2022. 06. 05. 목</p>
                </div>
                <div>
                    <?= get_field("desc") ?>
                </div>
            </div>
        </div>
    </div>
    <div class="w-limit row gap-24">
        <div class="row gap-16">
            <div class="row gap-8">
                <h4>홍길동 작가</h4>
                <p class="gray light">1925 - </p>
                <p>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Voluptatem sequi velit fugit, nisi consequuntur ipsum nostrum fugiat debitis repellat eveniet mollitia veritatis minus, unde quod expedita quam non reiciendis animi.
                </p>
            </div>
            <!-- <p>
            Sodales eu venenatis, id a sed quis aliquet a orci. Malesuada sit urna dui pellentesque tincidunt purus. Rutrum tortor, facilisis nullam bibendum.Sodales eu venenatis, id a sed quis aliquet a orci. Malesuada sit urna dui pellentesque tincidunt purus. Rutrum tortor, facilisis nullam bibendum.
            </p> -->
        </div>

        <?php if ($f = _acf("imgs")): ?>
        <!-- gallery -->
        <div class="col-4 gap-24 _square " gall>

            <code class="displaynone">
                <?php
                $imgs = [];
                foreach ($f as $img):
                	$imgs[] = img_src($img, "large");
                endforeach;
                echo implode(",", $imgs);
                ?>
            </code>
            <?php // for 16 times
            foreach ($f as $item): ?>
            <div class="item" gall>
                <?= img($item, "thumb") ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="w-limit row gap-32">
        <hr />
        <div class="flex">
            <a href="<?= getPage("exhibition")->permalink ?>" class="button">
                목록으로
            </a>
        </div>
    </div>
</div>