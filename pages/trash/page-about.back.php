<?php
    get_header();

    $args = [
        ["title" => "Photo Craft", "content" => "사진의 즐거움과 가치를 찾아내는 것을 목표로 다양한 사진적 언어를 찾기 위한 메커니즘의 기본원리를 이해하고 이론에 기반하여 촬영실습을 유기적으로 진행합니다."],
        ["title" => "Photo Work", "content" => "사진의 완성을 위한 심화과정으로 동시대 예술에 대한 미학적 접근과 논의를 중심으로 사진가로서의 역량을 위한 체계적인 작품제작과 창작 노하우를 습득합니다."],
        ["title" => "Photo Research 사진연구반", "content" => "전시를 목표로 실전능력을 함양하는 과정으로 사진과 미술관련 실무자 특강을 통한 실제적 지식을 체계화하고 창의적 컨셉에 의거한 작업을 진행합니다."],
        ["title" => "Creative Studio 창작스튜디오", "content" => "작가 양성을 위한 인큐베이팅 과정으로 사진가 및 기획자, 평론가 등 예술관련 전문가들과의 개별 매칭을 통해 예술가로서 요구되는 필수사항을 심도 있게 다룹니다."]
    ];
?>
<div class="page-wrap row">
    <div class="row py-[60px] m:py-[24px] m:pt-0">
        <p class="text-[22px] m:text-[16px]">
            뮤지엄한미 아카데미는 <br>
            <strong>사진예술의 확장과 다가가는 미술관</strong>인 뮤지엄한미와 발 맞추어<br class="pc">
            열린 공간, 창작 공간, 소통 공간이라는 <strong>특화된 교육환경</strong>에서<br class="pc">
            사진과 예술을 사랑하는 <strong>모두를 위한 아카데미</strong>를 지향합니다.
        </p>
    </div>

    <div class="row gap-32 py-[60px] m:gap-16 m:py-[24px] m:pb-0">
        <div class="flex justify-between flex-wrap gap-24 m:gap-16 m:gap-y-[10px]">
            <div class="flex-auto">
                <h3 class="leading-[1.5em]">뮤지엄한미 아카데미<br class="!block"/> 4가지 정규과정</h3>
            </div>
            <div class="flex-none max-w-[640px] w-full">
               사진 입문과정부터 포트폴리오 제작과 전시를 세부적으로 기획하고 진행하는 필수과정을 통해 사진언어를 이해하고 개별적으로 표현하는데 목표를 두고 있습니다. 또한 양질의 교육을 제공하는 각 과정이 체계적이고 유기적으로 이어질 수 있도록 내실 있게 운영되고 있습니다.
            </div>
        </div>
    </div>

    <?php foreach ($args as $index => $value): ?>
    <div class="row gap-32 py-[20px] m:gap-16 m:py-[16px]">
        <hr />
        <div class="flex gap/8 justify-between flex-wrap gap-y-8 m:gap-x-0 m:gap-y-4">
            <img class="w-full h-[400px] object-cover m:w-[328px] m:h-[300px] m:order-2" src="<?= get_template_directory_uri() ?>/src/imgs/system/academy/about-<?= $index + 1 ?>.jpg" alt="<?= $value["title"] ?> 이미지" />
            <div class="flex-auto m:order-1">
                <h4 class="bold"><?= $value["title"] ?></h4>
            </div>
            <div class="flex-none max-w-[640px] w-full m:order-3 keep-all">
                <?= $value["content"] ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php
    get_footer();
?>
