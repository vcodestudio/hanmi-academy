<div class="row gap-32">

    <div class="row gap-32 detail w-limit">
        <?= comp("slider-banner", ["imgs" => _acf("detail_imgs") ?: _acf("imgs"), "forceSlider" => true, "showBullets" => true]) ?>
        <div class="col-2 gap-32 detail">
            <div class="row gap-24">
                <div class="row gap-12">
                    <h3><?= get_the_title() ?></h3>
                    <h6 class="light"><?= getDateRange() ?></h6>
                </div>
            </div>
            <div class="row gap-24">
                <div class="metabox row gap-24">
                    <?php if($additional_info = _acf("additional_info")): ?>
                        <?php foreach($additional_info as $info): ?>
                            <div class="flex gap-24">
                                <?php if(!empty($info['title'])): ?>
                                    <p class="bold"><?= $info['title'] ?></p>
                                <?php endif; ?>
                                <?php if(!empty($info['description'])): ?>
                                    <p><?= $info['description'] ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if(!empty($info['has_attachments']) && !empty($info['attachments'])): ?>
                                <div class="row gap-16">
                                    <?php foreach($info['attachments'] as $attachment): ?>
                                        <?php if(!empty($attachment['file'])): ?>
                                            <?php
                                            $file = $attachment['file'];
                                            $file_url = $file['url'] ?? '';
                                            $file_label = !empty($attachment['file_label']) 
                                                ? $attachment['file_label'] 
                                                : ($file['filename'] ?? '다운로드');
                                            ?>
                                            <div class="flex">
                                                <?= comp("download", [
                                                    'label' => $file_label,
                                                    'link' => $file_url
                                                ]) ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="w-limit row gap-24">
        <?php if ($desc = _acf("desc")): ?>
        <div class="row gap-16">
            <p>
                <?= $desc ?>
            </p>
        </div>
        <?php endif; ?>

        <?php if ($f = _acf("imgs")): ?>
        <!-- gallery -->
        <div class="col-4 gap-24 _square" gallery>
            <?php foreach ($f as $item): ?>
            <div class="item">
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