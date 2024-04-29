<div class="row gap-32 page-wrap detail <?= 'type-'.get_post_type() ?>">
    <?= comp("slider-banner",["imgs"=>_acf("imgs")]) ?>
    <div class="col-2 gap-32 detail">
        <div class="row gap-32">
            <div class="row gap-12">
                <h3><?= get_the_title() ?></h3>
                <?php if($f=_acf("author")): ?>
                    <h6><?= _acf("author") ?></h6>
                <?php endif; ?>
                <?php if($f=_acf("publish")): ?>
                    <h6 class="fade"><?= $f ?> 발행</h6>
                <?php endif; ?>
            </div>
            <?php if($f=_acf("download")): ?>
            <div class="flex">
                <?= comp("download",["link"=>$f["url"],"label"=>"전문 다운로드"]) ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
            if(get_field("desc")):
        ?>
        <div class="row gap-24">
            <div class="row gap-12">
                <p>
                <?= _acf("desc") ?>
                </p>
                <!-- <div>
                    <?= comp("more_down",["label"=>"자세히 보기"]) ?>
                </div> -->
            </div>
            <hr/>
            <div class="metabox row gap-24">
                <?php if($f=_acf("meta")):?>
                <?php foreach($f as $i): ?>
                <div class="flex gap-24">
                    <p class="bold"><?= $i['title'] ?></p>
                    <p><?= $i['desc'] ?></p>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
            endif;
        ?>
    </div>
    <?php if($f=_acf("text-1")): ?>
    <div class="accordion col col-2 tab-1">
        <h5 class="title" click="$('.tab-1').toggleClass('open')">
            목차
            <?= icon('chevron/down','i_close') ?>
            <?= icon('chevron/up','i_open') ?>
        </h5>
        <div>
            <?= $f ?>
        </div>
    </div>
    <?php endif; ?>
    <?php if(($f=_acf("text-2")) && ($ff=get_field_object("text-2"))): ?>
    <div class="accordion col col-2 tab-3">
        <h5 class="title" click="$('.tab-3').toggleClass('open')">
            <?= $ff["label"] ?>
            <?= icon('chevron/down','i_close') ?>
            <?= icon('chevron/up','i_open') ?>
        </h5>
        <div>
            <?= $f ?>
        </div>
    </div>
    <?php endif; ?>
    <?php if($f=_acf("authors")): ?>
    <div class="accordion col col-2 tab-2">
        <h5 class="title" click="$('.tab-2').toggleClass('open')">
            저자
            <?= icon('chevron/down','i_close') ?>
            <?= icon('chevron/up','i_open') ?>
        </h5>
        <div>
            <?php foreach($f as $i): ?>
            <div class="row gap-12">
                <h4>정승원</h4>
                <p class="fade">1920 - </p>
                <p>정승원은 서울 출생으로 런던을 기반으로 활동하고 있다. 2017년 중앙대학교 사진학과를 졸업하고 2019년 런던대학교 Slade School of Fine Art에서 석사 학위를 취득했다. 정승원은 인간의 기억의 불완전함을 사진과 직물을 이용해 표현한다. 작가는 초기 치매 진단을 받은 할머니의 시간을 추적한 연작 《Kyung Ae》(2016)를 시작으로 인간의 기억에 대한 관심을 확장해왔다. 직물이라는 가변적인 소재에 사진을 프린트하고 작품에 물리적 변형을 가하여 다층적인 의미를 더한다. 그간 《Memories Full of Forgetting》(2017~ ), 《Bark》(2018), 《Digital Strata》(2019) 등의 연작을 선보였다. 2018년 UCL Art Museum 입주작가, 2019년 런던 포토그래퍼스 갤러리 TPG New Talent 등에 선정되었으며, 『LensCulture』, 『The Guardians』, 『X=Y Magazine』 등 다양한 주요 해외 매체에서 소개되며 국제적인 이목을 끌고 있다.</p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <hr/>
    <?php endif; ?>
    <div class="flex">       
        <a href="<?= wp_get_referer()?wp_get_referer():'/' ?>" class="button">
                목록으로
        </a>
    </div>
</div>