<?php
get_header();
?>
<div class="row gap-32 page-wrap detail">
    <?= comp("slider-banner") ?>
    <div class="col-2 gap-32 detail">
        <div class="row gap-12">
            <h3>제목</h3>
            <h6>정승원(사진)</h6>
            <h6 class="fade">2022. 04. 08 발행</h6>
        </div>
        <div class="row gap-24">
            <div class="row gap-12">
                <p>
                한국과 리투아니아의 공식 수교 30주년 기념으로 기획한《Uncoverings: 리투아니아 사진의 정체성 탐구》는 국내에서 처음으로 리투아니아 예술 사진의 흐름을 소개하는 전시다. 리투아니아 사진의 태동기부터 현재까지의 흐름을 개괄한 전시는 역사의 흐름 속에서 리투아니아 사진이 어떻게 변화했는지, 사진 속에서 리투아니아의 정체성 문제...
                </p>
                <div>
                    <?= comp("more_down",["label"=>"자세히 보기"]) ?>
                </div>
            </div>
            <hr/>
            <div class="metabox row gap-24">
                <div class="flex gap-24">
                    <p class="bold">장소</p>
                    <p>뮤지엄 한미 </p>
                </div>
                <div class="flex gap-24">
                    <p class="bold">규격</p>
                    <p>뮤지엄 한미 </p>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion col col-2 tab-1">
        <h5 class="title" click="$('.tab-1').toggleClass('open')">
            목차
            <?= icon('chevron/down','i_close') ?>
            <?= icon('chevron/up','i_open') ?>
        </h5>
        <div>
        (앞)
CW35 젊은 사진가 포트폴리오 2021: 정지현
4p_작가노트
6p_Construction Site(2012)
22p_Construct(2017)
36p_Reconstruct(2020)
52p_기획노트
(뒤)
CW35 젊은 사진가 포트폴리오 2021: 정승원
4p_작가노트
6p_Memories Full of Forgetting(2017~)
28p_Bark(2018)
50p_기획노트
        </div>
    </div>
    <div class="accordion col col-2 tab-2">
        <h5 class="title" click="$('.tab-2').toggleClass('open')">
            저자
            <?= icon('chevron/down','i_close') ?>
            <?= icon('chevron/up','i_open') ?>
        </h5>
        <div class="row gap-12">
            <h4>정승원</h4>
            <p class="fade">1920 - </p>
            <p>정승원은 서울 출생으로 런던을 기반으로 활동하고 있다. 2017년 중앙대학교 사진학과를 졸업하고 2019년 런던대학교 Slade School of Fine Art에서 석사 학위를 취득했다. 정승원은 인간의 기억의 불완전함을 사진과 직물을 이용해 표현한다. 작가는 초기 치매 진단을 받은 할머니의 시간을 추적한 연작 《Kyung Ae》(2016)를 시작으로 인간의 기억에 대한 관심을 확장해왔다. 직물이라는 가변적인 소재에 사진을 프린트하고 작품에 물리적 변형을 가하여 다층적인 의미를 더한다. 그간 《Memories Full of Forgetting》(2017~ ), 《Bark》(2018), 《Digital Strata》(2019) 등의 연작을 선보였다. 2018년 UCL Art Museum 입주작가, 2019년 런던 포토그래퍼스 갤러리 TPG New Talent 등에 선정되었으며, 『LensCulture』, 『The Guardians』, 『X=Y Magazine』 등 다양한 주요 해외 매체에서 소개되며 국제적인 이목을 끌고 있다.</p>
        </div>
    </div>
    <hr/>
    <div class="flex">       
        <a href="<?= wp_get_referer()?wp_get_referer():'/' ?>" class="button">
                목록으로
        </a>
    </div>
</div>
<?php get_footer(); ?>