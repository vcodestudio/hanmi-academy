<div class="row gap-32 page-wrap detail">
    <?= comp("slider-banner",["imgs"=>_acf("imgs")]) ?>
    <div class="col-2 gap-32 detail">
        <div class="row gap-12">
            <h3><?= get_the_title() ?></h3>
            <h6 class="light"><?= getDateRange() ?></h6>
            <?php if($f=(_acf("book") ?? _acf("book-program"))): ?>
                <div class="flex">
                    <a href="<?= $f ?>" class="button" target="_blank">예약하기</a>
                </div>
            <?php endif; ?>
        </div>
        <div class="row gap-24">
            <?php if($f=_acf("desc")):?>
            <div class="row gap-12">
                <p>
                    <?= $f ?>
                </p>
                <!-- <div>
                    <?= comp("more_down",["label"=>"자세히 보기"]) ?>
                </div> -->
            </div>
            <hr/>
            <?php endif; ?>
            <div class="metabox row gap-24">
                <?php if($f=_acf("meta")):?>
                <?php foreach($f as $i): ?>
                <div class="flex gap-24">
                    <p class="bold"><?= $i['title'] ?></p>
                    <p><?= $i['desc'] ?></p>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php if($f=_acf("location")):?>
                <div class="flex gap-24">
                    <p class="bold">장소</p>
                    <p><?= (_acf("location_text") && !empty(_acf("location_text")))?_acf("location_text"):"뮤지엄한미 $f->name" ?></p>
                </div>
                <?php endif; ?>
                <?php if($f=_acf("price")): ?>
                <div class="flex gap-24">
                    <p class="bold">관람료</p>
                    <p>
                        <?= $f ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php if($f = _acf("artists")): ?>
                <div class="flex gap-24">
                    <p class="bold">참여작가</p>
                    <div class="row gap-16">
                        <p>
                            <?= $f ?>
                        </p>
                        <?php if($ff=_acf("download-artist")):?>
                        <div class="flex">
                            <?= comp("download",['label'=>'작가 정보 다운로드','link'=>$ff['url']]) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if($f=_acf("download-news")): ?>
                <div class="flex gap-24">
                    <p class="bold">보도자료</p>
                    <div class="flex">
                        <?= comp("download",['label'=>'전체 다운로드','link'=>$f["url"]]) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <hr/>
    <?php if($f=_acf("photos")): ?>
    <div class="row gap-24">
        <h3>현장 기록</h3>
        <div class="swiper-pic swiper-container" data-slidesPerView="5" data-spaceBetween="10">
                <div class="swiper-wrapper">
                    <?php foreach($f as $item): ?>
                    <div class="swiper-slide">
                        <img src="<?= $item["sizes"]["large"] ?? getImg("sample.png") ?>" zoom/>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
        </div>
    </div>
    <hr/>
    <?php endif; ?>
    <?php if($f=_acf("relative-exhibition")): ?>
    <div class="row gap-24">
        <h3>연계 전시</h3>
        <div class="gallery-view block swiper swiper-container" data-slidesPerView="3">
            <div class="swiper-wrapper">
                <?php
                foreach($f as $i):
                ?>
                    <div class="swiper-slide">
                        <?= comp("gallery-item",[
                            'link'=>get_permalink($i),
                            'thumb'=>get_field("thumb",$i)["sizes"]["large"],
                            'location'=>get_field("location",$i)->name,
                            'state'=>dateState(get_field("start",$i),get_field("end",$i))["name"],
                            'start'=>get_field("start",$i),
                            'end'=>get_field("end",$i),
                            'title'=>get_the_title($i)
                        ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
    <hr/>
    <?php endif; ?>
    <?php if($f=_acf("relative-program")): ?>
    <div class="row gap-24">
        <h3>연계 프로그램</h3>
        <div class="gallery-view block swiper swiper-container" data-slidesPerView="3">
            <div class="swiper-wrapper">
                <?php
                foreach($f as $i):
                ?>
                    <div class="swiper-slide">
                        <?= comp("gallery-item",[
                            'link'=>get_permalink($i),
                            'thumb'=>get_field("thumb",$i)["sizes"]["large"],
                            'location'=>get_field("location",$i)->name,
                            'state'=>dateState(get_field("start",$i),get_field("end",$i))["name"],
                            'start'=>get_field("start",$i),
                            'end'=>get_field("end",$i),
                            'title'=>get_the_title($i)
                        ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
    <hr/>
    <?php endif; ?>
    <?php if($f=_acf("relative-publish")):?>
    <div class="row gap-24">
        <h3>연계 도록</h3>
        <div class="book-list row gap-24">
            <?php foreach($f as $item): ?>
                <?= comp("book-item",[
                    "link"=>get_permalink($item),
                    "title"=>get_the_title($item),
                    "author"=>get_field("author",$item),
                    "meta"=>implode("/",get_field("meta",$item)),
                    "desc"=>get_field("desc",$item)
                ]) ?>
            <?php endforeach; ?>
        </div>
    </div>
    <hr/>
    <?php endif; ?>
    <?php if($f=_acf("news")): ?>
    <div class="row gap-24">
        <h3>관련 기사</h3>
        <div class="row gap-24">
            <?php foreach($f as $item): ?>
            <?= comp("board-item",[
                "link"=>$item["link"]["url"],
                "title"=>$item["link"]["title"],
                "date"=>$item["date"],
                "author"=>$item["author"]
            ]) ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    <!-- <hr/> -->
    <?php if($f=_acf("license")): ?>
    <div class="accordion col col-2 tab-1">
        <h5 class="title" click="$('.tab-1').toggleClass('open')">
            라이센스
            <?= icon('chevron/down','i_close') ?>
            <?= icon('chevron/up','i_open') ?>
        </h5>
        <div>
            <?= $f ?>
        </div>
    </div>
    <hr/>
    <?php endif; ?>
    <div class="flex">
        <a href="/" class="button">
            목록으로
        </a>
    </div>
</div>