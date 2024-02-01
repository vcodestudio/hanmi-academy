<?php
    get_header();
?>
<div class="row gap-60 page-wrap">
    <div>
        <p class="_text">
            뮤지엄한미 아카데미는<br />
            <b>사진예술의 확장과 다가가는 미술관</b>인 뮤지엄한미와 발 맞추어<br />
            열린 공간, 창작 공간, 소통 공간이라는 <b>특화된 교육환경</b>에서<br />
            사진과 예술을 사랑하는 <b class="prim">모두를 위한 아카데미</b>를 지향합니다.
        </p>
    </div>
    <div class="col-1-2 gap-60">
        <div class="sticky-wrapper">
            <div class="row gap-8 sticky">
                <h3>
                    뮤지엄한미 아카데미<br />4가지 정규과정
                </h3>
                <p>
                    사진 입문과정부터 포트폴리오 제작과 전시를 세부적으로 기획하고 진행하는 필수과정을 통해 사진언어를 이해하고 개별적으로 표현하는데 목표를 두고 있습니다.<br />
                    또한 양질의 교육을 제공하는 각 과정이 체계적이고 유기적으로 이어질 수 있도록 내실 있게 운영되고 있습니다.
                </p>
            </div>
        </div>
        <div class="row _content row gap-60 m-gap-16">
            <?php
        $args = [
            [
                "Photo Craft",
                "사진의 즐거움과 가치를 찾아내는 것을 목표로 다양한 사진적 언어를 찾기 위한 메커니즘의 기본원리를 이해하고 이론에 기반하여 촬영실습을 유기적으로 진행합니다.",
                "academy/about-1.jpg"
            ],
            [
                "Photo Work",
                "사진의 완성을 위한 심화과정으로 동시대 예술에 대한 미학적 접근과 논의를 중심으로 사진가로서의 역량을 위한 체계적인 작품제작과 창작 노하우를 습득합니다.",
                "academy/about-2.jpg"
            ],
            [
                "Photo Research 사진연구반",
                "전시를 목표로 실전능력을 함양하는 과정으로 사진과 미술관련 실무자 특강을 통한 실제적 지식을 체계화하고 창의적 컨셉에 의거한 작업을 진행합니다.",
                "academy/about-3.jpg"
            ],
            [
                "Creative Studio 창작스튜디오",
                "작가 양성을 위한 인큐베이팅 과정으로 사진가 및 기획자, 평론가 등 예술관련 전문가들과의 개별 매칭을 통해 예술가로서 요구되는 필수사항을 심도 있게 다룹니다.",
                "academy/about-4.jpg"
            ]
        ];
            foreach($args as $arg):
        ?>
            <div class="row gap-8">
                <h4><?= $arg[0] ?></h4>
                <p>
                    <?= $arg[1] ?>
                </p>
            </div>
            <img src="<?= getImg($arg[2]) ?>" />
            <?php
            endforeach;
            ?>
        </div>
    </div>
</div>
<?php
    get_footer();
?>