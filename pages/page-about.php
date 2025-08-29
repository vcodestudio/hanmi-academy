<?php
    get_header();

    $args = [
        ["title" => "Photo Craft", "content" => "사진의 즐거움과 가치를 찾아내는 것을 목표로 다양한 사진적 언어를 찾기 위한 메커니즘의 기본원리를 이해하고 이론에 기반하여 촬영실습을 유기적으로 진행합니다."],
        ["title" => "Photo Craft", "content" => "사진의 즐거움과 가치를 찾아내는 것을 목표로 다양한 사진적 언어를 찾기 위한 메커니즘의 기본원리를 이해하고 이론에 기반하여 촬영실습을 유기적으로 진행합니다."],
        ["title" => "Photo Craft", "content" => "사진의 즐거움과 가치를 찾아내는 것을 목표로 다양한 사진적 언어를 찾기 위한 메커니즘의 기본원리를 이해하고 이론에 기반하여 촬영실습을 유기적으로 진행합니다."],
        ["title" => "Photo Craft", "content" => "사진의 즐거움과 가치를 찾아내는 것을 목표로 다양한 사진적 언어를 찾기 위한 메커니즘의 기본원리를 이해하고 이론에 기반하여 촬영실습을 유기적으로 진행합니다."]
    ];
?>
<div class="page-wrap row">
    <div class="row py-[60px] m:py-[24px]">
        <p class="text-[22px] m:text-[16px]">
            뮤지엄한미 아카데미는 <br>
            <strong>사진예술의 확장과 다가가는 미술관</strong>인 뮤지엄한미와 발 맞추어<br>
            열린 공간, 창작 공간, 소통 공간이라는 <strong>특화된 교육환경</strong>에서<br>
            사진과 예술을 사랑하는 <strong>모두를 위한 아카데미</strong>를 지향합니다.
        </p>
    </div>

    <div class="row gap-32 py-[60px] m:gap-16 m:py-[24px]">
        <div class="flex justify-between flex-wrap gap-24 m:gap-16 m:gap-y-[10px]">
            <div class="flex-auto">
                <h3>뮤지엄한미 아카데미<br/> 4가지 정규과정</h3>
            </div>
            <div class="flex-none max-w-[640px] w-full">
               사진 입문과정부터 포트폴리오 제작과 전시를 세부적으로 기획하고 진행하는 필수과정을 통해 사진언어를 이해하고 개별적으로 표현하는데 목표를 두고 있습니다. 또한 양질의 교육을 제공하는 각 과정이 체계적이고 유기적으로 이어질 수 있도록 내실 있게 운영되고 있습니다.
            </div>
        </div>
    </div>

    <?php foreach ($args as $value): ?>
    <div class="row gap-32 py-[20px] m:gap-16 m:py-[16px]">
        <hr />
        <div class="flex gap-16 justify-between flex-wrap gap-y-8 m:gap-x-0 m:gap-y-[10px]">
            <img class="w-full m:order-2 m:h-[300px]" src="https://placehold.co/1080x400" alt="Photo Craft 이미지" />
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
